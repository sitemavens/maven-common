<?php

namespace Maven\Core\Domain;
use \Maven\Core\Shipping\ShippingMethodTypes;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class ShippingMethod extends \Maven\Core\DomainObject {

	

	private $name;

	/**
	 *
	 * @var boolean 
	 */
	private $enabled;

	/**
	 *
	 * @var \Maven\Core\Domain\ShippingMethodType  
	 */
	private $method;
	
	private $methodType;
	
	private $description;

	
	public function __construct ( $id = false ) {
		parent::__construct( $id );

		$rules = array(
			'name' => \Maven\Core\SanitizationRule::Text,
			'enabled' => \Maven\Core\SanitizationRule::Boolean,
			'method' => \Maven\Core\SanitizationRule::SerializedObject,
			'description' => \Maven\Core\SanitizationRule::Text
		);

		$this->setSanitizationRules( $rules );
	}
	
	
	public function getName () {
		return $this->name;
	}

	public function isEnabled () {
		return $this->enabled;
	}

	public function setName ( $name ) {
		$this->name = $name;
	}

	public function setEnabled ( $enabled ) {
		$this->enabled = $enabled;
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\ShippingMethodType
	 */
	public function getMethod () {
		return $this->method;
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\ShippingMethodType $method
	 */
	public function setMethod ( $method ) {

		if ( ! $method ){
			return;
		}
		
		if ( $method instanceof \Maven\Core\Domain\ShippingMethodType ) {
			$this->method = $method;
		} else {
			switch ( $method ) {
				case \Maven\Core\Shipping\ShippingMethodTypes::FlatRate:
					$this->method = new \Maven\Core\Shipping\FlatRateShippingMethod();
					break;
				case \Maven\Core\Shipping\ShippingMethodTypes::OrderAmountTiers:
					$this->method = new \Maven\Core\Shipping\OrderAmountTiersShippingMethod();
					break;
				default:
					throw new \Maven\Exceptions\MavenException( "Invalid Type Method" );
			}
		}

	}

	public function getDescription () {
		return $this->description;
	}

	public function setDescription ( $description ) {
		$this->description = $description;
	}
	
	public function getMethodType () {
		return $this->methodType;
	}

	public function setMethodType ( $methodType ) {
		
		if ( !ShippingMethodTypes::isValid( $methodType ) ) {
			throw new \Maven\Exceptions\MavenException( 'Invalid shipping method: ' . $methodType );
		}

		$this->methodType = $methodType;
		
	}
	
}
