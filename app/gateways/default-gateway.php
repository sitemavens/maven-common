<?php

namespace Maven\Gateways;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description 
 *
 * @author Emiliano Jankowski
 */
class DefaultGateway extends Gateway {


	public function __construct() {
		
		parent::__construct();
		
		$this->setLiveUrl( "" );
		$this->setTestUrl( "" );
		$this->setParameterPrefix( "" );
		$this->setItemDelimiter( "" );
		$this->setName( "Default" );

	}

	/**
	 * 
	 * @param array $args 
	 * 
	 */
	public function execute() {

		$this->setError( true );
		$this->setErrorDescription( 'DefaultGateway: You don\'t have any gateway configured!' );
	}

	

	public function getFullUrl() {
		

		return "dumyUrl";
	}


	public function getAvsCode() {
		
	}

	

}
