<?php

namespace Maven\Encryption;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

use Maven\Settings\OptionType,
	Maven\Settings\Option;

class XorEncryptor extends \Maven\Encryption\Encryptor{
	
	
	public function __construct() {
		
		parent::__construct();

		$this->setName( "Xor" );

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
	
	public function decrypt( $string , $key ="") {
		
		$string = $this->merge( $string );

		$dec = '';
		for ($i = 0; $i < strlen($string); $i++)
		{
			$dec .= (substr($string, $i++, 1) ^ substr($string, $i, 1));
		}
		

		return $dec;
	}

	
	
	public function encrypt( $string , $key ="") {
		
		$rand = '';
		while (strlen($rand) < 32)
		{
			$rand .= mt_rand(0, mt_getrandmax());
		}

		$rand = $this->getHash($rand);

		$enc = '';
		for ($i = 0; $i < strlen($string); $i++)
		{
			$enc .= substr($rand, ($i % strlen($rand)), 1).(substr($rand, ($i % strlen($rand)), 1) ^ substr($string, $i, 1));
		}

		return $this->merge($enc);
		
	}	
	
	
	/**
	 * XOR key + string Combiner
	 *
	 * Takes a string and key as input and computes the difference using XOR
	 *
	 * @access	private
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	function merge( $string )
	{
		$hash = $this->getHash( $this->getKey() );
		$str = '';
		for ($i = 0; $i < strlen($string); $i++)
		{
			$str .= substr($string, $i, 1) ^ substr($hash, ($i % strlen($hash)), 1);
		}

		return $str;
	}
}