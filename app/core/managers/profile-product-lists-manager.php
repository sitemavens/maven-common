<?php

namespace Maven\Core\Managers;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class ProfileProductListsManager {

	public function __construct () {
		;
	}

	public function getProfileLists ( $profileId ) {
		
	}

	public function addThingToList () {
		
	}

	public function getWishListItems ( $profileId ) {

		return $this->getListItems( $profileId, 'wishlist' );
		
	}

	public function getListItems ( $profileId, $type ) {
		
		
		
	}

}
