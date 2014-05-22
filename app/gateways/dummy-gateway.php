<?php

namespace Maven\Gateways;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

use Maven\Settings\OptionType,
    Maven\Settings\Option;

/**
 * Description 
 *
 * @author Emiliano Jankowski
 */
class DummyGateway extends Gateway {

	public function __construct() {

		parent::__construct();

		$this->setLiveUrl( "" );
		$this->setTestUrl( "" );
		$this->setParameterPrefix( "" );
		$this->setItemDelimiter( "" );
		$this->setName( "Dummy" );
		$this->setManageProfile( true );

		$defaultOptions = array(
		    new Option(
			    "recurringEnabled", "Recurring Enabled", true, '', OptionType::CheckBox
		    )
		);

		$this->addSettings( $defaultOptions );
	}

	/**
	 * 
	 * @param array $args 
	 * 
	 */
	public function execute() {

		$option = 1; //rand( 1, 4 );

		switch ( $option ) {
			case 1:
				$this->setApproved( true );

				//$this->setTransactionId( rand( 9999, 99999 ) );
				$this->setTransactionId( sprintf( "%10d", rand( 0, 9999999999 ) ) );
				//$this->setTransactionId( rtrim( base64_encode( md5( microtime() ) ), "=" ) );
				break;
			case 2:
				$this->setHeldForReview( true );
				$this->setErrorDescription( 'DummyGateway: Held for review' );
				break;

			case 3:
				$this->setDeclined( true );
				$this->setErrorDescription( 'DummyGateway: The cc was declined' );
				break;

			case 4:
				$this->setError( true );
				$this->setErrorDescription( 'DummyGateway: There was an error! But don\'t know how to fix it!' );
				break;
		}
	}

	public function getFullUrl() {


		return "dumyUrl";
	}

	public function getAvsCode() {
		
	}

}
