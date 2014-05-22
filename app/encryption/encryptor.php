<?php

namespace Maven\Encryption;


// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;
 

abstract class Encryptor{
	
	private $encryptionKey;
	private $name;

	
	abstract function encrypt( $string, $key ="");
	
	abstract function decrypt( $string , $key ="");
	
	
	
	public function __construct() {
		;
	}
	
	public function getHash( $mixed ) {
		if ( !$mixed ) {
			return '';
		} else {
			$theString = '';
			if ( is_array( $mixed ) ) {
				$theString = serialize( $mixed );
			} else {
				$theString = $mixed;
			}
			$lower = strtolower( $theString );
			return sha1( md5( $lower ) );
		}
	}
	
	/**
	 * 
	 * @param \Maven\Settings\Option $option
	 */
	
	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}
	
	public function getEncryptionKey() {
		return $this->encryptionKey;
	}

	public function setEncryptionKey( $encryptionKey ) {
		$this->encryptionKey = $encryptionKey;
	}
	
	protected function addSetting( \Maven\Settings\Option $option ) {

		$this->options[ $option->getName() ] = $option;
	}

	protected function addSettings( $options ) {

		foreach ( $options as $option )
			$this->addSetting( $option );

		$this->syncronize();
	}

	protected function getSetting( $key ) {
		return isset( $this->options[ $key ] ) ? $this->options[ $key ]->getValue() : '';
	}

	public function getSettings() {
		return $this->options;
	}
	
	
	private function syncronize() {

		// Get the options from the db
		$existingsOptions = get_option( $this->getEncryptionKey() );

		// Get the saved options in the object
		$options = $this->getSettings();

		// If options exists we need to merge them with the default ones
		if ( $existingsOptions ) {
			foreach ( $existingsOptions as $option ) {
				if ( isset( $options[ $option->getName() ] ) )
					$options[ $option->getName() ]->setValue( $option->getValue() );
			}
		}

	}
	
}