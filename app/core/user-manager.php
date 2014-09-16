<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class UserManager extends \Maven\Core\Manager {

	public function __construct () {
		parent::__construct();
	}

	public static function init () {

		HookManager::instance()->addFilter( 'retrieve_password_message', array( __CLASS__, 'resetPassword' ), 10, 2 );
	}

	public static function resetPassword ( $content, $key ) {
		$mavenSettings = \Maven\Settings\MavenRegistry::instance();

		$website = urlencode( site_url() );

		$request = new Request();
		$userLogin = $request->getProperty( 'user_login' );

		$user = self::getUserByLoginOrEmail( $userLogin );

		$email = $user->getEmail();

		$autoLoginLink = "";

		// Check if it has a profile on it.
		if ( !$user->getProfile()->isEmpty() ) {
			$profileManager = new ProfileManager();
			$autoLoginLink = site_url( $mavenSettings->getAutoLoginUrl() . "?email={$email}&key=" . $profileManager->generateAutoLoginKey( $user->getEmail() ) );
		}

		$organizationSignature = $mavenSettings->getSignature();
		$resetLink = site_url( "wp-login.php?redirect_to={$website}&action=rp&key={$key}&login=" . urlencode( $userLogin ) );

		$output = new Ui\Output( "", array(
			'organizationSignature' => $organizationSignature,
			'resetLink' => $resetLink,
			'userLogin' => $userLogin,
			'autoLoginLink' => $autoLoginLink )
		);
		$message = $output->getTemplate( 'email-reset-password.html' );

//		$message = \Maven\Core\MailFormatter::prepareContentEmail( $message );

		return $message;
	}

	/**
	 * Check if a user is logged in or not
	 * @return boolean
	 */
	public static function isUserLoggedIn () {
		return is_user_logged_in();
	}

	/**
	 * Return a logged user information
	 * @return \Maven\Core\User
	 */
	public static function getLoggedUser () {

		\Maven\Loggers\Logger::log()->message( 'UserManager: getLoggedUser' );

		if ( !self::isUserLoggedIn() ) {
			return new \Maven\Core\Domain\User();
		}

		$wpUser = wp_get_current_user();

		$user = self::loadUser( $wpUser );

		return $user;
	}

	public static function getUserByLoginOrEmail ( $value ) {

		if ( strpos( $value, '@' ) ) {
			$wpUser = get_user_by( 'email', $value );
		} else {
			$wpUser = get_user_by( 'login', $value );
		}

		if ( !$wpUser ) {
			throw new \Maven\Exceptions\NotFoundException( 'Invalid user' . $value );
		}

		return self::loadUser( $wpUser );
	}

	private static function loadUser ( $wpUser ) {

		$user = new \Maven\Core\Domain\User();

		$user->setFirstName( $wpUser->first_name );
		$user->setLastName( $wpUser->last_name );
		$user->setEmail( $wpUser->user_email );
		$user->setId( $wpUser->ID );

		// Check if it has a profile on it.
		$profileManager = new ProfileManager();
		$profile = $profileManager->getByEmail( $user->getEmail() );

		if ( $profile->getProfileId() ) {
			$user->setProfile( $profile );
		}

		\Maven\Loggers\Logger::log()->message( 'Maven/UserManager/loadUser: Profile User Id: ' . $profile->getUserId() );

		return $user;
	}

	/**
	 * 
	 * @param string $login
	 * @return \Maven\Core\Domain\User
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public static function getUserByLogin ( $login ) {

		if ( !$login ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Login is required' );
		}

		$wpUser = get_user_by( 'login', $login );

		if ( is_wp_error( $wpUser ) ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Invalid user' . $wpUser->get_error_message() );
		}

		$user = self::loadUser( $wpUser );



		return $user;
	}

	public static function autoLogin ( $userEmail, $key ) {

		// Check if it has a profile on it.
		$profileManager = new ProfileManager();

		if ( !$profileManager->exists( $userEmail ) ) {
			return false;
		}

		$result = $profileManager->validateAutoLoginKey( $userEmail, $key );

		if ( $result ) {

			$user = get_user_by( 'email', $userEmail );

			if ( $user ) {

				// Clean the token
				$profileManager->resetAutoLoginKey( $userEmail );

				wp_set_current_user( $user->ID, $user->user_login );
				wp_set_auth_cookie( $user->ID );
				do_action( 'wp_login', $user->user_login, $user );



				return true;
			}
		}

		return false;
	}

}
