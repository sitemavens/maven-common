<?php

namespace Maven\Core;

if ( !defined( 'ABSPATH' ) )
	exit;

class MandrillManager {

	private $apiUrl = "https://mandrillapp.com/api/1.0/messages/search.json";

	public function __construct () {
		;
	}

	public function isActive () {
		return !Utils::isEmpty( \Maven\Settings\MavenRegistry::getMandrillApiKey() );
	}

	public function getMessages ( $email ) {

		$apiKey = \Maven\Settings\MavenRegistry::instance()->getMandrillApiKey();
		if ( !$apiKey ) {
			return;
		}

		$content = array(
			"api_keys" => "",
			"date_from" => "",
			"date_to" => "",
			"key" => $apiKey,
			"limit" => "",
			"query" => "email:{$email}",
			"senders" => "",
			"tags" => ""
		);

		$content = json_encode( $content );

		$httpOptions = array(
			'method' => 'POST',
			'header' => 'Content-type: application/x-www-form-urlencoded'
		);

		if ( $content ) {
			$httpOptions[ 'header' ] = 'Content-type: application/json; charset=UTF-8' . PHP_EOL .
					'Content-Length: ' . strlen( $content ) . PHP_EOL;
			$httpOptions[ 'content' ] = $content;
		}

		$opts = array( 'http' =>
			$httpOptions
		);

		$context = stream_context_create( $opts );

		$response = file_get_contents( $this->apiUrl, false, $context );

		$response = json_decode( $response, true );
		
		$timeZone = \Maven\Settings\MavenRegistry::instance()->getTimeZone();
		
		foreach( $response as &$message ){
			
			$dtNow = new \DateTime();
			
			// Set a non-default timezone if needed
			$dtNow->setTimezone($timeZone);
		
			// Since clover seems to be runed with java, we need to convert the miliseconds to seconds
			$dtNow->setTimestamp($message['ts']);

			$message['ts'] = $dtNow->format("m/d/Y H:i:s");
		
		}
		return $response;


		/*
		  api_keys: null
		  date_from: null
		  date_to: null
		  key: "rDGP0Ehz1BDZncGMR-2MKA"
		  limit: 100
		  query: "email:elbiguasecretaria@gmail.com"
		  senders: null
		  tags: null

		 */
	}

}
