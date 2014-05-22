<?php

namespace Maven\SocialNetworks;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of 
 *
 * @author lucasmpb
 */
abstract class BaseSocialNetwork {

	private $options = array( );
	private $socialNetworkKey = "";
	private $name;

	public function __construct( $name ) {
		$this->setName( $name );
	}

	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
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

	/**
	 * 
	 * @param \Maven\Settings\Option[] $options
	 */
	public function saveOptions( $options ) {

		//Save the options in the WP table
		update_option( $this->getSocialNetworkKey(), $options );

	}

//	public function set( $key, $value ) {
//		
//		parent::set( $key, $value);
//			
//		update_option( $this->getSettingKey(), $this->getOptions() );
//			
//	}

	private function getSocialNetworkKey() {

		//We need to sanitize the key just one time
		if ( !$this->socialNetworkKey )
			$this->socialNetworkKey = "mvn-socnet-" . sanitize_key( $this->getName() );

		return $this->socialNetworkKey;
	}

	private function syncronize() {

		// Get the options from the db
		$existingsOptions = get_option( $this->getSocialNetworkKey() );

		// Get the saved options in the object
		$options = $this->getSettings();

		// If options exists we need to merge them with the default ones
		if ( $existingsOptions ) {
			foreach ( $existingsOptions as $option ) {
				$options[ $option->getName() ]->setValue( $option->getValue() );
			}
		}

		 
	}
	
	abstract function post ( Post $post );

	abstract function event( Event $event);
}