<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class AttributeValue extends \Maven\Core\DomainObject {

	private $amount;
	private $attributeId;
	private $wholesaleAmount;
	private $thingId;
	private $pluginKey;

	public function __construct( $id = false ) {

		parent::__construct( $id );

		$rules = array(
		    'name' => \Maven\Core\SanitizationRule::Text,
		    'pluginKey' => \Maven\Core\SanitizationRule::Key,
		    'attributeId' => \Maven\Core\SanitizationRule::Integer,
			'wholesaleAmount' => \Maven\Core\SanitizationRule::Float,
			'amount' => \Maven\Core\SanitizationRule::Float
		);

		$this->setSanitizationRules( $rules );
	}
	 
	
	public function getAmount () {
		return $this->amount;
	}

	public function getAttributeId () {
		return $this->attributeId;
	}

	public function getWholesaleAmount () {
		return $this->wholesaleAmount;
	}

	public function getThingId () {
		return $this->thingId;
	}

	public function getPluginKey () {
		return $this->pluginKey;
	}

	public function setAmount ( $amount ) {
		$this->amount = $amount;
	}

	public function setAttributeId ( $attributeId ) {
		$this->attributeId = $attributeId;
	}

	public function setWholesaleAmount ( $wholesaleAmount ) {
		$this->wholesaleAmount = $wholesaleAmount;
	}

	public function setThingId ( $thingId ) {
		$this->thingId = $thingId;
	}

	public function setPluginKey ( $pluginKey ) {
		$this->pluginKey = $pluginKey;
	}



 
}
