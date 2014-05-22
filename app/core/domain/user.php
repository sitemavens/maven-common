<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;


class User extends \Maven\Core\DomainObject{
	
	/**
	 *
	 * @var \Maven\Core\Domain\Profile 
	 */
	private $profile;
	private $firstName;
	private $lastName;
	private $email; 
	
	public function __construct ( $id = false ) {
		
		parent::__construct( $id );
		
		$this->profile = new Profile();
	}
	
	public function getProfile () {
		return $this->profile;
	}

	public function setProfile ( \Maven\Core\Domain\Profile $profile ) {
		$this->profile = $profile;
	}

	public function getFirstName () {
		return $this->firstName;
	}

	public function getLastName () {
		return $this->lastName;
	}

	public function getEmail () {
		return $this->email;
	}

	public function setFirstName ( $firstName ) {
		$this->firstName = $firstName;
	}

	public function setLastName ( $lastName ) {
		$this->lastName = $lastName;
	}

	public function setEmail ( $email ) {
		$this->email = $email;
	}


	public function hasProfile(){
		$value = $this->profile && ! $this->profile->isEmpty();
		
		return $value;
	}

	
}