<?php

namespace Maven\Core\Mappers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class ShippingMethodMapper extends \Maven\Core\Db\WordpressMapper {

	public function __construct() {

		parent::__construct( "mvn_shipping_methods" );
	}

	public function getCount( \Maven\Core\Domain\ShippingMethodFilter $filter ) {

		$where = '';
		$values = array();

		$name = $filter->getName();
		if ( $name ) {
			$values[] = "%{$name}%";
			$where.=" AND name LIKE %s";
		}

		$query = "select count(*)
				from {$this->tableName} 
				where 1=1
				{$where}";

		$query = $this->prepare( $query, $values );

		return $this->getVar( $query );
	}
	
	public function getAll( \Maven\Core\Domain\ShippingMethodFilter $filter, $orderBy = "name", $orderType = 'desc', $start = 0, $limit = 1000 ) {
		$where = '';
		$values = array();

		$name = $filter->getName();
		if ( $name ) {
			$values[] = "%{$name}%";
			$where.=" AND name LIKE %s";
		}
		
		if ( !$orderBy ) {
			$orderBy = 'id';
		}

		$values[] = $start;
		$values[] = $limit;

		$query = "select	{$this->tableName}.*
					from {$this->tableName} 
					where 1=1 
					{$where} order by {$orderBy} {$orderType}
					LIMIT %d , %d;";

		$query = $this->prepare( $query, $values );

		$results = $this->getQuery( $query );

		$items = array();

		foreach ( $results as $row ) {
			$item = new \Maven\Core\Domain\ShippingMethod();
			$this->fillObject( $item, $row );

			$items[] = $item;
		}

		return $items;
	}
	
	 
	
	/**
	 * 
	 * @param string $orderBy
	 * @return \Maven\Core\Domain\ShippingMethod
	 */
	public function getEnabledMethods( $orderBy = "name" ) {

		$instances = array( );
		$results = $this->getResultsBy('enabled', 1, $orderBy);

		foreach ( $results as $row ) {

			$instance = new \Maven\Core\Domain\ShippingMethod();

			// We need to unserialize the method
			$row->method = unserialize( $row->method );
			
			$this->fillObject( $instance, $row );

			$instances[ ] = $instance;
		}

		return $instances;
	}

	public function save( \Maven\Core\Domain\ShippingMethod $shippingMethod ) {

		$shippingMethod->sanitize();

		$data = array(
		    'name' => $shippingMethod->getName(),
		    'enabled' => $shippingMethod->isEnabled() ? 1 : 0,
			'method' => serialize($shippingMethod->getMethod()),
			'description' => $shippingMethod->getDescription(),
			'method_type' => $shippingMethod->getMethod()->getKey()
		);

		$format = array(
		    '%s', //name
		    '%d', //enabled
			'%s', //method
			'%s', //description
			'%s'  //method_type
		);

		if ( ! $shippingMethod->getId() ) {
			$id = $this->insert( $data, $format );
			$shippingMethod->setId( $id );
		} else {
			$this->updateById( $shippingMethod->getId(), $data, $format );
		}

		return $shippingMethod;
	}
	

	public function get( $id ){
		$instance = new \Maven\Core\Domain\ShippingMethod();

		if ( !$id ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );
		}

		$row = $this->getRowById( $id );

		
		if ( !$row ) {
			throw new \Maven\Exceptions\NotFoundException("Item not found: ".$id);
		}

		// We need to unserialize the method
		$row->method = unserialize( $row->method );
			
		$this->fillObject( $instance, $row );

		return $instance;
	}

	public function delete( $id ) {
		
		return parent::deleteRow( $id );
	}

}