<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Profiles extends \Maven\Admin\Controllers\MavenAdminController implements \Maven\Core\Interfaces\iView {

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
		$routes[ '/maven/profileaddress/(?P<id>\d+)' ] = array(
		    array( array( $this, 'getProfileAddress' ), \WP_JSON_Server::READABLE ),
		    array( array( $this, 'deleteProfileAddress' ), \WP_JSON_Server::DELETABLE ),
		);

		return $routes;
	}

	public function getProfiles( $filter = array(), $page = 1 ) {
		$manager = new \Maven\Core\ProfileManager();
		$filter = new \Maven\Core\Domain\ProfileFilter();

		if ( key_exists( 'email', $filter ) && $filter[ 'email' ] ) {
			$filter->setEmail( $filter[ 'email' ] );
		}

		if ( key_exists( 'firstName', $filter ) && $filter[ 'firstName' ] ) {
			$filter->setFirstName( $filter[ 'firstName' ] );
		}

		if ( key_exists( 'lastName', $filter ) && $filter[ 'lastName' ] ) {
			$filter->setLastName( $filter[ 'lastName' ] );
		}

		$sortBy = 'email';
		$order = 'desc';
		$perPage = 10;

		$profile = $manager->getPage( $filter, $sortBy, $order, (($page - 1) * $perPage ), $perPage );
		$count = $manager->getCount( $filter );

		$response = array(
		    "items" => $profile,
		    "totalItems" => $count
		);

		$this->getOutput()->sendApiResponse( $response );
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
		$profile->setId( $profile->getProfileId() );
		$this->getOutput()->sendApiResponse( $profile );
	}

	public function editProfile( $id, $data ) {

		$manager = new \Maven\Core\ProfileManager();

		$profile = new \Maven\Core\Domain\Profile();

		$profile->load( $data );
		$profile = $manager->updateProfile( $profile );

		$this->getOutput()->sendApiResponse( $profile );
	}

	public function deleteProfile( $id ) {
		$manager = new \Maven\Core\ProfileManager();

		$manager->delete( $id );

		$this->getOutput()->sendApiResponse( new \stdClass() );
	}

	public function getProfileAddress( $id ) {
		$manager = new \Maven\Core\AddressManager();
		$address = $manager->get( $id );
		$this->getOutput()->sendApiResponse( $address );
	}

	public function deleteProfileAddress( $id ) {
		$manager = new \Maven\Core\AddressManager();

		$manager->delete( $id );

		$this->getOutput()->sendApiResponse( new \stdClass() );
	}

	public function getView( $view ) {
		switch ( $view ) {
			case "profiles-edit":
				$countries = \Maven\Core\CountriesApi::getAllCountries();
				$addresses = \Maven\Core\Domain\AddressType::getAddressesTypesCollection();
				$this->addJSONData( "cachedAddresses", $addresses );
				$this->addJSONData( "cachedCountries", $countries );
				return $this->getOutput()->getAdminView( "profiles/{$view}" );
		}
		return $view;
	}

}
