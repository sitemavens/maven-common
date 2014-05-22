<?php

namespace Maven\SocialNetworks;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


//WE need to load the library
require_once __DIR__ . '/../libs/facebook/facebook.php';

use Maven\Settings\OptionType,
    Maven\Settings\Option;

class FacebookSocialNetwork extends BaseSocialNetwork {

	public function __construct() {
		parent::__construct( 'Facebook' );

		//Access Token
		// CAACEdEose0cBAAsg2n58FVYdDsgFgTKEynL3ugEmZCVQEIiVAch21FHZByjcQTEOjZBJSlZBn8ECDTXIPSsCBGqonl68VOO99P9TA0Ob7clNS918abZCeRe9AKpVXwCnjZAo5kvJrXVhsR1fNjbTGZASSnydZBmxDVbtq5BdJ0uaQAZDZD
		//id
		//{
		//	"id": "1098431849", 
		//	"name": "Lucas Martin Perez Buscaglia"
		//}

		$defaultOptions = array(
		    new Option(
			    "facebookAppId", "Facebook App Id", '', '', OptionType::Input
		    ),
		    new Option(
			    "facebookSecret", "Facebook Secret", '', '', OptionType::Input
		    ),
		    new Option(
			    "pageId", "Page Id", '', '', OptionType::Input
		    ),
		    new Option(
			    "facebookAccessToken", "Facebook Access Token", '', '', OptionType::Input
			),
		    new Option(
			    "facebookFileUpload", "Facebook File Upload", '', '', OptionType::Input
		    )
		);


		$this->addSettings( $defaultOptions );
	}

	public function post( Post $post ) {

		$config = array(
		    'appId' => $this->getSetting( 'facebookAppId' ),
		    'secret' => $this->getSetting( 'facebookSecret' ),
		    'cookie' => false
		);

		//var_dump($this->getSetting( 'facebookAppId' ));

		$facebook = new \Facebook( $config );

		/*$token = $this->get_app_token( $this->getSetting( 'facebookAppId' ), $this->getSetting( 'facebookSecret' ) );

		//var_dump( $token );
		$tokenArray = split( '=', $token );
		$token = $tokenArray[ 1 ];
		var_dump( $token );*/

		/*$access_token = $facebook->getAccessToken();
		var_dump($access_token);*/
		$facebook->setAccessToken($this->getSetting('facebookAccessToken'));
		
		//var_dump($facebook->api('me', 'GET'));
		//var_dump( $facebook->getUser() );
		//$page_token = 'CAACEdEose0cBAAsg2n58FVYdDsgFgTKEynL3ugEmZCVQEIiVAch21FHZByjcQTEOjZBJSlZBn8ECDTXIPSsCBGqonl68VOO99P9TA0Ob7clNS918abZCeRe9AKpVXwCnjZAo5kvJrXVhsR1fNjbTGZASSnydZBmxDVbtq5BdJ0uaQAZDZD';
		//$page_id = '1098431849';
		//Try to Publish on wall or catch the Facebook exception
		try {
			$attachment = array( 'message' => $post->getMessage(),
			    'access_token' =>  $this->getSetting('facebookAccessToken'),//'CAAGlKHVomCkBANmZB2OPdmGKwCbtSVitVT7mcQ5jZCxJB9tus1wvC0jwVvooXqLxxd4kxium58YWQXavaZCHuZA7ei2T3w398y6fkHK1dpIvpAUZBH8uYnsJkZBuc3ZCucpKVMkIv7IWROmnvjyOKGDLKBhiYZAYE77NfjXTbhQZCVAZDZD',//$access_token, //$token,
			    'name' => $post->getName(),
			    'caption' => $post->getCaption(),
			    'link' => $post->getLink(),
			    'description' => $post->getDescription(),
			    'picture' => 'http://upload.wikimedia.org/wikipedia/en/e/e0/Rock_band_cover.jpg',//$post->getPicture(),
			    'actions' => array( array( 'name' => 'Example Action Text',
				    'link' => 'http://local.maven.com' ) )
			);

			//Page id 500976746645005
			$result = $facebook->api( '/' . $this->getSetting( 'pageId') . '/feed/', 'POST', $attachment );

			//var_dump( $result );
			
			//TODO: $result return an id like this (500976746645005_501963726546307)
			//maybe we should save it or something
		}
		//If the post is not published, print error details
		catch ( \FacebookApiException $e ) {
			var_dump( $e );
		} catch ( \Exception $e ) {
			var_dump( $e );
		}
	}
	
	public function event( Event $event ) {

		$config = array(
		    'appId' => $this->getSetting( 'facebookAppId' ),
		    'secret' => $this->getSetting( 'facebookSecret' ),
		    'cookie' => false
		);

		$facebook = new \Facebook( $config );
		
		
		/*$access_token = $facebook->getAccessToken();
		var_dump($access_token);
		
		$result=$facebook->api('/me/accounts', 'GET');
		var_dump($result);*/
		
		$facebook->setAccessToken($this->getSetting('facebookAccessToken'));
		//Try to Publish on wall or catch the Facebook exception
		try {
			$attachment = array( 
			    'name' => $event->getName(),
			    'start_time' => $event->getStartTime(),
			    'end_time' => $event->getEndTime(),
			    'description' => $event->getDescription(),
			    'location' => $event->getLocation(),
			    'picture' => $event->getPicture(),
			    'ticket_uri' => $event->getTicketUri(),
			    'privacy' => 'OPEN'
			);

			//Page id 500976746645005
			$result = $facebook->api( '/' . $this->getSetting( 'pageId') . '/events/', 'POST', $attachment );

			//var_dump( $result );
			
			//TODO: $result return an id like this (500976746645005_501963726546307)
			//maybe we should save it or something
		}
		//If the post is not published, print error details
		catch ( \FacebookApiException $e ) {
			var_dump( $e );
		} catch ( \Exception $e ) {
			var_dump( $e );
		}
	}
}

