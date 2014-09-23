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

		$attrs = shortcode_atts( array( 'prop' => 'firstName', 'emptyPropMessage' => ''  ), $attrs );
		$loggedProfiled = $this->getLoggedProfile();

        $prop = '';
		switch ( strtolower( $attrs[ 'prop' ] ) ) {
			case "firstname";
				$prop =  $loggedProfiled->getFirstName();
                break;
			case "lastname";
				$prop = $loggedProfiled->getLastName();
                break;
			case "fullname";
				$prop = $loggedProfiled->getFullName();
                break;
		}
        
        if( empty( $prop ) && isset( $attrs[strtolower( 'emptyPropMessage' )] ) ){
            return $attrs[strtolower( 'emptyPropMessage' )];
        }

		return $prop;
	}

	private function getLoggedProfile () {
		$loggedUser = \Maven\Core\UserManager::getLoggedUser();

		return $loggedUser->getProfile();
	}

}
