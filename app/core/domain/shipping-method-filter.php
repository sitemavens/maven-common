<?php

namespace Maven\Core\Domain;

class ShippingMethodFilter extends BaseFilter{

	private $name;
	
	public function __construct() {
		parent::__construct();
	}

	public function getName () {
		return $this->name;
	}

	public function setName ( $name ) {
		$this->name = $name;
	}
	 
}

