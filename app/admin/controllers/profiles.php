<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Profiles extends \Maven\Admin\Controllers\MavenAdminController implements \Maven\Core\Interfaces\iView {

	public function __construct () {
		parent::__construct();
	}

	public function registerRoutes ( $routes ) {

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
		$routes[ '/maven/profileentries/(?P<id>\D+)' ] = array(
			array( array( $this, 'getProfileEntries' ), \WP_JSON_Server::READABLE ),
		);
		$routes[ '/maven/profiletowpuser/(?P<id>\d+)' ] = array(
			array( array( $this, 'linkProfiletoWp' ), \WP_JSON_Server::EDITABLE | \WP_JSON_Server::ACCEPT_JSON ),
		);

		$routes[ '/maven/profile/(?P<id>\d+)/mandrill' ] = array(
			array( array( $this, 'getMandrillInfo' ), \WP_JSON_Server::READABLE ),
		);

		return $routes;
	}

	public function getProfiles ( $filter = array(), $page = 1 ) {
		$manager = new \Maven\Core\ProfileManager();
		$filter = new \Maven\Core\Domain\ProfileFilter();

		if ( key_exists( 'email', $_GET ) && $_GET[ 'email' ] ) {
			$filter->setEmail( $_GET[ 'email' ] );
		}

		if ( key_exists( 'firstName', $_GET ) && $_GET[ 'firstName' ] ) {
			$filter->setFirstName( $_GET[ 'firstName' ] );
		}

		if ( key_exists( 'lastName', $_GET ) && $_GET[ 'lastName' ] ) {
			$filter->setLastName( $_GET[ 'lastName' ] );
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

	public function getProfileOrders ( $id ) {
		$manager = new \Maven\Core\OrderManager();
		$orders = $manager->getProfileOrders( $id );
		$this->getOutput()->sendApiResponse( $orders );
	}

	public function getProfileEntries ( $id ) {
		$formEntries = array();

		if ( !\Maven\Core\GravityFormManager::isGFMissing() ) {
			$gfManager = new \Maven\Core\GravityFormManager();
			$formEntries = $gfManager->getEntries( $id );
		}

		$this->getOutput()->sendApiResponse( $formEntries );
	}

	public function getMandrillInfo ( $id ) {

		$profileManager = new \Maven\Core\ProfileManager();
		$profile = $profileManager->get( $id );

		if ( $profile->isEmpty() ) {
			$this->getOutput()->sendApiError( null, 'Profile Not found' );
		}
		
		$mandrillManager = new \Maven\Core\MandrillManager();
		$messages = $mandrillManager->getMessages( $profile->getEmail() );
		
		$this->getOutput()->sendApiSuccess( $messages, 'Mandrill Messages' );
	}

	public function newProfile ( $data ) {
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

		$this->getOutput()->sendApiSuccess( $profile, 'Profile Saved' );
	}

	public function isWpUser ( $id ) {
		$manager = new \Maven\Core\ProfileManager();
		$registrationManager = new \Maven\Core\RegistrationManager();
		$userExists = $registrationManager->getByEmail( $id );
		if ( $userExists !== FALSE ) {
			$userExists = TRUE;
		}
		$isWpUser = $manager->isWPUser( $id );
		$data = array( 'isWpUser' => $isWpUser, 'userExists' => $userExists );
		$this->getOutput()->sendApiResponse( $data );
	}

	public function linkProfiletoWp ( $id ) {
		try {
			$manager = new \Maven\Core\ProfileManager();
			$registrationManager = new \Maven\Core\RegistrationManager();
			$profile = $manager->get( $id );
			$registerWp = FALSE;
			$password = FALSE;
			$username = $profile->getEmail();
			$isWpUser = $manager->isWPUser( $username );
			if ( !$isWpUser ) {
				$registerWp = TRUE;
				$userExists = $registrationManager->getByEmail( $username );
				if ( $userExists === FALSE ) {
					$password = $manager->generateWpPassword();
				}
			} else {
				$profile->setUserId( 0 );
			}
			$profile = $manager->addProfile( $profile, $registerWp, $username, $password );
			if ( $password !== FALSE ) {
				$this->getOutput()->sendApiSuccess( $password, 'Profile Linked Sucessfully' );
			} else if ( !$password && $registerWp ) {
				$this->getOutput()->sendApiSuccess( '', 'Profile Linked Sucessfully' );
			} else {
				$this->getOutput()->sendApiSuccess( 'removed', 'Profile Linked Sucessfully' );
			}
		} catch ( \Maven\Exceptions\NotFoundException $e ) {
			//Specific exception
			$this->getOutput()->sendApiError( $id, "Profile Not found" );
		} catch ( \Exception $e ) {
			//General exception, send general error
			$this->getOutput()->sendApiError( $id, "An error has ocurred" );
		}
	}

	public function getProfile ( $id ) {
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

	public function editProfile ( $id, $data ) {
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
			$this->getOutput()->sendApiSuccess( $profile, 'Profile Saved' );
		} catch ( \Exception $e ) {
			//General exception, send general error
			$this->getOutput()->sendApiError( $data, $e->getMessage() );
		}
	}

	public function deleteProfile ( $id ) {
		$manager = new \Maven\Core\ProfileManager();

		$manager->delete( $id );

		$this->getOutput()->sendApiSuccess( new \stdClass(), 'Profile Deleted' );
	}

	public function getProfileAddress ( $id ) {
		$manager = new \Maven\Core\AddressManager();
		$address = $manager->get( $id );
		$this->getOutput()->sendApiResponse( $address );
	}

	public function deleteProfileAddress ( $id ) {
		$manager = new \Maven\Core\AddressManager();

		$manager->delete( $id );

		$this->getOutput()->sendApiSuccess( new \stdClass(), 'Address Deleted' );
	}

	public function getView ( $view ) {
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
