<?php

namespace Maven\Core\Mappers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class TaxMapper extends \Maven\Core\Db\WordpressMapper {

	public function __construct() {

		parent::__construct( "mvn_taxes" );
	}

	/* public static function getTypes() {
	  $promotions_types = array(
	  'amount' => array( 'name' => __( 'Amount' ), 'symbol' => '$', 'formula' => '' ),
	  'percentage' => array( 'name' => __( 'Percentage' ), 'symbol' => '%', 'formula' => '' ),
	  );
	  return $promotions_types;
	  }

	  public static function getSections() {
	  $sections = array(
	  'cart' => array( 'name' => 'Cart' ),
	  'item' => array( 'name' => 'Item' ),
	  'shipping' => array( 'name' => 'Shipping' )
	  );

	  return $sections;
	  } */

	public function getAll( $orderBy = "name" ) {

		$instances = array( );
		$results = $this->getResults( $orderBy );

		$registry = \Maven\Settings\MavenRegistry::instance();

		foreach ( $results as $row ) {

			$instance = new \Maven\Core\Domain\Tax();

			$this->fillObject( $instance, $row );


			//Set the status Image url
			$statusUrl = $instance->isEnabled() ? 'enabled.png' : 'disabled.png';

			//TODO: Maybe we should have a general images folder
			$instance->setStatusImageUrl( $registry->getPromotionStatusImageUrl() . $statusUrl );

			$instances[ ] = $instance;
		}

		return $instances;
	}

	public function getByPlugin( $pluginKey ) {

		$instances = array( );
		$results = $this->getResultsBy( 'plugin_key', $pluginKey );

		foreach ( $results as $row ) {
			$instance = new \Maven\Core\Domain\Tax();
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

		$tax = $this->getTax( $id );

		return $tax;
	}

	private function getTax( $id ) {

		$tax = new \Maven\Core\Domain\Tax();

		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );

		$row = $this->getRowById( $id );

		if ( ! $row )
			throw new \Maven\Exceptions\NotFoundException();

		
		$this->fillObject( $tax, $row );
		return $tax;
	}

	public function save( \Maven\Core\Domain\Tax $tax ) {

		$tax->sanitize();


		$data = array(
		    'name' => $tax->getName(),
		    'slug' => $tax->getSlug(),
		    'country' => $tax->getCountry(),
		    'state' => $tax->getState(),
		    'value' => $tax->getValue(),
		    'for_shipping' => $tax->isForShipping() ? 1 : 0,
		    'compound' => $tax->isCompound() ? 1 : 0,
		    'enabled' => $tax->isEnabled() ? 1 : 0,
		    'plugin_key' => $tax->getPluginKey()
		);
		$format = array(
		    '%s', //name
		    '%s', //slug
		    '%s', //country
		    '%s', //state
		    '%f', //value
		    '%s', //for_shipping
		    '%s', //compound
		    '%s', //enabled
		    '%s' // plugin_key
		);

		if ( ! $tax->getId() ) {
			$id = $this->insert( $data, $format );
			$tax->setId( $id );
		} else {
			$this->updateById( $tax->getId(), $data, $format );
		}

		return $tax;
	}

	public function getTaxes( \Maven\Core\Domain\TaxFilter $filter, $orderBy = 'name', $orderType = 'desc', $start = 0, $limit = 1000 ) {

		$where = '';
		$values = array( );
		//first value is plugin key

		//$values[ ] = $filter->getPluginKey();

		if ( ! $filter->getAll() ) {

			$country = $filter->getCountry();

			if ( $country ) {
				$values[ ] = $country;
				$where.=" AND country = %s";
			}
			else
			// Empty country means "all"
				$where.=" AND country = '*'";

			$state = $filter->getState();
			if ( $state ) {
				$values[ ] = $state;
				$where.=" AND state = %s";
			}
			else
			// Empty state means "all"
				$where.=" AND state = '*'";

			$enabled = $filter->getEnabled();
			if ( $enabled ) {
				$values[ ] = $enabled;
				$where.=" AND enabled = %d";
			}
		}

		if ( ! $orderBy )
			$orderBy = 'id';

		$query = "select	{$this->tableName}.*
					from {$this->tableName} 
					where  1=1
					{$where} order by {$orderBy} {$orderType}
					LIMIT %d , %d;";

		//other values
		/* $values[ ] = $orderBy;
		  $values[ ] = $orderType; */
		$values[ ] = $start;
		$values[ ] = $limit;

		$query = $this->prepare( $query, $values );

		$results = $this->getQuery( $query );

		$registry = \Maven\Settings\MavenRegistry::instance();

		$instances = array( );
		foreach ( $results as $row ) {
			$instance = new \Maven\Core\Domain\Tax();
			$this->fillObject( $instance, $row );

			//Set the status Image url
			$statusUrl = $instance->isEnabled() ? 'enabled.png' : 'disabled.png';

			//TODO: Maybe we should have a general images folder
			$instance->setStatusImageUrl( $registry->getPromotionStatusImageUrl() . $statusUrl );

			$instances[ ] = $instance;
		}

		return $instances;
	}

	public function getTaxesCount( \Maven\Core\Domain\TaxFilter $filter ) {

		$where = '';
		$values = array( );
		//first value is plugin key

		//$values[ ] = $filter->getPluginKey();

		if ( ! $filter->getAll() ) {

			$country = $filter->getCountry();

			if ( $country ) {
				$values[ ] = $country;
				$where.=" AND country = %s";
			}
			else
			// Empty country means "all"
				$where.=" AND country = '*'";

			$state = $filter->getState();
			if ( $state ) {
				$values[ ] = $state;
				$where.=" AND state = %s";
			}
			else
			// Empty state means "all"
				$where.=" AND state = '*'";

			$enabled = $filter->getEnabled();
			if ( $enabled ) {
				$values[ ] = $enabled;
				$where.=" AND enabled = %d";
			}
		}


		$query = "select	count(*)
					from {$this->tableName} 
					where  1=1
					{$where}";


		$query = $this->prepare( $query, $values );

		return $this->getVar( $query );
	}

	public function delete( $orderId ) {
		//delete the tax
		return parent::delete( $orderId );
	}

}