<?php

namespace Maven\Mail;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

use Maven\Settings\OptionType,
	Maven\Settings\Option;

class MandrillMail extends MailBase {

	public function __construct () {


		parent::__construct();

		$this->setName( "Mandrill" );
		$this->setId( "mandrill" );

		$defaultOptions = array(
			new Option(
					"apiKey", "Api Key", '', '', OptionType::Input
			),
		);

		$this->addSettings( $defaultOptions );
	}

	/**
	 * Send an email using mailchimp mandrill API
	 * 
	 * @return boolean Whether the email contents were sent successfully.
	 */
	function send () {

		require_once __DIR__ . '/../libs/mandrill/Mandrill.php';

		$apiKey = $this->getSetting( 'apiKey' );
		$apiKey = \Maven\Core\HookManager::instance()->applyFilters( 'maven/mail/mandrill/api-key', $apiKey );

		if ( !$apiKey ) {
			throw new \Maven\Exceptions\RequiredException( 'Mandrill Api Key is required' );
		}

		\Maven\Loggers\Logger::log()->message( 'Maven\Mail\MandrillMail: apiKey: ' . $apiKey );

		//TODO: Put this api key as a setting in maven-common
		$mandrill = new \Mandrill( $apiKey );

		if ( !$this->getEmailTo() ) {
			throw new \Maven\Exceptions\MissingParameterException( 'You need at least one "to" ' );
		}

		$toSenders = $this->getEmailTo();

		//Check if there are more than one. 
		$receivers = explode( ',', $toSenders );

		$to = array();

		foreach ( $receivers as $receiver ) {
			$to[] = array( 'email' => $receiver );
		}


		if ( $this->getCc() || count( $this->getCc() ) > 0 ) {
			//Mandrill api dont support CC address. It will be added as to address
			foreach ( $this->getCc() as $cc_mail ) {
				$to[] = array( 'email' => $cc_mail );
			}
		}

		// TODO: Mandrill's API only accept one BCC address. The other addresses supplied will be silently discarted
		$bcc = '';
		$array_bcc = $this->getBcc();
		if ( !$array_bcc || count( $array_bcc ) == 0 ) {

			// If there is no bcc, lets try to add the global bcc.
			$bccEmail = \Maven\Settings\MavenRegistry::instance()->getBccNotificationsTo();
			$array_bcc = array( $bccEmail );
		}

		if ( $array_bcc && count( $array_bcc ) > 1 ) {
			$bcc = explode( ',', $array_bcc[ 0 ] );
			$bcc = $bcc[ 0 ];
		}

		$message = array(
			'subject' => $this->getSubject(),
			'from_email' => $this->getFromAccount(),
			'from_name' => $this->getFromMessage(),
			'html' => $this->getFullMessage(),
			'to' => $to,
			'bcc_address' => $bcc,
			'tags' => array( get_bloginfo(), get_bloginfo( 'url' ) )
		);


		$response = @$mandrill->messages->send( $message );

		if ( !$response || array_key_exists( 'status', $response ) && $response->status == 'error' ) {
			return false;
		}

		return true;

		/* $headers=array();

		  $cc = $this->getCc();
		  $bcc = $this->getBcc();

		  if(!empty($cc)){
		  $cc=  implode(',', $this->getCc());
		  $headers['cc']=$cc;
		  }
		  if(!empty($bcc)){
		  $bcc=  implode(',', $this->getBcc());
		  $headers['bcc']=$bcc;
		  }
		  var_dump($headers);
		  $response = \wpMandrill::mail(
		  , $this->getSubject(), ,
		  $headers, //headers
		  array( ), //attachments
		  array(), //tags
		  , $this->getFromMessage()
		  );
		  var_dump($response);
		  if ( is_wp_error( $response ) )
		  return false; */
	}

}
