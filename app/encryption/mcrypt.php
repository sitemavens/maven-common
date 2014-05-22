<?php

namespace Maven\Encryption;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

use Maven\Settings\OptionType,
	Maven\Settings\Option;

class Mcrypt extends Encryptor {

	const module = 'rijndael-128';
	const mode = 'cbc';

	public function __construct() {

		parent::__construct();

		$this->setName( "Mcrypt" );

		$defaultOptions = array(
			new Option(
					"key", "Key", AUTH_KEY, '', OptionType::Input
			)
		);

		$this->addSettings( $defaultOptions );
	}

	private function getKey() {
		return $this->getSetting( 'key' );
	}

	

	public function encrypt( $string, $key ="" ) {
		
		if ( !$string ) {
			return '';
		}
		
		$encrypted = null;

		srand( ( double ) microtime() * 1000000 ); //for sake of MCRYPT_RAND
		$key = md5( $key?$key:$this->getKey() );

		/* Open module, and create IV */
		$td = mcrypt_module_open( self::module, '', self::mode, '' );
		$key = substr( $key, 0, mcrypt_enc_get_key_size( $td ) );
		$iv_size = mcrypt_enc_get_iv_size( $td );
		$iv = mcrypt_create_iv( $iv_size, MCRYPT_RAND );

		$encrypted = null;

		/* Initialize encryption handle */
		if ( mcrypt_generic_init( $td, $key, $iv ) != -1 ) {
			/* Encrypt data */
			$encrypted = mcrypt_generic( $td, $string );
			mcrypt_generic_deinit( $td );
			mcrypt_module_close( $td );
			$encrypted = $iv . $encrypted;
		}
		
		return $encrypted?base64_encode( $encrypted ):$string;
		
	}

	public  function decrypt( $string, $key ="" ) {
		if ( ! $string ) {
			return '';
		}
		
		$decrypted = null;

		$string = base64_decode( $string );

		$key = md5( $key?$key:$this->getKey() );

		/* Open module, and create IV */
		$td = mcrypt_module_open( self::module, '', self::mode, '' );
		$key = substr( $key, 0, mcrypt_enc_get_key_size( $td ) );
		$iv_size = mcrypt_enc_get_iv_size( $td );
		$iv = substr( $string, 0, $iv_size );

		$string = substr( $string, $iv_size );

		$decrypted = null;
		/* Initialize encryption handle */
		if ( mcrypt_generic_init( $td, $key, $iv ) != -1 ) {
			/* Encrypt data */
			$decrypted = mdecrypt_generic( $td, $string );
			mcrypt_generic_deinit( $td );
			mcrypt_module_close( $td );
		}
		
		if ( isset( $decrypted ) ) {
			$decrypted = trim( $decrypted );
		}
		
		return $decrypted?$decrypted:$string;
	}

}

