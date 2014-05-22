<?php

namespace Maven\Loggers;

class Logger{
	
	
	private static $log = null;
	
	private function __construct () {
	}
	
	
	/**
	 * 
	 * @return \Maven\Loggers\Log
	 */
	public static function log(){
		if ( ! self::$log ){
			self::$log = new DefaultLog( defined('MAVEN_DEBUG') && MAVEN_DEBUG);
		}
		
		return self::$log;
	}
	
}