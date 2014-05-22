<?php
namespace Maven\Front;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class ThingAttribute extends \Maven\Core\DomainObject {
	
	private $price;
	private $name;
	
	
	/**
	 * 
	 * @param int $id
	 */
	public function __construct( $id = false ) {
		parent::__construct( $id );
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
