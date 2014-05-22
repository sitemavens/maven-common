<?php

namespace Maven\Security;

class Blocker {

	private static $instance;

	public static function init () {

		if ( !self::$instance ) {
			self::$instance = new self();
		}

		\Maven\Loggers\Logger::log()->message( 'Blocker/init' );

		self::$instance->protect();
	}

	private function __construct () {
		
	}

	public function protect () {

		\Maven\Loggers\Logger::log()->message( 'Blocker/protect' );

		global $post;

		// We are only protecting pages 
		if ( !is_page() ) {
			return false;
		}

		\Maven\Loggers\Logger::log()->message( 'Blocker/protect: Is Page' );


		// Check if the parent has roles assigned 
		if ( isset( $post->post_parent ) && $post->post_parent > 0 ) {
			return $this->check( get_post( $post->post_parent ) );
		}

		return $this->check( $post );
	}

	private function check ( $post ) {

		$registry = \Maven\Settings\MavenRegistry::instance();

		$meta = get_post_meta( $post->ID, $registry->getSecurityMetaKey(), true );

		// By default is open. So if there is no meta, we don't have to check anything.
		if ( !$meta || count( $meta ) <= 0 || !$meta[ 0 ] ) { //|| ! isset( $meta[ 'capabilities' ] )
			return true;
		}

		//Check if the user is logged
		if ( !is_user_logged_in() ) {
			$this->redirectToLogin();
		}

		// If is an administrator, we should let read the content
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}

		if ( isset( $meta[ 'capabilities' ] ) ) {
			$capabilities = $meta[ 'capabilities' ];
			foreach ( $capabilities as $capability ) {
				if ( current_user_can( $capability ) ) {
					return true;
				}
			}
		} else {
			return true;
		}

		$this->redirectToLogin();
	}

	private function redirectToLogin () {
		$registry = \Maven\Settings\MavenRegistry::instance();

		$loginPage = $registry->getLoginPage();

		$loginPage = \Maven\Core\HookManager::instance()->applyFilters( 'maven/blocker/loginPage', $loginPage );

		if ( !$loginPage ) {
			die( 'You don\'t have access to view this page' );
		}

		$page = get_page_by_path( $loginPage );
		if ( !$page ) {
			throw new \Maven\Exceptions\MavenException( 'Invalid page:' . $loginPage );
		}

		$loginPageUrl = get_permalink( $page );

		if ( $loginPageUrl ) {
			wp_redirect( $loginPageUrl );
			exit();
		} else {
			throw new \Maven\Exceptions\MavenException( 'Invalid page:' . $loginPage );
		}
	}

	function isSubpage ( $page = null ) {
		global $post;
		// is this even a page?
		if ( !is_page() ) {
			return false;
		}

		// does it have a parent?
		if ( !isset( $post->post_parent ) OR $post->post_parent <= 0 ) {
			return false;
		}

		// is there something to check against?
		if ( !isset( $page ) ) {
			// yup this is a sub-page
			return true;
		} else {
			// if $page is an integer then its a simple check
			if ( is_int( $page ) ) {
				// check
				if ( $post->post_parent == $page )
					return true;
			} else if ( is_string( $page ) ) {
				// get ancestors
				$parent = get_ancestors( $post->ID, 'page' );
				// does it have ancestors?
				if ( empty( $parent ) )
					return false;
				// get the first ancestor
				$parent = get_post( $parent[ 0 ] );
				// compare the post_name
				if ( $parent->post_name == $page )
					return true;
			}
			return false;
		}
	}

}
