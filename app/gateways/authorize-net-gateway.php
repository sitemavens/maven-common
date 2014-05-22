<?php

namespace Maven\Gateways;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

use Maven\Settings\OptionType,
	Maven\Settings\Option;

/**
 * Description 
 *
 * @author Emiliano Jankowski
 */
class AuthorizeNetGateway extends Gateway {

	// Manage items http://developer.authorize.net/guides/AIM/Transaction_Data_Requirements/Itemized_Order_Information.htm
	private $login;
	private $transactionKey;
	private $autorizationType;
	private $customerLiveUrl = "https://api.authorize.net/xml/v1/request.api";
	private $customerTestUrl = "https://apitest.authorize.net/xml/v1/request.api";
	
	public function __construct() {

	
		parent::__construct();

		$this->setLiveUrl( "https://secure.authorize.net/gateway/transact.dll" );
		$this->setTestUrl( "https://test.authorize.net/gateway/transact.dll" );
		
		$this->setParameterPrefix( "x_" );
		$this->setItemDelimiter( "|" );
		$this->setName( "Authorize.net" );
		$this->setManageProfile( true );

		$defaultOptions = array(
			new Option(
					"login", "Login", '', '', OptionType::Input
			),
			new Option(
					"transactionKey", "Transaction Key", '', '', OptionType::Input
			),
			new Option(
					"authorizationType", "Authorization Type", '', '', OptionType::DropDown
			),
			new Option(
					"loginTest", "Login Test", '', '', OptionType::Input
			),
			new Option(
					"transactionKeyTest", "Transaction Key Test", '', '', OptionType::Input
			),
			new Option(
					"authorizationTypeTest", "Authorization Type Test", 'AUTH_ONLY', '', OptionType::DropDown
			),
			new Option(
					"sendEmailToCustomerFromAuth", "Send email to customer from Authorize.net", false, '', OptionType::DropDown
			),
			new Option(
					"delimiter",
					"Delimiter",
					'|',
					'',
					OptionType::Input
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

	
	
	private function getCustomerUrl() {
		if ( $this->isTestMode() )
			return $this->customerTestUrl;

		return $this->customerLiveUrl;
	}
	
	private function setLoginInformation(){
		
		if ( $this->isTestMode() ) {

			$this->login = $this->getSetting( "loginTest" );
			$this->transactionKey = $this->getSetting( "transactionKeyTest" );
			$this->autorizationType = $this->getSetting( "authorizationTypeTest" );
			
		} else {

			$this->login = $this->getSetting( "login" );
			$this->transactionKey = $this->getSetting( "transactionKey" );
			$this->autorizationType = $this->getSetting( "authorizationType" );
			
		}
	}

	public function getFullUrl() {

		$this->setLoginInformation();

		$sendEmail = $this->getSetting( 'sendEmailToCustomerFromAuth' );

		$fields = array(
			'customer_ip' => $_SERVER[ "REMOTE_ADDR" ],
			'delim_char' => "|",
			'version' => "3.1",
			'delim_data' => "TRUE", //This should be a setting
			'relay_response' => "FALSE",
			'method' => 'CC',
			'login' => $this->login,
			'tran_key' => $this->transactionKey,
			'type' => $this->autorizationType,
			'first_name' => $this->getFirstName(),
			'last_name' => $this->getLastName(),
			'email' => $this->getEmail(),
			'description' => $this->getDescription(),
			'card_num' => $this->getCCNumber(),
			'exp_date' => $this->getCCMonth() . $this->getCCYear(),
			'card_code' => $this->getCCVerificationCode(),
			'address' => $this->getAddress(),
			'city' => $this->getCity(),
			'state' => $this->getState(),
			'zip' => $this->getZip(),
			'country' => $this->getCountry(),
			'ship_to_first_name' => $this->getShipToFirstName(),
			'ship_to_last_name' => $this->getShipToLastName(),
			'ship_to_address' => $this->getShipToAddress(),
			'ship_to_city' => $this->getShipToCity(),
			'ship_to_state' => $this->getShipToState(),
			'ship_to_country' => $this->getShipToCountry(),
			'invoice_num' => $this->getInvoiceNumber(),
			'amount' => $this->getAmount(),
			'email_customer' => $sendEmail,
			'phone' => $this->getPhone()
		);

		$postString = "";
		foreach ( $fields as $key => $value ) {

			if ( $value ) {
				if ( !is_array( $value ) )
					$postString .= $this->getParameterPrefix() . "$key=" . urlencode( trim( $value ) ) . "&";
				else {

					foreach ( $value as $item ) {
						$item = implode( $this->item_delimiter, $item );

						$postString .= $this->getParameterPrefix() . "$key=" . urlencode( trim( $item ) ) . "&";
					}
				}
			}
		}

		$postString = rtrim( $postString, "& " );

		return $this->getUrl() . "?" . $postString;
	}

//	public function get_avs_code() {
//		if ( $responseFields && isset( $responseFields[ 5 ] ) )
//			return $responseFields[ 5 ];
//
//		return false;
//	}

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
	
	
	/** CIM **/
	
	/**
	 * Create a profile
	 * @return boolean
	 * @throws \Maven\Exceptions\GatewayException
	 */
	public function createProfile(){
		
		if ( ! $this->getCustomerId() )
			throw new \Maven\Exceptions\GatewayException('Customer Id can\'t be empty');
		
		//build xml to post
		$content ="<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
					"<createCustomerProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
					$this->merchantAuthenticationBlock().
						"<profile>".
							"<merchantCustomerId>".$this->getCustomerId()."</merchantCustomerId>". // Your own identifier for the customer.
							"<description></description>".
							"<email>" . $this->getEmail() . "</email>".
						"</profile>".
					"</createCustomerProfileRequest>";

		
		return $this->callCustomerApi( $content );

		
	}
	
	public function createPaymentProfile(){
		
		//build xml to post
		$content =
			"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
			"<createCustomerPaymentProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
				$this->merchantAuthenticationBlock().
				"<customerProfileId>" . $this->getCustomerProfileId() . "</customerProfileId>".
				"<paymentProfile>".
					"<billTo>".
						"<firstName>". $this->getFirstName()."</firstName>".
						"<lastName>". $this->getLastName()."</lastName>".
						"<phoneNumber>". $this->getPhone()."</phoneNumber>".
					"</billTo>".
					"<payment>".
						"<creditCard>".
							"<cardNumber>". $this->getCCNumber()."</cardNumber>".
							"<expirationDate>". $this->getCCYear()."-". $this->getCCMonth()."</expirationDate>". // required format for API is YYYY-MM
						"</creditCard>".
					"</payment>".
				"</paymentProfile>".
				"<validationMode>". ($this->isTestMode()?"testMode":"liveMode")."</validationMode>".  
			"</createCustomerPaymentProfileRequest>";
		
		return $this->callCustomerApi( $content );

	}
	
	public function createProfileTransaction(){
		//build xml to post
		$content =
			"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
			"<createCustomerProfileTransactionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
				$this->merchantAuthenticationBlock().
				"<transaction>".
					"<profileTransAuthOnly>".
					"<amount>" . $this->getAmount() . "</amount>". // should include tax, shipping, and everything.
					"<shipping>".
						"<amount>".$this->getShippingAmount()."</amount>".
						"<name>".$this->getShippingName()."</name>".
						"<description>".$this->getShippingDescription()."</description>".
					"</shipping>";
		
		if ( $this->hasOrderItems() ){
			foreach( $this->getOrderItems() as $item ){
					$content .="<lineItems>".
									"<itemId>".$item->getItemId()."</itemId>".
									"<name>".$item->getName()."</name>".
									"<description>".$item->getDesription()."</description>".
									"<quantity>".$item->getQuantity()."</quantity>".
									"<unitPrice>".$item->getUnitPrice()."</unitPrice>".
									"<taxable>".$item->getTaxable()."</taxable>".
								"</lineItems>";
			}
		}
		
		$content .="<customerProfileId>" . $this->getCustomerProfileId() . "</customerProfileId>".
					"<customerPaymentProfileId>" . $this->getCustomerPaymentProfileId() . "</customerPaymentProfileId>".
					"<customerShippingAddressId>" . $this->getCustomerShippingAddressId() . "</customerShippingAddressId>".
					"<order>".
						"<invoiceNumber>" . $this->getInvoiceNumber() . "</invoiceNumber>".
						"<description>" . $this->getDescription() . "</description>".
					"</order>".
					"</profileTransAuthOnly>".
				"</transaction>".
			"</createCustomerProfileTransactionRequest>";
		
		return $this->callCustomerApi( $content );
		
	}
	
	public function createShippingAddress(){
		
		//build xml to post
		$content =
			"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
			"<createCustomerShippingAddressRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
				$this->merchantAuthenticationBlock().
				"<customerProfileId>" . $this->getCustomerProfileId() . "</customerProfileId>".
					"<address>".
						"<firstName>" . $this->getFirstName() . "</firstName>".
						"<lastName>" . $this->getLastName() . "</lastName>".
						"<phoneNumber>" . $this->getPhone() . "</phoneNumber>".
					"</address>".
			"</createCustomerShippingAddressRequest>";

		return $this->callCustomerApi( $content );
	}
	
	public function removeProfile(){
		//build xml to post
		$content =
			"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
				"<deleteCustomerProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
				$this->merchantAuthenticationBlock().
					"<customerProfileId>" . $this->getCustomerId(). "</customerProfileId>".
				"</deleteCustomerProfileRequest>";
		
		
		return $this->callCustomerApi( $content );

	}
	
	
	private function callCustomerApi( $content ){
		
		$response = wp_remote_post( $this->getCustomerUrl(),
										array(
								'method' => 'POST',
								'timeout' => 45,
								'redirection' => 5,
								'httpversion' => '1.0',
								'headers' => array(
									'Content-Type' => 'text/xml'
								),
								'body' => $content,
								'sslverify' => false
							) );
		
		
		if ( is_wp_error( $response ) ) {

			$errorDescription = "";
			foreach ( $response->errors as $key => $value ) {
				$errorDescription .= $key . ":" . $value[ 0 ] . "<br/>";
			}
			$this->setError( true );
			$this->setErrorDescription( $errorDescription );
			
			return false;
			
		} else {

			$body = wp_remote_retrieve_body( $response );
			$this->setRawResponse( $body );

			$responseFields = $this->parseApiResponse( $body );
			//TODO: Ver que responde.
			var_dump($responseFields);
			
			return true;
		}
		
	}
	
	
	
	private function parseApiResponse($content)
	{
		$parsedresponse = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOWARNING);
		if ("Ok" != $parsedresponse->messages->resultCode) {
			echo "The operation failed with the following errors:<br>";
			foreach ($parsedresponse->messages->message as $msg) {
				echo "[" . htmlspecialchars($msg->code) . "] " . htmlspecialchars($msg->text) . "<br>";
			}
			echo "<br>";
		}
		return $parsedresponse;
	}
	
	private function merchantAuthenticationBlock() {
			
		$this->setLoginInformation();
		
			return
				"<merchantAuthentication>".
					"<name>" . $this->login . "</name>".
					"<transactionKey>" . $this->transactionKey . "</transactionKey>".
				"</merchantAuthentication>";
	}

}

//http://phpfour.com/blog/2009/02/php-payment-gateway-library-for-paypal-authorizenet-and-2checkout/