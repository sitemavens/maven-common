<?php

namespace Maven\Tracking;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of mvn-gateway-pro-abs
 *
 * @author mustela
 */
abstract class BaseTracker {

	private $options = array( );
	private $trackerKey = "";
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
		update_option( $this->getTrackerKey(), $options );

	}

//	public function set( $key, $value ) {
//		
//		parent::set( $key, $value);
//			
//		update_option( $this->getSettingKey(), $this->getOptions() );
//			
//	}

	public function getTrackerKey() {

		//We need to sanitize the key just one time
		if ( !$this->trackerKey )
			$this->trackerKey = "mvn-tracker-" . sanitize_key( $this->getName() );

		return $this->trackerKey;
	}

	private function syncronize() {

		// Get the options from the db
		$existingsOptions = get_option( $this->getTrackerKey() );

		// Get the saved options in the object
		$options = $this->getSettings();

		// If options exists we need to merge them with the default ones
		if ( $existingsOptions ) {
			foreach ( $existingsOptions as $option ) {
				$options[ $option->getName() ]->setValue( $option->getValue() );
			}
		}

		 
	}
	
	
	abstract function addTransaction ( ECommerceTransaction $transaction );
	
	abstract function addEvent ( Event $event );

}