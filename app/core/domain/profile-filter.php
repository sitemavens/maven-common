<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class ProfileFilter extends BaseFilter{

	private $email;
	private $firstName;
	private $lastName;
	
	public function __construct() {
		parent::__construct();
	}

	public function getEmail() {
		return $this->email;
	}

	public function getFirstName() {
		return $this->firstName;
	}

	public function getLastName() {
		return $this->lastName;
	}

	public function setEmail( $email ) {
		$this->email = $email;
	}

	public function setFirstName( $firstName ) {
		$this->firstName = $firstName;
	}

	public function setLastName( $lastName ) {
		$this->lastName = $lastName;
	}



}

