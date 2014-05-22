<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * It defines a base class for all the shipping method types
 */
 class ShippingMethodTypeDestination{
	
	private $country;
	private $state;
	private $postalCode;
	private $minValue;
	private $maxValue;
	private $amount;
	
	public function getCountry () {
		return $this->country;
	}

	public function getState () {
		return $this->state;
	}

	public function getPostalCode () {
		return $this->postalCode;
	}

	public function getMinValue () {
		return $this->minValue;
	}

	public function getMaxValue () {
		return $this->maxValue;
	}

	public function setCountry ( $country ) {
		$this->country = $country;
	}

	public function setState ( $state ) {
		$this->state = $state;
	}

	public function setPostalCode ( $postalCode ) {
		$this->postalCode = $postalCode;
	}

	public function setMinValue ( $minValue ) {
		$this->minValue = $minValue;
	}

	public function setMaxValue ( $maxValue ) {
		$this->maxValue = $maxValue;
	}

	public function getAmount () {
		return $this->amount;
	}

	public function setAmount ( $amount ) {
		$this->amount = $amount;
	}



}