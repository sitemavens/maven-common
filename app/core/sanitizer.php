<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class Sanitizer{
	
	
	public static function slug( $value ) {
		return sanitize_title( $value );
	}
	
	
}