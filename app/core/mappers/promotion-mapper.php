<?php

namespace Maven\Core\Mappers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class PromotionMapper extends \Maven\Core\Db\WordpressMapper {

	public function __construct() {

		parent::__construct( "mvn_promotions" );
	}

	public static function getTypes() {
		$promotionsTypes = array(
		    'amount' => array( 'name' => __( 'Amount' ), 'symbol' => '$', 'formula' => '' ),
		    'percentage' => array( 'name' => __( 'Percentage' ), 'symbol' => '%', 'formula' => '' ),
		);
		return $promotionsTypes;
	}

	public static function getSections() {
		$sections = array(
		    'cart' => array( 'name' => 'Cart' ),
		    'item' => array( 'name' => 'Item' ),
		    'shipping' => array( 'name' => 'Shipping' )
		);

		return $sections;
	}

	public function getAll( $orderBy = "order_id" ) {
		$instances = array( );
		$results = $this->getResults( $orderBy );

		foreach ( $results as $row ) {
			$instance = new \Maven\Core\Domain\Promotion();
			$this->fillObject( $instance, $row );
			$instances[ ] = $instance;
		}

		return $instances;
	}

	public function getByPlugin( $pluginKey ) {

		$instances = array( );
		$results = $this->getResultsBy( 'plugin_key', $pluginKey );

		foreach ( $results as $row ) {
			$instance = new \Maven\Core\Domain\Promotion();
			$this->fillObject( $instance, $row );
			$instances[ ] = $instance;
		}

		return $instances;
	}

	/**
	 * Return an Promotion object
	 * @param int $id
	 * @return \Maven\Core\Domain\Promotion
	 */
	public function get( $id ) {

		$promotion = $this->getPromotion( $id );

		return $promotion;
	}

	private function getPromotion( $id ) {

		$promotion = new \Maven\Core\Domain\Promotion();

		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );

		$row = $this->getRowById( $id );
		if ( ! $row )
			throw new \Maven\Exceptions\NotFoundException();

		
		$this->fillObject( $promotion, $row );
		return $promotion;
	}

	public function save( \Maven\Core\Domain\Promotion $promotion ) {

		$data = array(
		    'section' => $promotion->getSection(),
		    'name' => $promotion->getName(),
		    'description' => $promotion->getDescription(),
		    'code' => $promotion->getCode(),
		    'type' => $promotion->getType(),
		    'value' => $promotion->getValue(),
		    'limit_of_use' => $promotion->getLimitOfUse(),
		    'uses' => $promotion->getUses(),
		    'from' => $promotion->getFrom(),
		    'to' => $promotion->getTo(),
		    'enabled' => $promotion->isEnabled() ? 1 : 0,
		    'exclusive' => $promotion->isExclusive() ? 1 : 0,
		    'plugin_key' => $promotion->getPluginId()
		);


		$format = array(
		    '%s', //section
		    '%s', //name
		    '%s', //description
		    '%s', //code
		    '%s', //type
		    '%d', //value
		    '%d', //limit_of_use
		    '%d', //uses
		    '%s', //from
		    '%s', //to
		    '%s', //enabled
		    '%s', //exclusive
		    '%s' // plugin_key
		);

		if ( ! $promotion->getId() ) {
			$id = $this->insert( $data, $format );
			$promotion->setId( $id );
		} else {
			$this->updateById( $promotion->getId(), $data, $format );
		}

		return $promotion;
	}

	public function getPromotions( \Maven\Core\Domain\PromotionFilter $filter, $orderBy = 'name', $orderType = 'desc', $start = 0, $limit = 1000 ) {

		$where = '';
		$values = array( );
		
		$code = $filter->getCode();
		if ( $code ) {
			$values[ ] = $code;
			$where.=" AND code = %s";
		}

		$enabled = $filter->getEnabled();
		if ( $enabled ) {
			$values[ ] = $enabled;
			$where.=" AND enabled = %d";
		}

		$date = $filter->getDate();
		if ( $date ) {
			//added twice
			$values[ ] = $date;
			$values[ ] = $date;

			$where.=" AND from <= %s AND to >= %s";
		}

		if ( ! $orderBy )
			$orderBy = 'id';

		$query = "select	{$this->tableName}.*
					from {$this->tableName} 
					where 1=1 
					{$where} order by {$orderBy} {$orderType}
					LIMIT %d , %d;";

		//other values
		/* $values[ ] = $orderBy;
		  $values[ ] = $orderType; */
		$values[ ] = $start;
		$values[ ] = $limit;
		//$query = $this->prepare( $query, $filter->getPluginKey(), $orderBy, $orderType, $start, $limit );
		$query = $this->prepare( $query, $values );

		$results = $this->getQuery( $query );

		$registry = \Maven\Settings\MavenRegistry::instance();

		$instances = array( );
		foreach ( $results as $row ) {
			$instance = new \Maven\Core\Domain\Promotion();
			$this->fillObject( $instance, $row );

			//Set the status Image url
			$statusUrl = $instance->isEnabled() ? 'enabled.png' : 'disabled.png';

			$instance->setStatusImageUrl( $registry->getPromotionStatusImageUrl() . $statusUrl );

			$instances[ ] = $instance;
		}

		return $instances;
	}

	public function getPromotionsCount( \Maven\Core\Domain\PromotionFilter $filter ) {

		$where = '';
		$values = array( );
		//first value is plugin key
		//$values[ ] = $filter->getPluginKey();

		$code = $filter->getCode();
		if ( $code ) {
			$values[ ] = $code;
			$where.=" AND code = %s";
		}

		$enabled = $filter->getEnabled();
		if ( $enabled ) {
			$values[ ] = $enabled;
			$where.=" AND enabled = %d";
		}

		$date = $filter->getDate();
		if ( $date ) {
			//added twice
			$values[ ] = $date;
			$values[ ] = $date;

			$where.=" AND from <= %s AND to >= %s";
		}

		$query = "select	count(*)
					from {$this->tableName} 
					where  1=1
					{$where}";

		$query = $this->prepare( $query, $values );

		return $this->getVar( $query );
	}

	public function getPromotionByCode( $promotionCode, $pluginKey = "" ) {
		if ( ! $promotionCode )
			throw new \Maven\Exceptions\MissingParameterException( "Promotion Code is required" );

		$query = "select	{$this->tableName}.*
					from {$this->tableName} 
					where  ( plugin_key = %s )
					AND code = %s;";
		$values = array( );
		//other values
		$values[ ] = $pluginKey;
		$values[ ] = sanitize_title( $promotionCode );

		//$query = $this->prepare( $query, $filter->getPluginKey(), $orderBy, $orderType, $start, $limit );
		$query = $this->prepare( $query, $values );

		$results = $this->getQuery( $query );

		if ( count( $results ) > 0 ) {
			$instance = new \Maven\Core\Domain\Promotion();
			$this->fillObject( $instance, $results[ 0 ] );

			return $instance;
		}

		return false;
	}

	public function delete( $orderId ) {
		//delete the promotion
		return parent::deleteRow( $orderId );
	}

}