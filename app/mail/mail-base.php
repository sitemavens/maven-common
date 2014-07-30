<?php

namespace Maven\Mail;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

abstract class MailBase {

	private $options = array( );
	private $key;
	private $name;
	private $id;
	
	private $errorDescription = "";
	private $html = true;
	private $emailTo = "";
	private $bcc = array( );
	private $cc = array( );
	private $message = "";
	private $subject = "";
	private $fromAccount = "";
	private $fromMessage = "";
	private $useTemplate = true;
	
	
	public function __construct() {
		;
	}

	public function getErrorDescription() {
		return $this->errorDescription;
	}

	public function setErrorDescription( $errorDescription ) {
		$this->errorDescription = $errorDescription;
	}

	public function getHtml() {
		return $this->html;
	}

	public function getEmailTo() {
		return $this->emailTo;
	}

	public function getBcc() {
		return $this->bcc;
	}

	public function getCc() {
		return $this->cc;
	}

	public function getMessage() {
		return $this->message;
	}
 
	public function getFullMessage() {

		return \Maven\Core\MailFormatter::prepareContentEmail( $this->message, $this->useTemplate );
	}

	public function getSubject() {
		return $this->subject;
	}

	public function getFromAccount() {
		
		$registry = \Maven\Settings\MavenRegistry::instance();
		
		if ( \Maven\Core\Utils::isEmpty( $this->fromAccount ) ) {
			$this->fromAccount = $registry->getSenderEmail();
		}


		return $this->fromAccount;
	}

	public function getFromMessage() {
		
		$registry = \Maven\Settings\MavenRegistry::instance();
		
		if ( \Maven\Core\Utils::isEmpty( $this->fromMessage ) ) {
			$this->fromMessage = $registry->getSenderName();
		}

		return $this->fromMessage;
	}

	public function html( $value = true ) {
		$this->isHtml = $value;

		return $this;
	}

	public function fromAccount( $fromAccount ) {
				
		$this->fromAccount = $fromAccount;
		
		return $this;
	}

	public function fromMessage( $fromMessage ) {

		$this->fromMessage = $fromMessage;
		
		
		
		return $this;
	}

	public function to( $emailTo ) {

		$this->emailTo = $emailTo;

		return $this;
	}

	public function bcc( $bcc ) {

		$this->bcc[ ] = $bcc;

		return $this;
	}

	public function cc( $cc ) {

		$this->cc[ ] = $cc;

		return $this;
	}

	public function subject( $subject ) {

		$this->subject = $subject;
		return $this;
	}

	public function message( $message ) {

		$this->message = $message;
		return $this;
	}
	
	

	public function useTemplate ( $useTemplate ) {
		$this->useTemplate = $useTemplate;
		return $this;
	}

	
	/**
	 * Send the email.
	 */
	abstract function send();
	
	
	/**
	 * 
	 * @param \Maven\Settings\Option $option
	 */
	protected function addSetting( \Maven\Settings\Option $option ) {

		$this->options[ $option->getName() ] = $option;
	}

	protected function addSettings( $options ) {

		foreach ( $options as $option ) {
			$this->addSetting( $option );
		}

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
		update_option( $this->getKey(), $options );

		//$this->setOptions( $options );
	}

//	public function set( $key, $value ) {
//		
//		parent::set( $key, $value);
//			
//		update_option( $this->getSettingKey(), $this->getOptions() );
//			
//	}

	private function getKey() {

		//We need to sanitize the key just one time
		if ( !$this->key ) {
			$this->key = "mvn-mail-provider-" . sanitize_key( $this->getName() );
		}

		return $this->key;
	}

	private function syncronize() {

		// Get the options from the db
		$existingsOptions = get_option( $this->getKey() );

		// Get the saved options in the object
		$options = $this->getSettings();

		// If options exists we need to merge them with the default ones
		if ( $existingsOptions ) {
			foreach ( $existingsOptions as $option ) {
				if ( isset( $options[ $option->getName() ] ) ) {
					$options[ $option->getName() ]->setValue( $option->getValue() );
				}
			}
		}

	}
	
	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}

	public function getId() {
		return $this->id;
	}

	public function setId( $id ) {
		$this->id = $id;
	}

	
}
