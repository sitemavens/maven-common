<?php
namespace Maven\SocialNetworks;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class SocialNetwork {

	public static function getAll() {

		$socialNetworks = array( );
		$socialNetworks[ 'facebook' ] = new FacebookSocialNetwork();

		return $socialNetworks;
	}

	public static function &getSocialNetwork( $key ) {

		$socialNetwork = null;

		switch ( strtolower( $key ) ) {
			case "facebook":
				$socialNetwork = new FacebookSocialNetwork();
				break;
		}

		return $socialNetwork;
	}

	public static function post( \Maven\SocialNetworks\Post $post ) {

		$settings = \Maven\Settings\MavenRegistry::instance();

		$socialNetworks = $settings->getEnabledSocialNetworks();

		foreach ( $socialNetworks as $socialNetworkKey ) {

			$socialNetwork = self::getSocialNetwork( $socialNetworkKey );

			if ( $socialNetwork ) {
				$socialNetwork->post( $post );
			}
		}
	}
	
	public static function event( \Maven\SocialNetworks\Event $event ) {

		$settings = \Maven\Settings\MavenRegistry::instance();

		$socialNetworks = $settings->getEnabledSocialNetworks();

		foreach ( $socialNetworks as $socialNetworkKey ) {

			$socialNetwork = self::getSocialNetwork( $socialNetworkKey );

			if ( $socialNetwork ) {
				$socialNetwork->event( $event );
			}
		}
	}

}

