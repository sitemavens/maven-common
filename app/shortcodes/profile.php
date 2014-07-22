<?php

namespace Maven\Shortcodes;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Profile extends Base {

	public function __construct () {
		parent::__construct();
	}
	
	public function addShortCodes () {

		$this->addShortcode( 'mvn-profile', array( $this, 'getProfileData' ) );
	}

	public function getProfileData ( $attrs ) {

		$attrs = shortcode_atts( array( 'prop' => 'firstName' ), $attrs );
		$loggedProfiled = $this->getLoggedProfile();

		switch ( strtolower( $attrs[ 'prop' ] ) ) {
			case "firstname";
				return $loggedProfiled->getFirstName();
			case "lastname";
				return $loggedProfiled->getLastName();
			case "fullname";
				return $loggedProfiled->getFullName();
		}

		return;
	}

	private function getLoggedProfile () {
		$loggedUser = \Maven\Core\UserManager::getLoggedUser();

		return $loggedUser->getProfile();
	}

}
