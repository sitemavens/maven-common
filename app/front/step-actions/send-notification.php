<?php

namespace Maven\Front\StepActions;


// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class SendNotification implements iStepAction{
	
	private $thing;
	
	public function __construct ( \Maven\Front\Thing $thing ) {
		$this->thing = $thing;
	}
	public function doAction(){
		
		$registry = \Maven\Settings\MavenRegistry::instance();
		$request = \Maven\Core\Request::current();
		
		$message = "Someone is trying to add a product that has an issue:<br/>";
		$message.= "Thing ID: ".$this->thing->getId()."<br/>";
		$message.= "Thing Name: ".$this->thing->getName()."<br/>";
		$message.= "Plugin Key: ".$this->thing->getPluginKey()."<br/>";
		$message.= "Price: ".$this->thing->getPrice()."<br/>";
		$message.= "Url: ".$request->getCurrentCompleteUrl()."<br/>";
		
		$mail = \Maven\Mail\MailFactory::build();

		$mail->fromAccount( $registry->getSenderEmail() )
			->fromMessage( $registry->getSenderName() )
			->to( $registry->getExceptionNotification() )
			->subject( 'Error adding a product to the cart' )
			->message( $message )
			->send();
		
	}
}