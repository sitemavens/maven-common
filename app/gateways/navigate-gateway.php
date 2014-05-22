<?php

namespace Maven\Gateways;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

use Maven\Settings\OptionType,
	Maven\Settings\Option;

class NavigateGateway extends Gateway {

	// Manage items http://developer.authorize.net/guides/AIM/Transaction_Data_Requirements/Itemized_Order_Information.htm

	public function __construct() {

		parent::__construct();

		$this->setLiveUrl( "https://gateway.merchantplus.com/cgi-bin/PAWebClient.cgi" );
		$this->setTestUrl( "https://gateway.merchantplus.com/cgi-bin/PAWebClient.cgi" );
		$this->setParameterPrefix( "x_" );
		$this->setItemDelimiter( "|" );
		$this->setName( "Navigate" );
		
		
		$defaultOptions = array(
			new Option(
					"login",
					"Login",
					'',
					'',
					OptionType::Input
			),
			new Option(
					"transactionKey",
					"Transaction Key",
					'',
					'',
					OptionType::Input
			),
			new Option(
					"authorizationType",
					"Authorization Type",
					'',
					'',
					OptionType::DropDown
			),
			new Option(
					"delimiter",
					"Delimiter",
					'',
					'|',
					OptionType::DropDown
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


		$response = wp_remote_post( $this->getFullUrl() );

		if ( is_wp_error( $response ) ) {

			$errorDescription = "";
			foreach ( $response->errors as $key => $value ) {
				$errorDescription .= $key . ":" . $value[ 0 ] . "<br/>";
			}
			$this->setError( true );
			$this->setErrorDescription( $errorDescription );
		} else {

			$body = wp_remote_retrieve_body( $response );
			$this->setRawResponse( $body );

			$responseFields = explode( $this->getItemDelimiter(), $body );

			$this->setStatus( $responseFields );
		}
	}
	
	
	public function getFullUrl() {

		$testRequest= 'FALSE';
		
		if ( $this->isTestMode() ) 
			$testRequest =  'TRUE' ;

		$login				= $this->getSetting( "login" );
		$transactionKey		= $this->getSetting( "transactionKey" ) ;
		$autorizationType	= $this->getSetting( "authorizationType" ) ;
		
		$fields = array(
			'customer_ip'	 => $_SERVER[ "REMOTE_ADDR" ],
			'delim_char'	 => $this->getItemDelimiter(),
			'version'		 => "3.1",
			'delim_data'	 => "TRUE",   //This should be a setting
			'relay_response' => "FALSE",
			'method'		=> 'CC',
			'login'			=> $login,
			'tran_key'		=> $transactionKey,
			'type'			=> $autorizationType,
			'first_name'	=> $this->getFirstName(),
			'last_name'		=> $this->getLastName(),
			'email'			=> $this->getEmail(),
			'description'	=> $this->getDescription(),
			'card_num'		=> $this->getCCNumber(),
			'exp_date'		=> $this->getCCMonth() . $this->getCCYear(),
			'card_code'		=> $this->getCCVerificationCode(),
			'address'		=> $this->getAddress(),
			'city'			=> $this->getCity(),
			'state'			=> $this->getState(),
			'zip'			=> $this->getZip(),
			'country'		=> $this->getCountry(),
			'ship_to_first_name'	=> $this->getShipToFirstName(),
			'ship_to_last_name'	=> $this->getShipToLastName(),
			'ship_to_address'	=> $this->getShipToAddress(),
			'ship_to_city'		=> $this->getShipToCity(),
			'ship_to_state'		=> $this->getShipToState(),
			'ship_to_country'	=> $this->getShipToCountry(),
			'invoice_num'		=> $this->getInvoiceNumber(),
			'amount'		=> $this->getAmount(),
			'phone'	=> $this->getPhone(),
			'test_request'=> $testRequest
		);

		$postString = "";
		foreach ( $fields as $key => $value ) {

			if ( $value ){
				if ( !is_array( $value ) )
					$postString .= $this->getParameterPrefix ( ). "$key=" . urlencode( trim( $value ) ) . "&";
				else {

					foreach ( $value as $item ) {
						$item = implode( $this->item_delimiter, $item );

						$postString .= $this->getParameterPrefix ( )."$key=" . urlencode( trim( $item ) ) . "&";
					}
				}
			}
		}

		$postString = rtrim( $postString, "& " );

		return $this->getUrl() . "?" . $postString;
	}
	
	
	private function setStatus( $responseFields ) {

		$response = array( "", "Approved", "Declined", "Error", "Held" );

		if ( $responseFields ) {

			switch ( $response[ $responseFields[ 0 ] ] ) {
				case "Approved":
					$this->setApproved( true );
					if ( $responseFields && isset( $responseFields[ 6 ] ) && !empty( $responseFields[ 6 ] ) ) {
						$this->setTransactionId( $responseFields[ 6 ] );
					}
					else
						$this->setTransactionId( -1 );

					break;
				case "Held":
					$this->setHeldForReview( true );
					$this->setErrorDescription( $responseFields[ 3 ] );
					break;

				case "Declined":
					$this->setDeclined( true );
					$this->setErrorDescription( $responseFields[ 3 ] );
					break;

				case "Error":
					$this->setError( true );
					$this->setErrorDescription( $responseFields[ 3 ] );
					break;
			}
		}
	}

	
	public function getAvsCode() {
		
	}

}

