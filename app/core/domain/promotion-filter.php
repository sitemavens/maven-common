<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class PromotionFilter extends BaseFilter{

	private $pluginKey;
	private $code;
	private $date;
	private $enabled;

	public function __construct() {
		parent::__construct();
	}

	public function getPluginKey() {
		return $this->pluginKey;
	}

	public function setPluginKey( $pluginKey ) {
		$this->pluginKey = $pluginKey;
	}

	public function getCode() {
		return $this->code;
	}

	public function setCode( $code ) {
		$this->code = $code;
	}

	public function getDate() {
		return $this->date;
	}

	public function setDate( $date ) {
		$this->date = $date;
	}

	public function getEnabled() {
		return $this->enabled;
	}

	public function setEnabled( $enabled ) {
		$this->enabled = $enabled;
	}

}

