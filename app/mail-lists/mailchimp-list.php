<?php

namespace Maven\MailLists;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

use Maven\Settings\OptionType,
	Maven\Settings\Option;

if ( ! class_exists( 'MCAPI' ) )
	require_once __DIR__.'/../libs/mailchimp/MCAPI.class.php';


/**
 *
 * @author mustela
 */
class MailchimpList extends MailList {

	private $api = null;
	private $listsRows = null;
	
	public function __construct() {
		
		$this->setName( 'Mailchimp' );
		$this->setId( "mailchimp" );
		
		$defaultOptions = array(
			new Option(
					"apiKey", "Api Key", '', '', OptionType::Input
			)
		);

		$this->addSettings( $defaultOptions );
		
		$this->api = new \MCAPI( $this->getSetting( 'apiKey' ) );
	}
	
	/**
	 * 
	 * @return \Maven\MailLists\Domain\Compaign
	 * @link http://apidocs.mailchimp.com/api/1.3/campaigns.func.php
	 */
	public function getCampaigns(){
		
		$campaignsRows = $this->api->campaigns();
		
		$this->checkError();
		
		$campaigns = array();
		
		if ( isset( $campaignsRows['data'] ) ){
			
			$data = $campaignsRows['data'];
			
			foreach($data as $campaignRow){
				
				$campaign = new \Maven\MailLists\Domain\Compaign();
				
				$campaign->setId( $campaignRow['id'] );
				$campaign->setTitle( $campaignRow['title'] );
				$campaign->setStatus( $campaignRow['status'] );
				$campaign->setType( $campaignRow['type'] );
				$campaign->setEmailsSent( $campaignRow['emails_sent'] );
				
				$campaigns[] = $campaign;
			}
		}
		 
		return $campaigns;
	}
	
	/**
	 * 
	 * @link http://apidocs.mailchimp.com/api/1.3/listsubscribe.func.php
	 * @param \Maven\Core\Domain\Profile $profile
	 * @param type $sendWelcomeMessage
	 * @return type
	 */
	public function subscribe( \Maven\Core\Domain\Profile $profile, $sendWelcomeMessage = false ){
		
		$merge_vars = array(
				'FNAME'=>$profile->getFirstName(), 
				'LNAME'=>$profile->getLastName(), 
				'GROUPINGS'=>array(
//					  array('name'=>'Your Interests:', 'groups'=>'Bananas,Apples'),
//					  array('id'=>22, 'groups'=>'Trains')
					  )
				  );
		
		// By default this sends a confirmation email - you will not see new members
		// until the link contained in it is clicked!
		$retval = $this->api->listSubscribe( $profile->getMaillist(), $profile->getEmail(), 
						$merge_vars, 'html', false, 
						true, true, $sendWelcomeMessage );

		return $this->checkError();
	}
	
	/**
	 * @link http://apidocs.mailchimp.com/api/1.3/listunsubscribe.func.php
	 * @param \Maven\Core\Domain\Profile $profile
	 * @return type
	 */
	public function unSubscribe( \Maven\Core\Domain\Profile $profile  ){
		
		$retval = $this->api->listUnsubscribe( $profile->getMaillist(),$profile->getEmail() );

		return $this->checkError();
	}
	
	
	/**
	 * 
	 * @return \Maven\MailLists\Domain\MailList
	 * @link http://apidocs.mailchimp.com/api/1.3/lists.func.php
	 */
	public function getLists(){

		$listsRows= wp_cache_get( 'lists', 'maven-common-maillist' );
		
		if ( ! $listsRows ){
			$listsRows = $this->api->lists();
			wp_cache_add( 'lists', $listsRows, 'maven-common-maillist', 0 );
		}

		if ( ! ($result = $this->checkError() ) )
				return array();
		
		$lists = array();
		
		if ( isset( $listsRows['data'] ) ){
			
			$data = $listsRows['data'];
			
			foreach($data as $listRow){
				
				$list = new \Maven\MailLists\Domain\MailList();
				
				$list->setId( $listRow['id'] );
				$list->setName( $listRow['name'] );
				
				$lists[] = $list;
				
			}
		}
		
		return $lists;

	}
	
	 
	
	private function checkError( ){
		
//		if( $this->api->errorCode ){
//			
//			if ( $this->api->errorCode== -99 )
//				// We don't have internet
//				return false;
//			else
//				throw new \Maven\Exceptions\MavenException( "Code:{$this->api->errorCode}, MSG: {$this->api->errorMessage}" );
//				
//		}
		
		return true;
	}
	
}