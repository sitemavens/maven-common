<?php

namespace Maven\Session;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


abstract class SessionBase{
	
	
	private $sessionKey;
	private $options = array( );
	private $name;
	
	
	public function __construct(  ) {
		
	}
	
	
	private function getSessionKey() {

		//We need to sanitize the key just one time
		if ( !$this->sessionKey )
			$this->sessionKey = "mvn-session-" . sanitize_key( $this->getName() );

		return $this->sessionKey;
	}

	private function syncronize() {

		// Get the options from the db
		$existingsOptions = get_option( $this->getSessionKey() );

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
	
	
	/**
	 * 
	 * @param \Maven\Settings\Option $option
	 */
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
		
	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}

	abstract function addData( $key, $value );
	
	abstract function removeData( $key );

	abstract function getData( $key );
}