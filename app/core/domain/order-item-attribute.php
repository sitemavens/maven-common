<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class OrderItemAttribute extends \Maven\Core\DomainObject {

	private $name;
	private $price;

	public function __construct () {

		parent::__construct( false );

		$rules = array(
			'name' => \Maven\Core\SanitizationRule::Text,
			'price' => \Maven\Core\SanitizationRule::Float
		);

		$this->setSanitizationRules( $rules );
	}

	public function getPrice () {
		return $this->price;
	}

	public function setPrice ( $price ) {
		$this->price = $price;
	}

	public function getName () {
		return $this->name;
	}

	public function setName ( $name ) {
		$this->name = $name;
	}

}
