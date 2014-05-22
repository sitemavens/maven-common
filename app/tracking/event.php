<?php

namespace Maven\Tracking;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class Event {

	private $category;
	private $action;
	private $label;
	private $value;
	private $properties = array();

	public function getProperties() {
		return $this->properties;
	}

	public function setProperties( $properties ) {
		$this->properties = $properties;
	}

		public function getCategory() {
		return $this->category;
	}

	public function setCategory( $category ) {
		$this->category = $category;
	}

	public function getAction() {
		return $this->action;
	}

	public function setAction( $action ) {
		$this->action = $action;
	}

	public function getLabel() {
		return $this->label;
	}

	public function setLabel( $label ) {
		$this->label = $label;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue( $value ) {
		$this->value = $value;
	}

	
	public function addProperty( $key, $value ){
		$this->properties[$key] = $value;
	}

}

