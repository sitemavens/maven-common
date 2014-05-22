<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Profiles extends \Maven\Admin\Controllers\MavenAdminController {

	public function __construct() {
		parent::__construct();
	}

	public function admin_init() {
		
	}

	public function showForm() {

		$this->addJSONData( 'addressesTypes', \Maven\Core\Domain\AddressType::getAddressesTypes() );

		$this->addJSONData( 'defaultAddressType', \Maven\Core\Domain\AddressType::Billing );

		$countriesManager = new \Maven\Core\CountryManager();
		$countries = $countriesManager->getAll();

		//Remove worldwide option
		unset( $countries[ '*' ] );

		$col = array();
		foreach ( $countries as $code => $data ) {
			$col[ $code ] = array( 'name' => $data[ 'name' ], 'value' => $code );
		}

		$this->addJSONData( 'cachedCountries', $col );

		$this->addJSONData( 'defaultRole', get_option( 'default_role' ) );

		$roleManager = new \Maven\Security\RoleManager();
		$roles = $roleManager->getRolesWithoutCapabilities();

		$this->addJSONData( 'roles', $roles );

		$this->getOutput()->setTitle( $this->__( "Profiles" ) );

		$this->getOutput()->loadAdminView( "profiles" );
	}

	public function cancel() {
		
	}

	public function save() {
		
	}

	public function showList() {
		
	}

	public function profileEntryPoint() {
		try {
			$event = $this->getRequest()->getProperty( 'event' );
			$manager = new \Maven\Core\ProfileManager();
			$addressManager = new \Maven\Core\AddressManager();
			$roleManager = new \Maven\Security\RoleManager();

			switch ( $event ) {
				case 'create':
				case 'update':
					$data = $this->getRequest()->getProperty( 'data' );

					$register = FALSE;
					if ( isset( $data[ 'registered' ] ) && $data[ 'registered' ] === 'true' )
						$register = TRUE;

					$username = FALSE;
					if ( isset( $data[ 'username' ] ) && $data[ 'username' ] )
						$username = $data[ 'username' ];

					$password = FALSE;
					if ( isset( $data[ 'password' ] ) && $data[ 'password' ] )
						$password = $data[ 'password' ];

					$confirm = FALSE;
					if ( isset( $data[ 'confirm' ] ) && $data[ 'confirm' ] )
						$confirm = $data[ 'confirm' ];

					if ( $password == $confirm ) {
						try {
							//save profile
							$instance = $manager->addProfile( $data, $register, $username, $password );
							$addresses = array();
							//save addresses, if addresses array come
							if ( isset( $data[ 'addresses' ] ) && $data[ 'addresses' ] ) {
								$addresses = $data[ 'addresses' ];
							}
							$addresses = $addressManager->updateAddresses( $addresses, $instance );
							$instance->setAddresses( $addresses );
							//save roles
							if ( $instance->getUserId() ) {
								$instance = $roleManager->saveUserRoles( $instance );
							}

							$this->getOutput()->sendData( $instance->toArray() );
						} catch ( \Exception $e ) {
							$this->getOutput()->sendError( $e->getMessage() );
						}
					} else {

						$this->getOutput()->sendError( 'Passwords dont match' );
					}
					break;

				case 'read':

					$modelId = $this->getRequest()->getProperty( 'id' );

					if ( $modelId ) {
						try {
							$intance = $manager->get( $modelId );
							$this->getOutput()->sendData( $intance->toArray() );
						} catch ( \Maven\Exceptions\MavenException $ex ) {
							$this->getOutput()->sendError( $ex->getMessage() );
						}
					} else {
						$data = $this->getRequest()->getProperty( 'data' );
						//$orderBy = $this->getRequest()->getProperty( 'orderby' );
						$top = $this->getRequest()->getProperty( 'top' );
						$skip = $this->getRequest()->getProperty( 'skip' );

						$filter = new \Maven\Core\Domain\ProfileFilter();

						if ( key_exists( 'email', $data ) && $data[ 'email' ] )
							$filter->setEmail( $data[ 'email' ] );

						if ( key_exists( 'firstName', $data ) && $data[ 'firstName' ] )
							$filter->setFirstName( $data[ 'firstName' ] );

						if ( key_exists( 'lastName', $data ) && $data[ 'lastName' ] )
							$filter->setLastName( $data[ 'lastName' ] );


						$page = $data[ 'page' ] - 1; //We use 0-based pages
						$perPage = $data[ 'per_page' ];
						$sortBy = '';
						if ( $data && key_exists( 'sort_by', $data ) )
							$sortBy = \Maven\Core\Utils::unCamelize( $data[ 'sort_by' ], '_' );

						$order = '';
						if ( $data && key_exists( 'order', $data ) )
							$order = $data[ 'order' ];
						//var_dump($data);
						$intances = $manager->getPage( $filter, $sortBy, $order, ($page * $perPage ), $perPage );
						$count = $manager->getCount( $filter );

						$response = array();
						foreach ( $intances as $row ) {
							$response[] = $row->toArray();
						}

						$out[] = array( 'total_entries' => intval( $count ) );
						$out[] = $response;

						$this->getOutput()->sendData( $out );
					}
					break;

				/* case 'update':

				  $data = $this->getRequest()->getProperty( 'data' );

				  $register = FALSE;
				  if ( isset( $data[ 'registered' ] ) && ($data[ 'registered' ] == 'true') ) {
				  $register = TRUE;
				  }

				  $username = FALSE;
				  if ( isset( $data[ 'username' ] ) && $data[ 'username' ] ) {
				  $username = $data[ 'username' ];
				  }

				  $password = FALSE;
				  if ( isset( $data[ 'password' ] ) && $data[ 'password' ] )
				  $password = $data[ 'password' ];

				  $confirm = FALSE;
				  if ( isset( $data[ 'confirm' ] ) && $data[ 'confirm' ] ) {
				  $confirm = $data[ 'confirm' ];
				  }

				  $useOneAddress = FALSE;
				  if ( isset( $data[ 'useOneAddress' ] ) && ($data[ 'useOneAddress' ] == 'true') ) {
				  $useOneAddress = TRUE;
				  }

				  if ( $password == $confirm ) {
				  try {
				  $instance = $manager->addProfile( $data, $register, $username, $password, $useOneAddress );

				  $this->getOutput()->sendData( $instance->toArray() );
				  } catch ( \Exception $e ) {
				  $this->getOutput()->sendError( $e->getMessage() );
				  }
				  } else {

				  $this->getOutput()->sendError( 'Passwords dont match' );
				  }
				  break; */

				case 'delete':
					$modelId = $this->getRequest()->getProperty( 'id' );

					$manager->delete( $modelId );

					//Empty response
					$this->getOutput()->sendData( 'deleted' );

					break;
			}
		} catch ( Exception $ex ) {
			$this->getOutput()->sendError( $ex->getMessage() );
		}
	}

}
