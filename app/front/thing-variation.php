<?php
namespace Maven\Front;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class ThingVariation extends \Maven\Core\DomainObject {
	
	private $quantity;
	private $price;
	private $name;
	private $optionId;
	
	
	/**
	 * 
	 * @param int $id
	 */
	public function __construct( $id = false ) {
		parent::__construct( $id );
	}
	
	public function getQuantity() {
		return $this->quantity;
	}

	public function setQuantity( $quantity ) {
		$this->quantity = $quantity;
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


	public function getOptionId () {
		return $this->optionId;
	}

	public function setOptionId ( $optionId ) {
		$this->optionId = $optionId;
	}



}
