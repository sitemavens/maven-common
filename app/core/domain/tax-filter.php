<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class TaxFilter extends BaseFilter{

	private $pluginKey;
	private $country = "*";
	private $state = "*";
	private $enabled;
	private $all = false;

	public function __construct() {
		parent::__construct();
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

