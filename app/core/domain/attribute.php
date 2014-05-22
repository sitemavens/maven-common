<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Attribute extends \Maven\Core\DomainObject {

	private $name;
	private $pluginKey;
	private $description;
	private $defaultAmount;
	private $defaultWholesaleAmount;

	public function __construct( $id = false ) {

		parent::__construct( $id );

		$rules = array(
		    'name' => \Maven\Core\SanitizationRule::Text,
		    'pluginKey' => \Maven\Core\SanitizationRule::Key,
		    'description' => \Maven\Core\SanitizationRule::Text,
			'default_amount' => \Maven\Core\SanitizationRule::Float,
			'default_wholesale_amount' => \Maven\Core\SanitizationRule::Float
		);

		$this->setSanitizationRules( $rules );
	}
	
	public function getName () {
		return $this->name;
	}

	public function getPluginKey () {
		return $this->pluginKey;
	}

	public function getDescription () {
		return $this->description;
	}

	public function setName ( $name ) {
		$this->name = $name;
	}

	public function setPluginKey ( $pluginKey ) {
		$this->pluginKey = $pluginKey;
	}

	public function setDescription ( $description ) {
		$this->description = $description;
	}

	
	public function getDefaultAmount () {
		return $this->defaultAmount;
	}

	public function getDefaultWholesaleAmount () {
		return $this->defaultWholesaleAmount;
	}

	public function setDefaultAmount ( $defaultAmount ) {
		$this->defaultAmount = $defaultAmount;
	}

	public function setDefaultWholesaleAmount ( $defaultWholesaleAmount ) {
		$this->defaultWholesaleAmount = $defaultWholesaleAmount;
	}

}
