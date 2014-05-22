<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class TaxFilter {

	private $pluginKey;
	private $country = "*";
	private $state = "*";
	private $enabled;
	private $all = false;

	private function protectField( $field ) {

		if ( ! ( $field instanceof \Maven\Core\MavenDateTime ) )
			return esc_sql( sanitize_text_field( $field ) );

		return $field;
	}

	public function __construct() {
		;
	}

	public function getPluginKey() {
		return $this->pluginKey;
	}

	public function setPluginKey( $pluginKey ) {
		$this->pluginKey = $pluginKey;
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

	public function getEnabled() {
		return $this->enabled;
	}

	public function setEnabled( $enabled ) {
		$this->enabled = $enabled;
	}
	
	public function getAll() {
		return $this->all;
	}

	public function setAll( $all ) {
		$this->all = $all;
	}



}

