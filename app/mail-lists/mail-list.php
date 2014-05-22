<?php

namespace Maven\MailLists;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 *
 * @author mustela
 */
abstract class MailList {

	private $options = array( );
	private $maillistKey;
	private $key;
	private $name;
	private $id;
	
	
	private function getMaillistKey() {

		//We need to sanitize the key just one time
		if ( !$this->maillistKey )
			$this->maillistKey = "mvn-maillists-" . sanitize_key( $this->getName() );

		return $this->maillistKey;
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
	
	public function getSettings(){
		return $this->options;
	}
	
	/**
	 * 
	 * @return \Maven\MailLists\Domain\Compaign
	 */
    abstract function getCampaigns();  
	
	/**
	 * 
	 * @return \Maven\MailLists\Domain\MailList
	 */
	abstract function getLists();
	
	/**
	 * Unsubscribe profile
	 */
	abstract function unSubscribe( \Maven\Core\Domain\Profile $profile );
	
	/**
	 * Subscribe a profile
	 */
	abstract function subscribe( \Maven\Core\Domain\Profile $profile, $sendWelcomeMessage = false );
			
	private function syncronize() {
		
		// Get the options from the db
		$existingsOptions = get_option( $this->getMaillistKey() );
		
		// Get the saved options in the object
		$options = $this->getSettings();
		
		// If options exists we need to merge them with the default ones
		if ( $existingsOptions ){
			foreach ( $existingsOptions as $option ){
				$options[ $option->getName() ]->setValue( $option->getValue() );
			}
		}
			
	}
	
	/**
	 * 
	 * @param \Maven\Settings\Option[] $options
	 */
	public function saveOptions( $options ) {

		//Save the options in the WP table
		update_option( $this->getKey(), $options );

		//$this->setOptions( $options );
	}
	
	
	public function getId() {
		return $this->id;
	}

	public function setId( $id ) {
		$this->id = $id;
	}
	
	private function getKey() {

		//We need to sanitize the key just one time
		if ( !$this->key )
			$this->key = "mvn-maillists-" . sanitize_key( $this->getName() );

		return $this->key;
	}
}