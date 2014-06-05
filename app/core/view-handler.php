<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class ViewHandler{
	private static $entryPointVar = 'maven_view_handler';

	public function __construct() {
		;
	}

	public static function init() {

		$mavenSettings = \Maven\Settings\MavenRegistry::instance();

		add_rewrite_rule( "^{$mavenSettings->getAdminViewHandlerUrl()}([^/]*)/([^/]*)/?", "index.php?" . self::$entryPointVar . '=1&key=$matches[1]&view=$matches[2]', 'top' );
		
		}

	public static function queryVars( $query_vars ) {

		$query_vars[] = self::$entryPointVar;
		$query_vars[] = 'key';
		$query_vars[] = 'view';

		return $query_vars;
	}

	public static function parseRequest( $wp ) {

		
		// Hay que ver el tema de la seguridad a ver como lo mejoramos.
		if (  ! current_user_can( 'manage_options')){
			return;
		}
		
		if ( array_key_exists( self::$entryPointVar, $wp->query_vars ) ) {
			
			$key = isset( $wp->query_vars[ 'key' ] ) ? $wp->query_vars[ 'key' ] : false;
			$view = isset( $wp->query_vars[ 'view' ] ) ? $wp->query_vars[ 'view' ] : false;
			
			if ( ! $key ) {
				return;
			}
			
			if ( ! $view ) {
				return;
			}
			
			$viewOutput = HookManager::instance()->applyFilters("maven/views/get/{$key}", $view);
			die($viewOutput);
			
		}

		return;
	}
}
