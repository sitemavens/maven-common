<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class RegistrationApi {

	private static $currentTransactionResult = false;
	private $registry;

	public function __construct( \Maven\Settings\Registry $registry ) {

		$this->registry = $registry;
	}

	public static function getCurrentTransactionResult() {

		return self::$currentTransactionResult;
	}

	/**
	 * Create a profile
	 * @return Domain\Profile()
	 */
	public static function createProfile() {

		$profile = new Domain\Profile();
		$profile->setPrimaryAddress( new Domain\Address() );
		$profile->getPrimaryAddress()->setType( Domain\AddressType::Billing );

		return $profile;
	}

	/**
	 * Create a profile and register user
	 * @param Domain\Profile $profile
	 * @return \Maven\Core\Message\Message
	 */
	public static function register( Domain\Profile $profile, $password = false ) {

		$registrationManager = new RegistrationManager();

		$result = $registrationManager->register( $profile, $password );

		self::$currentTransactionResult = $result;

		return self::$currentTransactionResult;
	}

	/**
	 * 
	 * @param int $profileId
	 * @return Domain\Profile()
	 */
	public static function getProfile( $profileId ) {
		$profileManager = new ProfileManager();
		return $profileManager->get( $profileId );
	}

	public static function getRegistrationThankYouPage() {
		$mavenRegistry = \Maven\Settings\MavenRegistry::instance();
		if ( $mavenRegistry->getRegistrationThankYouPage() ) {
			return get_permalink( $mavenRegistry->getRegistrationThankYouPage() );
		} else {
			//Return to homepage? is this working?
			return '/';
		}
	}

}

?>
