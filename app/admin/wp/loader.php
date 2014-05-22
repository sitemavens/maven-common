<?php

namespace Maven\Admin\Wp;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class Loader  {
	
	public function __construct () {
		
	}
	
	public function init(){
		
	}
	
	public static function adminInit(){
		
		$users = new Users();
		$users->init();
		
	}
	
}


