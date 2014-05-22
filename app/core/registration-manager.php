<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class RegistrationManager {

	public function __construct() {
		;
	}

	private function get( $value, $field = 'id' ) {

		if ( ! $value )
			throw new \Maven\Exceptions\MissingParameterException( 'Search Value is required' );

		return get_user_by( $field, $value );
	}

	/**
	 * Create a profile and register user
	 * @param Domain\Profile $profile
	 * @return \Maven\Core\Message\Message
	 */
	public function register( Domain\Profile $profile, $password = false ) {
		$profileManager = new ProfileManager();

		$wp_user_id = $this->addWordpressUser( $profile, $profile->getEmail(), $password );

		if ( is_wp_error( $wp_user_id ) ) {
			//What to do here
			return Message\MessageManager::createErrorMessage( $wp_user_id->get_error_message() );
		} else {
			$profile->setUserId( $wp_user_id );
		}
		$profile = $profileManager->addProfile( $profile );

		//send notification message
		$this->sendEmail( $profile );

		return Message\MessageManager::createRegularMessage( 'New user registered', $profile );
	}

	public function getById( $id ) {
		return $this->get( $id );
	}

	public function getByNicename( $nicename ) {
		return $this->get( $nicename, 'slug' );
	}

	public function getByEmail( $email ) {
		return $this->get( $email, 'email' );
	}

	public function getByLogin( $login ) {
		return $this->get( $login, 'login' );
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Profile $profile
	 * @param string $password
	 * @return int|WP_Error
	 */
	public function addWordpressUser( $profile, $username = false, $password = false ) {

		if ( ! $profile->getUserId() && ! $password )
			throw new \Maven\Exceptions\MissingParameterException( 'Password is required for new Users' );

		$user = array( );
		if ( $profile->getUserId() )
			$user[ 'ID' ] = $profile->getUserId();

		if ( $password )
			$user[ 'user_pass' ] = $password;

		if ( $username ) {
			$user[ 'user_login' ] = $username;
		} else {
			$user[ 'user_login' ] = $profile->getEmail();
		}
		$user[ 'user_url' ] = $profile->getWebsite();
		$user[ 'user_email' ] = $profile->getEmail();
		$user[ 'display_name' ] = $profile->getFirstName() . ' ' . $profile->getLastName();
		$user[ 'first_name' ] = $profile->getFirstName();
		$user[ 'last_name' ] = $profile->getLastName();

		if ( $profile->getUserId() ) {
			return wp_update_user( $user );
		} else {
			return wp_insert_user( $user );
		}
	}
	
	

	public function delete( $id ) {

		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'Id is required' );

		return wp_delete_user( $id );
	}

	/**
	 * 
	 * TODO// Todo esto hay que moverlo a una clase que se encargue del parseo
	 */
	public function sendEmail( Domain\Profile $profile ) {

		$mavenSettings = \Maven\Settings\MavenRegistry::instance();

		//Process the form
		$url = $mavenSettings->getPluginUrl() . 'templates';
		ob_start();
		$siteName = $mavenSettings->getOrganizationName();
		$accountUrl = $mavenSettings->getWebSiteUrl() . '/wp-admin';
		$emailContact = $mavenSettings->getContactEmail();
		$signature = $mavenSettings->getSignature();
		//extract($order);
		require(dirname( __FILE__ ) . '/../templates/email-new-user.html');
		$message = ob_get_clean();

		$mail = \Maven\Mail\MailFactory::build();
		$mail->bcc( $mavenSettings->getBccNotificationsTo() ) //We should send this? because the copy for the admin is sended to the same address
			->to( $profile->getEmail() )
			->message( $message )
			->subject( "Thank you for register" )
			->fromAccount( $mavenSettings->getSenderEmail() )
			->fromMessage( $mavenSettings->getSenderName() )
			->send();

		//Notify admins
		$this->sendNotificationEmail( $profile, 'New user registered' );
	}

	private function sendNotificationEmail( Domain\Profile $profile, $subject ) {

		$mavenSettings = \Maven\Settings\MavenRegistry::instance();

		//Process the form
		$url = $mavenSettings->getPluginUrl() . 'templates';
		ob_start();
		//extract($order);
		require(dirname( __FILE__ ) . '/../templates/email-admin-new-user.html');
		$message = ob_get_clean();

		$mail = \Maven\Mail\MailFactory::build();
		$mail->to( $mavenSettings->getBccNotificationsTo() )
			->message( $message )
			->subject( $mavenSettings->getLanguage()->__( $subject ) )
			->fromAccount( $mavenSettings->getSenderEmail() )
			->fromMessage( $mavenSettings->getSenderName() )
			->send();
	}

}

