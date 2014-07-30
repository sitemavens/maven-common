<?php

namespace Maven\Api;

class Loader {

	public static function init () {

		$hookManager = \Maven\Core\HookManager::instance();
		
		$cartV2 = V2\Cart::current();
		$hookManager->addAction( 'wp_json_server_before_serve', array( $cartV2, 'registerRestApi' ) );
		
		$profile = new V2\Profile();
		$hookManager->addAction( 'wp_json_server_before_serve', array( $profile, 'registerRestApi' ) );
		
	}

}
