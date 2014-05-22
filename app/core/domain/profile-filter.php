<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class ProfileFilter {

	private $email;
	private $firstName;
	private $lastName;
	
	private function protectField( $field ) {

		if ( ! ( $field instanceof \Maven\Core\MavenDateTime ) )
			return esc_sql( sanitize_text_field( $field ) );

		return $field;
	}

	public function __construct() {
		;
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

