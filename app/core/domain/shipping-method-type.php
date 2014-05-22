<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * It defines a base class for all the shipping method types
 */
 abstract class ShippingMethodType{
	
	private $name;
	private $taxable; 
	private $handlingFee;
	private $estimatedDelivery;
	private $key;
	
	public function __construct ( $name , $key ) {
		$this->name = $name;
		$this->key  = $key;
	}
	/**
	 *
	 * @var \Maven\Core\Domain\ShippingMethodTypeDestination[]
	 */
	private $destinations = array();

	
	public function getName () {
		return $this->name;
	}

	public function getTaxable () {
		return $this->taxable;
	}

	public function getHandlingFee () {
		return $this->handlingFee;
	}

	public function setName ( $name ) {
		$this->name = $name;
	}

	public function setTaxable ( $taxable ) {
		$this->taxable = $taxable;
	}

	public function setHandlingFee ( $handlingFee ) {
		$this->handlingFee = $handlingFee;
	}

	public function getEstimatedDelivery () {
		return $this->estimatedDelivery;
	}

	public function setEstimatedDelivery ( $estimatedDelivery ) {
		$this->estimatedDelivery = $estimatedDelivery;
	}

	/**
	 * Get destinations
	 * @return \Maven\Core\Shipping\ShippingMethodTypeDestination
	 */
	public function getDestinations () {
		return $this->destinations;
	}

	/**
	 * Set destinations
	 * @param \Maven\Core\Shipping\ShippingMethodTypeDestination[] $destinations
	 */
	public function setDestinations ( $destinations ) {
		$this->destinations = $destinations;
	}

	/**
	 * Add a destination
	 * @param string $country Leave it blank to be wideworld. It should be a valid country code.
	 * @param string $state Leave it blank to be for all the country (if selected)
	 * @param float $minValue
	 * @param float $maxValue
	 */
	public function addDestination($country = "*", $state = "*", $minValue, $maxValue, $amount ){
		
		$destination = new ShippingMethodTypeDestination();
		$destination->setCountry($country);
		$destination->setState($state);
		$destination->setMinValue($minValue);
		$destination->setMaxValue($maxValue);
		$destination->setAmount($amount);
		
		$this->destinations[] = $destination;
		
	}
	
	public function getDestination( $country, $state ){
		
		if ( $this->destinations ){
			foreach( $this->destinations as $destination ){
				if ( $destination->getCountry() == $country && $destination->getState() == $state ) {
					return $destination;
				}
			}
		}
		
		return false;
	}
	
	public function existsDestination( $country, $state ){
		
		if ( $this->destinations ){
			foreach( $this->destinations as $destination ){
				if ( $destination->getCountry() == $country && $destination->getState() == $state ) {
					return true;
				}
			}
		}
		
		return false;
	}

	public function hasDestinations(){
		return ! \Maven\Core\Utils::isEmpty( $this->destinations );
	}

	public function getKey () {
		return $this->key;
	}

	public function setKey ( $key ) {
		$this->key = $key;
	}

	

}