<?php

namespace Maven\Core;

class Ssl {

	function forceSslCheckout( $request ) {
		$httpsPages = \Maven\Settings\MavenRegistry::instance()->getHttpsPages();
		if ( ! $httpsPages || count($httpsPages)==0)
			return; 
		
		if ( is_page( $httpsPages ) && !is_ssl() && in_array( basename(get_permalink()), $httpsPages ) ) {

			wp_safe_redirect( str_replace( 'http:', 'https:', get_permalink( get_the_ID() ) ), 301 );
			exit;
		} elseif ( is_ssl() && !is_page( $httpsPages ) ) {

			wp_redirect( 'http://' . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] );
			exit;
		}
	}

	function forceSslImages( $images ) {
		if ( is_ssl() ) {
			if ( is_array( $images ) ) {
				$images = array_map( array($this,'forceSslImages'), $images );
			} else {
				$images = str_replace( 'http:', 'https:', $images );
			}
		}
		return $images;
	}

}

