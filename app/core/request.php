<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class Request {

	private static $instance; 
	
	private $properties;
	private $isPost = false;
	private $isGet	= false;

	public function __construct() {
		$this->init();
	}
	
	/**
	 * Get the current request
	 * @return \Maven\Core\Request
	 */
	public static function current(){
		
		if ( ! self::$instance ) self::$instance = new self();
		
		return self::$instance;
		
	}
	
	
	public static function simulate( $data ){
		
		if ( is_array( $data ) ){
			
			$current = self::current();
			
			//We have to clean the existings properties
			$current->properties = array();
			foreach( $data as $key=>$value )
				$current->setProperty($key, $value);
			
			return $current;
		}
		
		return array();
		
	}

	private function init() {
		
		if (isset($_SERVER['REQUEST_METHOD'])) {
			$this->properties = $_REQUEST;
			
			switch( $_SERVER['REQUEST_METHOD'] ){
				case "GET":$this->isGet = true;
					break;
				case "POST":$this->isPost = true;
					break;
			}
			return;
		}
		foreach ($_SERVER['argv'] as $arg) {
			if (strpos($arg, '=')) {
				list( $key, $val ) = explode("=", $arg);
				$this->setProperty($key, $val);
			}
		}
	}
	
	public function isPost(){
		return $this->isPost;
	}
	
	public function isGet(){
		return $this->isGet;
	}

	public function getProperty( $key ) {
		
		if ( isset($this->properties[$key]) ) {
			return \stripslashes_deep( $this->properties[$key] );
		}
		
		return false;
	}
	
	public function isDoingAjax(){
		return defined('DOING_AJAX') && DOING_AJAX;
	}
	public function exists( $key ) {
		
		if ( isset($this->properties[$key]) )  
			return true;
				
		
		return false;
	}

	public function setProperty( $key, $val ) {
		$this->properties[ $key ] = $val;
	}
	
	public function getIp(){
		
		if( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) 
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if( isset( $_SERVER ['HTTP_VIA'] ))  
			$ip = $_SERVER['HTTP_VIA'];
		else if( isset( $_SERVER ['REMOTE_ADDR'] ) )  
			$ip = $_SERVER['REMOTE_ADDR'];
		else $ip = null ;
		
		return $ip;
	}
	
	
	
	public function getKeys(){
		return array_keys( $this->properties );
	}
	
	public function getAdminUrl(){
		return admin_url( 'admin.php' );
	}
	
	public function getCurrentCompleteUrl(){

		return filter_input( INPUT_SERVER, 'PATH_INFO',FILTER_SANITIZE_URL );
		
	}
	
	public function getCurrentUri(){
		return filter_input( INPUT_SERVER, 'REQUEST_URI',FILTER_SANITIZE_URL );
	} 
	
	public function getUserAgent(){
		return filter_input( INPUT_SERVER, 'HTTP_USER_AGENT',FILTER_SANITIZE_STRING );
	}
	
	public function getReferral(){
		return filter_input( INPUT_SERVER, 'HTTP_REFERER',FILTER_SANITIZE_URL );
	}

}