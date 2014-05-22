<?php

namespace Maven\MailLists;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of  MaillistFactory
 *
 * @author mustela
 */
class MailListFactory {

	/**
	 * Get a default maillist or you can choose one. 
	 * @param string $key
	 * @return \Maven\MailLists\MailList
	 */
	public static function &getMailList( $key = null ) {

		$mavenRegistry = \Maven\Settings\MavenRegistry::instance();

		if ( ! $key )
			$key = $mavenRegistry->getActiveMaillist()?$mavenRegistry->getActiveMaillist():'';
		
		$maillist = null;
		
		switch ( strtolower( $key ) ) {
			case "mailchimp":
				$maillist = new MailchimpList();
                break;
			
			default:
				$maillist = new DummyList();
		}

		return $maillist;
	}
	
	/**
	 * Return all the existsing maillist
	 * @return \Maven\MailLists\MailList
	 */
	public static function getAll(){
		
		$gateways = array();
		$gateways['mailchimp'] = new MailchimpList();
		
		return $gateways;
	}

}

