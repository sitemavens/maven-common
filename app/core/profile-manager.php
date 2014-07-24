<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class ProfileManager {

	const AutoLoginKeyAction = 'auto-login-key';

	protected $profileTableName = 'mvn_profile';

	/**
	 *
	 * @var \Maven\Core\Mappers\ProfileMapper 
	 */
	private $mapper;

	public function __construct() {
		$this->mapper = new Mappers\ProfileMapper( $this->profileTableName );
	}

	/*
	 * return \Maven\Core\Domain\Profile
	 */

	public function get( $id ) {

		if ( !$id ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Profile Id is required' );
		}

		$profile = $this->mapper->get( $id );

		$profile = $this->addAddresses( $profile );

		$profile = $this->addRoles( $profile );

		return $profile;
	}

	/**
	 * 
	 * @param string $email
	 * @return \Maven\Core\Domain\Profile
	 */
	public function getProfileOnly( $email ) {

		$profile = $this->mapper->getProfileByEmail( $email );

		return $profile;
	}

	private function addAddresses( \Maven\Core\Domain\Profile $profile ) {

		if ( !$profile->getProfileId() )
			return $profile;

		$addressManager = new AddressManager();

		$profile->setAddresses( $addressManager->getAddresses( $profile->getProfileId() ) );

		return $profile;
	}

	private function addRoles( \Maven\Core\Domain\Profile $profile ) {

		if ( !$profile->getUserId() )
			return $profile;

		$roleManager = new \Maven\Security\RoleManager();

		$profile->setRoles( $roleManager->getUserRoles( $profile->getUserId() ) );

		return $profile;
	}

	/**
	 * 
	 * @param string $email
	 * @return \Maven\Core\Domain\Profile
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function getByEmail( $email ) {

		if ( !$email ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Profile email is required' );
		}

		$this->mapper = new Mappers\ProfileMapper( $this->profileTableName );

		$profile = $this->mapper->getProfileByEmail( $email, true );

		if ( $profile->getProfileId() ) {
			$profile = $this->addAddresses( $profile );
		}

		return $profile;
	}

	/**
	 * Check if the profile exists. If so, it returns the ID, if not false.
	 * @param string $email
	 * @return boolean \ int
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function exists( $email ) {

		if ( !$email ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Profile email is required' );
		}

		$this->mapper = new Mappers\ProfileMapper( $this->profileTableName );

		return $this->mapper->existsProfile( $email );
	}

	public function isWPUser( $email ) {

		if ( !$email ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Email is required' );
		}

		return $this->mapper->isWPUser( $email );
	}

	public function populateProfileByEmail( \Maven\Core\Domain\Profile $profile ) {

		if ( !$profile->getEmail() ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Email is required' );
		}

		$this->mapper = new Mappers\ProfileMapper( $this->profileTableName );

		$profile = $this->mapper->populateProfileByEmail( $profile );

		$profile = $this->addAddresses( $profile );

		return $profile;
	}

	/**
	 * Add or update a profile
	 * @param \Maven\Core\Domain\Profile or array $profile $profile
	 * @return \Maven\Core\Domain\Profile
	 */
	public function updateProfile( $profile ) {
		return $this->addProfile( $profile );
	}

	/**
	 * Add or update a profile
	 * @param \Maven\Core\Domain\Profile or array $profile
	 * @return \Maven\Core\Domain\Profile
	 */
	public function addProfile( $profile, $registerWp = null, $username = null, $password = null ) {
		$profileToUpdate = null;
		if ( is_array( $profile ) ) {
			$profileToUpdate = new Domain\Profile();
			\Maven\Core\FillerHelper::fillObject( $profileToUpdate, $profile );
		} else
			$profileToUpdate = $profile;

		$this->mapper = new Mappers\ProfileMapper( $this->profileTableName );
		$registrationManager = new RegistrationManager();
		$wpUser = $registrationManager->getByEmail( $username );
		$roleManager = new \Maven\Security\RoleManager();
		if ( $registerWp || !$wpUser === FALSE ) {
			if ( $wpUser === FALSE ) {
				$userId = $registrationManager->addWordpressUser( $profileToUpdate, $username, $password );
			} else {
				$userId = $wpUser->ID;
			}
			if ( !is_wp_error( $userId ) ) {
				$profileToUpdate->setUserId( $userId );
				$defaultRole[] = $roleManager->get( (get_option( 'default_role' ) ) );
				//Get roles of the user
//				$existingRoles = $roleManager->getUserRoles( $profileToUpdate->getUserId() );
//				$allRoles = array_unique( array_merge( $existingRoles, $profileToUpdate->getRoles() ), SORT_REGULAR );
				//combine user roles, with roles set on the profile
				if ( $registerWp ) {
					$allRoles = $profileToUpdate->getRoles();
				} else {
					$userCurrentRoles = $wpUser->roles;
					if ( count( $userCurrentRoles ) !== 0 )
						$allRoles[] = $roleManager->get( $userCurrentRoles[ 0 ] );
					else
						$allRoles = $profileToUpdate->getRoles();
				}
				//asign combined roles to the profile
				if ( count( ( array ) $allRoles ) !== 0 ) {
					$profileToUpdate->setRoles( $allRoles );
				} else {
					$profileToUpdate->setRoles( $defaultRole );
				}
				$profileToUpdate = $roleManager->saveUserRoles( $profileToUpdate );
			} else {
				//TODO: show error message
				throw new \Maven\Exceptions\MavenException( $userId->get_error_message() );
			}
		}
		//$forUpdate = ((int)$profile->getId()) > 0;
		$profileToUpdate = $this->mapper->save( $profileToUpdate );

//		$addressManager = new AddressManager();
//
//		$addresses = $profileToUpdate->getAddresses();
//		
//		foreach ( $addresses as $address ) {
//			$address->setProfileId( $profileToUpdate->getProfileId() );
//			$addressManager->save( &$address );
//		}

		return $profileToUpdate;
	}

	public function updateProfileAddresses( \Maven\Core\Domain\Profile $profile ) {
		$addressMapper = new Mappers\AddressMapper();

		$addresses = $profile->getAddresses();
		foreach ( $addresses as $address ) {
			$address->setProfileId( $profile->getProfileId() );
			$addressMapper->save( $address );
		}
	}

	/**
	 * Convert an existing WP User into maven
	 * @param string $email
	 * @return boolean
	 */
	public function convertWpUserToMaven( $email ) {

		//First, verify that the user isn't already a Maven user

		if ( $this->isWPUser( $email ) ) {
			return false;
		}


		$wpUser = get_user_by( 'email', $email );

		if ( !$wpUser ) {
			return false;
		}


		$profile = new Domain\Profile();
		$profile->setFirstName( $wpUser->first_name );
		$profile->setLastName( $wpUser->last_name );
		$profile->setFirstName( $wpUser->first_name );
		$profile->setEmail( $wpUser->user_email );
		$profile->setUserId( $wpUser->ID );

		$this->addProfile( $profile );

		return true;
	}

	public function addProfiles( $profiles ) {

		foreach ( $profiles as $profile ) {
			$this->addProfile( $profile );
		}

		return $profiles;
	}

	public function getAll() {

		$this->mapper = new Mappers\ProfileMapper( $this->profileTableName );

		return $this->mapper->getAll();
	}

	public function getPage( Domain\ProfileFilter $filter, $orderBy = 'email', $orderType = 'desc', $start = 0, $limit = 1000 ) {
		$this->mapper = new Mappers\ProfileMapper( $this->profileTableName );

		return $this->mapper->getPage( $filter, $orderBy, $orderType, $start, $limit );
	}

	public function getCount( Domain\ProfileFilter $filter ) {
		$this->mapper = new Mappers\ProfileMapper( $this->profileTableName );

		return $this->mapper->getCount( $filter );
	}

	public function delete( $id ) {

		if ( !$id ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Id is required' );
		}

		$this->mapper = new Mappers\ProfileMapper( $this->profileTableName );

		// We delete the addresses
		$addressManager = new AddressManager();
		$addressManager->deleteByProfile( $id );

		return $this->mapper->deleteProfile( $id );
	}

	/**
	 * Generate a key to use for autologin
	 * @param string $email
	 * @throws \Maven\Exceptions\NotFoundException
	 */
	public function generateAutoLoginKey( $email ) {

		$profile = $this->getByEmail( $email );

		if ( !$profile ) {
			throw new \Maven\Exceptions\NotFoundException( 'Profile not found: ' . $email );
		}

		// Generate the key
		$i = wp_nonce_tick();
		$key = substr( wp_hash( $i . 'auto-login-key' . $profile->getProfileId(), 'nonce' ), -12, 10 );

		$this->mapper->updateAutoLoginKey( $profile->getProfileId(), $key );

		return $key;
	}

	/**
	 * Clean the autologin key
	 * @param string $email
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function resetAutoLoginKey( $email ) {

		$profile = $this->getByEmail( $email );

		if ( !$profile ) {
			throw new \Maven\Exceptions\NotFoundException( 'Profile not found: ' . $email );
		}

		$this->mapper->resetAutoLoginKey( $profile->getProfileId() );
	}

	public function validateAutoLoginKey( $email, $key ) {

		$profile = $this->getByEmail( $email );

		if ( !$profile ) {
			throw new \Maven\Exceptions\NotFoundException( 'Profile not found: ' . $email );
		}

		$i = wp_nonce_tick();

		// Nonce generated 0-12 hours ago
		if ( substr( wp_hash( $i . self::AutoLoginKeyAction . $profile->getProfileId(), 'nonce' ), -12, 10 ) === $key )
			return 1;
		// Nonce generated 12-24 hours ago
		if ( substr( wp_hash( ($i - 1) . self::AutoLoginKeyAction . $profile->getProfileId(), 'nonce' ), -12, 10 ) === $key )
			return 2;

		// Invalid nonce
		return false;
	}

}
