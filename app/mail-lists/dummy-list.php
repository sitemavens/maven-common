<?php

namespace Maven\MailLists;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 *
 * @author mustela
 */
class DummyList extends MailList {

	
	public function __construct() {
		
		$this->setName( 'dummy' );
		
	}
	
	/**
	 * 
	 * @return \Maven\MailLists\Domain\Compaign
	 */
	public function getCampaigns(){
		
		$campaigns = array();
				
		$campaign = new \Maven\MailLists\Domain\Compaign();

		$campaign->setId( -1 );
		$campaign->setTitle('Maillist not configured' );

		$campaigns[] = $campaign;
		 
		return $campaigns;
	}
	
	
	
	/**
	 * 
	 * @return \Maven\MailLists\Domain\MailList
	 */
	public function getLists(){

		
		$lists = array();
			
		$list = new \Maven\MailLists\Domain\MailList();

		$list->setId( -1 );
		$list->setName( 'Maillist not configured' );

		$lists[] = $list;
		
		return $lists;

	}

	public function subscribe( \Maven\Core\Domain\Profile $profile, $sendWelcomeMessage = false ) {
		return false;
	}
	
	public function unSubscribe( \Maven\Core\Domain\Profile $profile ) {
		return false;
	}
	
}