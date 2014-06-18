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

		parent::__construct("Dummy");

		$this->setLiveUrl( "" );
		$this->setTestUrl( "" );
		$this->setParameterPrefix( "" );
		$this->setItemDelimiter( "" );
		$this->setManageProfile( true );

		$responseType = new Option("responseType", "Response Type", true, '', OptionType::DropDown);
		$responseType->setOptions( array(
			array('id'=>'random' , 'name'=> 'Random'),
			array('id'=>'approved' , 'name'=> 'Approved',
			array('id'=>'error' , 'name'=> 'Error')
		)));
		
		$defaultOptions = array(
//		    new Option(
//			    "recurringEnabled", "Recurring Enabled", true, '', OptionType::CheckBox
//		    ),
			$responseType
		);

		$this->addSettings( $defaultOptions );
	}
	
	private function getResponseType(){
		
		$responseType =  $this->getSetting('responseType')?$this->getSetting('responseType'):'random';
		
		switch( $responseType ){
			case 'random':
				return rand( 1, 4 );
			case 'approved':
				return 1;
			case 'error':
				return 4;
		}
	}

	/**
	 * 
	 * @param array $args 
	 * 
	 */
	public function execute() {

		$option = $this->getResponseType(); 

		switch ( $option ) {
			case 1:
				$this->setApproved( true );

				$this->setTransactionId( sprintf( "%10d", rand( 0, 9999999999 ) ) );
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
