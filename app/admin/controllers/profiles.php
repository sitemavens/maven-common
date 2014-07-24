<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
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
		$routes[ '/maven/profilewpuser/(?P<id>\D+)' ] = array(
			array( array( $this, 'isWpUser' ), \WP_JSON_Server::READABLE ),
		);
		$routes[ '/maven/profileorders/(?P<id>\d+)' ] = array(
			array( array( $this, 'getProfileOrders' ), \WP_JSON_Server::READABLE ),
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

	public function getProfileOrders( $id ) {
		$manager = new \Maven\Core\OrderManager();
		$orders = $manager->getProfileOrders( $id );

		$this->getOutput()->sendApiResponse( $orders );
	}

	public function newProfile( $data ) {
		$manager = new \Maven\Core\ProfileManager();
		$profile = new \Maven\Core\Domain\Profile();
		$profile->load( $data );
		$registerWp = FALSE;
		if ( isset( $data[ 'register' ] ) && $data[ 'register' ] == 1 )
			$registerWp = true;

		$username = FALSE;
		if ( isset( $data[ 'userName' ] ) && $data[ 'userName' ] )
			$username = $data[ 'userName' ];

		$password = FALSE;
		if ( isset( $data[ 'password' ] ) && $data[ 'password' ] )
			$password = $data[ 'password' ];
		$profile = $manager->addProfile( $profile, $registerWp, $username, $password );

		$this->getOutput()->sendApiResponse( $profile );
	}

	public function isWpUser( $id ) {
		$manager = new \Maven\Core\ProfileManager();
		$result = $manager->isWPUser( $id );
		$data = array( 'result' => $result );
		$this->getOutput()->sendApiResponse( $data );
	}

	public function getProfile( $id ) {
		try {
			$manager = new \Maven\Core\ProfileManager();
			$profile = $manager->get( $id );
			$profile->setId( $profile->getProfileId() );
			$this->getOutput()->sendApiResponse( $profile );
		} catch ( \Maven\Exceptions\NotFoundException $e ) {
			//Specific exception
			$this->getOutput()->sendApiError( $id, "Profile Not found" );
		} catch ( \Exception $e ) {
			//General exception, send general error
			$this->getOutput()->sendApiError( $id, "An error has ocurred" );
		}
	}

	public function editProfile( $id, $data ) {
		try {
			$manager = new \Maven\Core\ProfileManager();
			$profile = new \Maven\Core\Domain\Profile();
			$profile->load( $data );
			$registerWp = FALSE;
			if ( isset( $data[ 'register' ] ) && $data[ 'register' ] == 1 )
				$registerWp = true;

			$username = FALSE;
			if ( isset( $data[ 'userName' ] ) && $data[ 'userName' ] )
				$username = $data[ 'userName' ];

			$password = FALSE;
			if ( isset( $data[ 'password' ] ) && $data[ 'password' ] )
				$password = $data[ 'password' ];

			$profile = $manager->addProfile( $profile, $registerWp, $username, $password );
			$this->getOutput()->sendApiSuccess( $profile, 'Provile saved' );
		} catch ( \Exception $e ) {
			//General exception, send general error
			$this->getOutput()->sendApiError( $data, $e->getMessage() );
		}
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
				$roleManager = new \Maven\Security\RoleManager();
				$countries = \Maven\Core\CountriesApi::getAllCountries();
				$addresses = \Maven\Core\Domain\AddressType::getAddressesTypesCollection();
				$defaultRole = $roleManager->get( (get_option( 'default_role' ) ) );
				$this->addJSONData( "cachedDefaultRole", $defaultRole );
				$this->addJSONData( "cachedAddresses", $addresses );
				$this->addJSONData( "cachedCountries", $countries );
				return $this->getOutput()->getAdminView( "profiles/{$view}" );
		}
		return $view;
	}

}
