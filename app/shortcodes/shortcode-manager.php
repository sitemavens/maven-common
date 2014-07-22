<?php

namespace Maven\Shortcodes;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class ShortCodeManager  {

	public static function addShortCodes () {

		$classes = array(
			new Profile()
		);

		foreach( $classes as $class ){
			$class->addShortcodes();
		}
		 
	}
	
}
