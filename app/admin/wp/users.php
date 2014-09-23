<?php

namespace Maven\Admin\Wp;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Users extends \Maven\Core\Domain\WpBase {

	const LogoOn = "logo-on.png";
	const LogoOff = "logo-off.png";

	public function __construct () {

		parent::__construct();
	}

	public function init () {

		$this->getHookManager()->addFilter( 'manage_users_columns', array( $this, 'addIsMavenColumn' ) );
		$this->getHookManager()->addFilter( 'manage_users_custom_column', array( $this, 'addIsMavenColumnContent' ), 10, 3 );
		$this->getHookManager()->addAdminEnqueueScripts( array( $this, 'enqueueScript' ) );
		$this->getHookManager()->addAjaxAction( 'convertUser', array( $this, 'convertUser' ) );
	}

	public function convertUser () {

		$userEmail = $this->getRequest()->getProperty( 'email' );
		$nonce = $this->getRequest()->getProperty( 'nonce' );
		if ( !wp_verify_nonce( $nonce, 'convertUser' ) ) {
			$result = array( 'success' => false, 'data' => 'Invalid Nonce' );

			$result = json_encode( $result );
			die( $result );
		}

		$profileManager = new \Maven\Core\ProfileManager();
		$result = $profileManager->convertWpUserToMaven( $userEmail );

		$result = array( 'success' => true, 'data' => $this->getLogoOnUrl() );

		$result = json_encode( $result );

		die( $result );
	}

	function enqueueScript ( $hook ) {
		$registry = \Maven\Settings\MavenRegistry::instance();
		if ( 'users.php' !== $hook )
			return;
		if ( $registry->isDevEnv() ) {
			wp_enqueue_script( 'wp-users-script', $this->getRegistry()->getAdminWpScriptsUrl() . 'users.js', array( 'maven' ) );
			wp_localize_script( 'wp-users-script', 'Users', array( 'action' => 'convertUser', 'nonce' => wp_create_nonce( 'wp_json' ) ) );
		} else {
			wp_enqueue_script( 'mainApp', $registry->getScriptsUrl() . "main.min.js", 'angular', $registry->getPluginVersion() );
			wp_localize_script( 'mainApp', 'Users', array( 'action' => 'convertUser', 'nonce' => wp_create_nonce( 'wp_json' ) ) );
		}
	}

	public function addIsMavenColumn ( $columns ) {
		$columns['isMaven'] = 'Is Maven';
		return $columns;
	}

	function addIsMavenColumnContent ( $value, $column_name, $user_id ) {

		if ( 'isMaven' !== $column_name ) {
			return $value;
		}

		$user = get_userdata( $user_id );

		$profileManager = new \Maven\Core\ProfileManager();

		if ( $profileManager->isWPUser( $user->user_email ) ) {
			$image = $this->getLogoOnUrl();
			return "<img src={$image} alt='' />";
		}

		$image = $this->getLogoOffUrl();
		$image = "<a class='maven-user' href='#{$user->user_email}'><img src={$image} alt='' /></a>";

		return $image;
	}

	private function getLogoOnUrl () {
		return $this->getRegistry()->getImagesUrl() . self::LogoOn;
	}

	private function getLogoOffUrl () {
		return $this->getRegistry()->getImagesUrl() . self::LogoOff;
	}

}
