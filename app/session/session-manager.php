<?php

namespace Maven\Session;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class SessionManager{
	
	private static $instance;
	
	/**
	 * Get a default gateway or you can choose one. 
	 * @param string $ey
	 * @return \Maven\Session\SessionBase
	 */
	public static function &get( $key = null ) {

		if ( ! self::$instance ){
			
			$mavenRegistry = \Maven\Settings\MavenRegistry::instance();

	//		if ( !$key )
	//			$key = $mavenRegistry->getActiveGateway();

			switch ( strtolower( $key ) ) {
				case "Native":
					self::$instance = new SessionNative();
					break;

				default:
					self::$instance = new SessionNative();
			}
			
		}
		
		

		return self::$instance;
	}
	
	
	public static function init(){
		self::get();
	}
	
}

