<?php

namespace Maven;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Validation {

	public function __construct() {
		;
	}
	
	public static function isGFMissing () {

		$result = class_exists( '\GFForms' );

		// If the common plugin isn't activate, lets add a default option.
		if ( !$result ) {
			$exists = in_array( 'gravityforms/gravityforms.php', (array) get_option( 'active_plugins', array() ) );
			if ( $exists ) {
				$result = require_once( ABSPATH . "wp-content/plugins/gravityforms/gravityforms.php" );
			} 
		}

		return !$result;
	}

}
 
