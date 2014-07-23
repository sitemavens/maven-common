<?php


namespace Maven\Shortcodes;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


abstract class Base {
	
	
	public function __construct () {
	}
	
	protected function addShortCode( $tag, $function ){
		\Maven\Core\HookManager::instance()->addShortcode($tag, $function);
	}
	
	abstract function addShortcodes();
	
}
