<?php
namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class EntryPoint{
	
	private static $gatewayEntryPointVar ="maven_common_entrypoint";
	
	public static function init(){
		$rule =  "index.php?".self::$gatewayEntryPointVar."=1";
		
		add_rewrite_rule( 'maven/gateway/entry-point$',$rule, 'top' );
		
	}
	
	public static function queryVars( $query_vars ){
		
		$query_vars[] = self::$gatewayEntryPointVar;
		
		return $query_vars;
		
	}

	
	public static function parseRequest( &$wp ){
		
		if ( array_key_exists( self::$gatewayEntryPointVar, $wp->query_vars ) ) {
			
//			

		}
		return;
		
	}
	
}