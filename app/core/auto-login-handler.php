<?php

namespace Maven\Core;

class AutoLoginHandler {

	private static $entryPointVar = 'maven_auto_login';

	const EmailVar = 'email';
	const KeyVar = 'key';

	public function __construct () {
		;
	}

	public static function init () {

		$mavenSettings = \Maven\Settings\MavenRegistry::instance();

		add_rewrite_rule( "^{$mavenSettings->getAutoLoginUrl()}?", "index.php?" . self::$entryPointVar . '=1', 'top' );
	}

	public static function queryVars ( $query_vars ) {

		$query_vars[] = self::$entryPointVar;
		$query_vars[] = self::EmailVar;
		$query_vars[] = self::KeyVar;
		return $query_vars;
	}

	public static function parseRequest ( &$wp ) {

		if ( array_key_exists( self::$entryPointVar, $wp->query_vars ) ) {

			$autoLoginEmail = isset( $wp->query_vars[ self::EmailVar ] ) ? $wp->query_vars[ self::EmailVar ] : false;
			$autoLoginKey = isset( $wp->query_vars[ self::KeyVar ] ) ? $wp->query_vars[ self::KeyVar ] : false;

			if ( !( $autoLoginEmail && $autoLoginEmail ) ) {
				throw new \Maven\Exceptions\MavenException( 'Invalid credentials' );
			}

			$result = UserManager::autoLogin( $autoLoginEmail, $autoLoginKey );

			if ( $result ) {
				wp_redirect( site_url() );
				exit();
			} else {
				die( 'Invalid data' );
			}
		}

		return;
	}

}
