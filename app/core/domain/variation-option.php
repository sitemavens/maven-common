<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class VariationOption extends \Maven\Core\DomainObject {

	private $name;
	private $variationId;
	
	public function __construct( $id = false ) {
		 
		parent::__construct( $id );
		
		$rules = array(
			
			'name'				=> \Maven\Core\SanitizationRule::Text,
			'variationId'		=> \Maven\Core\SanitizationRule::Integer
			
		);
	
		$this->setSanitizationRules( $rules );
	}
	
	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}


	public function getVariationId() {
		return $this->variationId;
	}

	public function setVariationId( $variationId ) {
		$this->variationId = $variationId;
	}


}


