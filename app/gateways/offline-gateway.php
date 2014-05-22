<?php

namespace Maven\Gateways;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description 
 *
 * @author Emiliano Jankowski
 */
class OfflineGateway extends \Maven\Gateways\Gateway{
	
	
	public function __construct() {
		
		parent::__construct();
		
		$this->setLiveUrl( "" );
		$this->setTestUrl( "" );
		$this->setParameterPrefix( "" );
		$this->setItemDelimiter( "" );
		$this->setName( "Offline" );
		 
	}

	public function execute(){
		$this->setApproved( true );
		$this->setHeldForReview( false);
		$this->setDeclined( false );
		$this->setError( false );
	}
	
	public function get_transaction_id() {
		return "";
	}
	

	public function getAvsCode() {
		
		return false;
	}
	
}
