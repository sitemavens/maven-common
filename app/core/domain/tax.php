<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Tax extends \Maven\Core\DomainObject {

	private $name;
	private $pluginKey;
	private $slug;
	private $country;
	private $state;
	private $value;
	private $forShipping = false;
	private $compound = false;
	private $enabled = true;
	private $statusImageUrl;
	private $taxAmount = 0;

	public function __construct( $id = false ) {

		parent::__construct( $id );


		$rules = array(
		    'name' => \Maven\Core\SanitizationRule::Text,
		    'pluginKey' => \Maven\Core\SanitizationRule::Key,
		    'slug' => \Maven\Core\SanitizationRule::Slug,
		    'country' => \Maven\Core\SanitizationRule::Text,
		    'state' => \Maven\Core\SanitizationRule::Text,
		    'value' => \Maven\Core\SanitizationRule::Float,
		    'forShipping' => \Maven\Core\SanitizationRule::Boolean,
		    'compound' => \Maven\Core\SanitizationRule::Boolean,
		    'enabled' => \Maven\Core\SanitizationRule::Boolean,
		    'statusImageUrl' => \Maven\Core\SanitizationRule::Text
		);

		$this->setSanitizationRules( $rules );
	}

	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}

	public function getPluginKey() {
		return $this->pluginKey;
	}

	public function setPluginKey( $pluginKey ) {
		$this->pluginKey = $pluginKey;
	}

	public function getSlug() {
		return $this->slug;
	}

	public function setSlug( $slug ) {
		$this->slug = $slug;
	}

	public function getCountry() {
		return $this->country;
	}

	public function setCountry( $country ) {
		$this->country = $country;
	}

	public function getState() {
		return $this->state;
	}

	public function setState( $state ) {
		$this->state = $state;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue( $value ) {
		$this->value = $value;
	}

	public function isForShipping() {
		return $this->forShipping;
	}

	public function setForShipping( $forShipping ) {
		if ( $forShipping === 'false' || $forShipping === false ) {
			$this->forShipping = FALSE;
		} else {
			$this->forShipping = $forShipping;
		}
	}

	public function isCompound() {
		return $this->compound;
	}

	public function setCompound( $compound ) {
		if ( $compound === 'false' || $compound === false ) {
			$this->compound = FALSE;
		} else {
			$this->compound = $compound;
		}
	}

	public function isEnabled() {
		return $this->enabled;
	}

	public function setEnabled( $enabled ) {
		if ( $enabled === 'false' || $enabled === false ) {
			$this->enabled = FALSE;
		} else {
			$this->enabled = $enabled;
		}
	}

	public function getStatusImageUrl() {
		return $this->statusImageUrl;
	}

	public function setStatusImageUrl( $statusImageUrl ) {
		$this->statusImageUrl = $statusImageUrl;
	}

	/**
	 * Get the tax amount
	 * @return float
	 */
	public function getTaxAmount() {
		return $this->taxAmount;
	}

	/**
	 * It saves the tax amount.
	 * @param float $taxAmount
	 */
	public function setTaxAmount( $taxAmount ) {
		$this->taxAmount = $taxAmount;
	}

}
