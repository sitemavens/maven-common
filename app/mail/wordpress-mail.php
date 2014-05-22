<?php

namespace Maven\Mail;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

use Maven\Settings\OptionType,
	Maven\Settings\Option;


class WordpressMail extends MailBase {

	
	public function __construct() {
	
		parent::__construct();

		$this->setName( "Wordpress" );
		$this->setId( "wordpress" );
		
		$defaultOptions = array(
//			new Option(
//					"apiKey", "Api Key", '', '', OptionType::Input
//			)
		);

		$this->addSettings( $defaultOptions );
	}
	
	/**
	 * Send an email using wordpress implementation
	 * 
	 * @return boolean Whether the email contents were sent successfully.
	 */
	function send() {
		$headers = "From: {$this->getFromMessage()} <{$this->getFromAccount()}> \r\n";
		if ( $this->getHtml() )
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

		//Send notification
		return \wp_mail( $this->getEmailTo(), $this->getSubject(), $this->getMessage(), $headers );
	}

}

