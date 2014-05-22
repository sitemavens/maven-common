<?php

namespace Maven\Mail;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


use Maven\Settings\OptionType,
	Maven\Settings\Option;


class AmazonSesMail extends MailBase {

	
	public function __construct() {
	
		parent::__construct();

		$this->setName( "Amazon SES" );
		$this->setId( "amazon-ses" );
		
		$defaultOptions = array(
			new Option(
					"accessKey", "Access Key", '', '', OptionType::Input
			),
			new Option(
					"secretKey", "Secret Key", '', '', OptionType::Input
			)
		);

		$this->addSettings( $defaultOptions );
	}
	
	/**
	 * AmazonSes using SimpleEmailService lib
	 * 
	 * @return boolean Whether the email contents were sent successfully.
	 */
	function send() {

        //WE need to load the library
		require_once __DIR__.'/../libs/ses.php';
        $ses = new \SimpleEmailService( $this->getSetting( 'accessKey'), $this->getSetting( 'secretKey') );
        $ses->enableVerifyHost(false);
        $ses->enableVerifyPeer(false);
        
        $m = new \SimpleEmailServiceMessage();

        $m->addTo($this->getEmailTo());
        $m->setFrom('noreplay@inimarin.com');
        $m->setSubject($this->getSubject());
        if(trim($this->getFromAccount())!='') $m->addReplyTo($this->getFromAccount());
        
		$cc = $this->getCc();
		$bcc = $this->getBcc();
        
        
		if(!empty($cc) && $cc[0]!=''){
		    $Cc = implode(',', $this->getCc());
            $m->addCC($Cc);
		}
		if(!empty($bcc) && $bcc[0]!=''){
		    $bcc = implode(',', $this->getBcc());
            $m->addBCC($bcc);
		}
        $m->setMessageFromString($this->getFullMessage());

        if($ses->sendEmail($m)){
            return true;
        }else{
            $this->setErrorDescription($ses->getErrorDescription());
            return false;   
        }
	}

}
