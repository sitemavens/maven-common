<?php

namespace Maven\Core\Mappers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class ShippingMethodMapper extends \Maven\Core\Db\WordpressMapper {

	public function __construct() {

		parent::__construct( "mvn_shipping_methods" );
	}

	public function getAll( $orderBy = "name" ) {

		$instances = array( );
		$results = $this->getResults( $orderBy );

		foreach ( $results as $row ) {

			$instance = new \Maven\Core\Domain\ShippingMethod();

			$this->fillObject( $instance, $row );

			$instances[ ] = $instance;
		}

		return $instances;
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
		
		return parent::delete( $id );
	}

}