<?php

namespace Maven\Encryption;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of mvn-gateway-pro-manager
 *
 * @author mustela
 */
class EncryptionFactory {

	
	/**
	 * Get a default Encryptor or you can choose one. 
	 * @param string $ey
	 * @return \Maven\Encryption\Encryptor
	 */
	public static function &getEncryptor( $key = null ) {

		$mcryptEnabled = function_exists( 'mcrypt_module_open' );
		
		if ( ! $key )
			$key = ! $mcryptEnabled?"xor":'';
			
			
		switch ( strtolower( $key ) ) {
			case "xor":
				$encryptor = new XorEncryptor();
				break;
			default:
				$encryptor = new Mcrypt();
		}


		return $encryptor;
	}
	
	/**
	 * Return all the existsing Encryptors
	 * @return \Maven\Encryption\Encryptor[]
	 */
	public static function getAll(){
		
		$encryptors = array();
		
		$encryptor = new Mcrypt();
		$encryptors[$encryptor->getName()] = $encryptor;
		
		$encryptor = new XorEncryptor();
		$encryptors[$encryptor->getName()] =$encryptor;
		
		return $encryptors;
		
	}
	
	
}
			