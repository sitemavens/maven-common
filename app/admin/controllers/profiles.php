<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Profiles extends \Maven\Admin\Controllers\MavenAdminController implements \Maven\Core\Interfaces\iView{

	public function __construct() {
		parent::__construct();
	}

	public function registerRoutes( $routes ) {

		$routes[ '/maven/profiles' ] = array(
		    array( array( $this, 'getProfiles' ), \WP_JSON_Server::READABLE ),
		    array( array( $this, 'newProfile' ), \WP_JSON_Server::CREATABLE | \WP_JSON_Server::ACCEPT_JSON ),
		);
		$routes[ '/maven/profiles/(?P<id>\d+)' ] = array(
		    array( array( $this, 'getProfile' ), \WP_JSON_Server::READABLE ),
		    array( array( $this, 'editProfile' ), \WP_JSON_Server::EDITABLE | \WP_JSON_Server::ACCEPT_JSON ),
		    array( array( $this, 'deleteProfile' ), \WP_JSON_Server::DELETABLE ),
		);

		return $routes;
	}

	public function getProfiles() {
		$manager = new \Maven\Core\ProfileManager();
		$profile = $manager->getAll();

		$this->getOutput()->sendApiResponse( $profile );
	}

	public function newProfile( $data ) {
		$manager = new \Maven\Core\ProfileManager();

		$profile = new \Maven\Core\Domain\Profile();

		$profile->load( $data );
		$profile = $manager->updateProfile( $profile );

		$this->getOutput()->sendApiResponse( $profile );
	}

	public function getProfile( $id ) {
		$manager = new \Maven\Core\ProfileManager();
		$profile = $manager->get( $id );

		$this->getOutput()->sendApiResponse( $profile );
	}

	public function editProfile( $id, $data ) {

		$manager = new \Maven\Core\ProfileManager();

		$profile = new \Maven\Core\Domain\Profile();

		$profile->load( $data );

		$profile = $manager->addAttribute( $profile );

		$this->getOutput()->sendApiResponse( $profile );
	}

	public function deleteProfile( $id ) {
		$manager = new \Maven\Core\ProfileManager();

		$manager->delete( $id );

		$this->getOutput()->sendApiResponse( new \stdClass() );
	}

	public function getView( $view ) {
		switch ( $view ) {
			case "profiles-edit":
				$addresses = \Maven\Core\Domain\AddressType::getAddressesTypesCollection();
				$this->addJSONData( "cachedAddresses", $addresses );
				return $this->getOutput()->getAdminView( "profiles/{$view}" );
		}
		return $view;
	}

}
