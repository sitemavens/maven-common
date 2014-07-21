<?php


namespace Maven\ShortCodes;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


abstract class Base {
	
	
	public function __construct () {
	}
	
	protected function addShortCode( $tag, $function ){
		\Maven\Core\HookManager::instance()->addShortCode($tag, $function);
	}
	
	abstract function addShortCodes();
	
}
