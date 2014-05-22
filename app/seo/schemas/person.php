<?php

namespace Maven\Seo\Schemas;


// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class Person extends Thing{
	
	private $email;
	
	public function __construct() {
		;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail( $email ) {
		$this->email = $email;
	}

	
	
}