<?php

namespace Maven\Mail;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

use Maven\Settings\OptionType,
	Maven\Settings\Option;


class PostmarkMail extends MailBase {

	private $apiKey;
    private $postmarkData; 
	
	public function __construct() {
	
		parent::__construct();

		$this->setName( "Postmark" );
		$this->setId( "postmark" );
		
		$defaultOptions = array(
			new Option(
					"apiKey", "Api Key", '', '', OptionType::Input
					
			),
			new Option(
					"emailFrom", "From", '', '', OptionType::Input
					)
		);
		
		$this->addSettings( $defaultOptions );
	}
	
	
	/**
	 * Send an email using Postmark plugin
	 * 
	 * @return boolean Whether the email contents were sent successfully.
	 */
	function send() {

		$this->apiKey = $this->getSetting( 'apiKey' );
		$this->postmarkData['From'] = $this->getSetting( 'emailFrom' );
		$this->postmarkData['ReplyTo'] = $this->getFromAccount();
        $this->postmarkData['To'] = $this->getEmailTo();
        
		$cc = $this->getCc();
		$bcc = $this->getBcc();
		
		if(!empty($cc)){
            $Cc=  implode(',', $this->getCc());
			$this->postmarkData["Cc"] = $Cc;
		}
		if(!empty($bcc)){
            $bcc=  implode(',', $this->getBcc());
			$this->postmarkData["Bcc"] = $bcc;
		}
		
        $this->postmarkData['Subject'] = $this->getSubject();
        $this->postmarkData['HtmlBody'] = $this->getFullMessage();
        
        $headers = array(
			'Accept: application/json',
			'Content-Type: application/json',
			'X-Postmark-Server-Token: '.$this->apiKey
		);
		
		$ch = curl_init('https://api.postmarkapp.com/email');
		
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->postmarkData));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
		$response = curl_exec($ch);
		$curl_error = curl_error($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);
		if($http_code !== 200){
            $response = json_decode($response);
            $this->setErrorDescription($http_code.' : '.$response->Message);
			return false;
		}else{
			return true;
        }
	}

}
