<?php

namespace Maven\Mail;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * This class is used when you need the body of the email
 * constructed with the configured logo, header, and footer,
 * but you dosnt gonna use the configured email provider from maven
 * Examples: Password retieval email.
*/

class DummyMail extends MailBase {
	
	
	public function __construct() {

		parent::__construct();
	}
	
	function send() {
		//NO OP - DO NOTHING
		return true;
	}

}
