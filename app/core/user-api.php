<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class UserApi {

	public static function getAddressesTypes() {
		return Domain\AddressType::getAddressesTypes();
	}

	private $manager = null;

	public function __construct() {

		$this->manager = new UserManager( );
	}

	public function getLoggedInUser() {

		if ( $this->manager->isUserLoggedIn() ) {
			return $this->manager->getLoggedUser();
		}

		return FALSE;
	}

}
