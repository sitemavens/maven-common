<?php

namespace Maven\Gateways;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

use Maven\Settings\OptionType,
	Maven\Settings\Option;


/**
 *
 */
class IntuitGateway extends Gateway {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->setLiveUrl( 'https://webmerchantaccount.quickbooks.com/j/AppGateway' );
		$this->setTestUrl( 'https://webmerchantaccount.ptc.quickbooks.com/j/AppGateway' );
		$this->setParameterPrefix( "" );
		$this->setItemDelimiter( "" );
		$this->setName( "Intuit" );
		$this->setManageProfile( true );
		
		$defaultOptions = array(
			new Option(
				"appLogin", "Aplication Login", 'gateway2.gunawanroy.com', '', OptionType::Input
			),
			new Option(
				"connectionTicket", "Connection Ticket", 'SDK-TGT-136-Pt2Bc4pd5KOBoCG__4QDkw', '', OptionType::Input
			),
			new Option(
				"authorizationType", "Authorization Type", 'AUTH_ONLY', '', OptionType::DropDown
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
		
		require_once __DIR__.'/../libs/QB/QuickBooks.php';
		
		
		//use below for login and transaction key test
		$application_login = $this->getSetting( 'appLogin' );
		$connection_ticket = $this->getSetting( 'connectionTicket' ); 
			
		
		$MS = new \QuickBooks_MerchantService(
			null,null, 
			$application_login,
			$connection_ticket);
		
		$MS->useTestEnvironment($this->isTestMode());
		$MS->useDebugMode(false);
		
//		$api = new \QuickBooks_API();
//			
//		$customer = new \QuickBooks_Object_Customer();
//			$ttt->add($Context, $realmID, $Object);
			
		$name = $this->getCCHolderName();
		$number = $this->getCCNumber();
		$expyear = $this->getCCYear();
		$expmonth = $this->getCCMonth();
		$address = $this->getAddress();
		$postalcode = $this->getZip();
		$cvv = $this->getCCVerificationCode();
		
		$Card = new \QuickBooks_MerchantService_CreditCard($name, $number, $expyear, $expmonth, $address, $postalcode, $cvv);
		
		//Amount we want to charge
		$amount = $this->getAmount();
		
			
		//******************************************************
		switch ($this->getSetting( 'authorizationType' )) {
			case 'AUTH_ONLY' :
				$transaction = $MS->authorize($Card, $amount, null, $this->getDescription() );
				
				if ( $transaction ){
			
					if ( $this->isRecurring() ){
			
						$customer = new \QuickBooks_IPP_Object_Customer();
			
						//$MS->addWallet($customerID, $Card);
					}
					
					//Do something with array here, store into database for later use.
					$this->setApproved( true );
					$this->setTransactionId($transaction->getTransactionID());
					
				}else{
					$this->setError( true );
					$this->setErrorDescription('An error occured during authorization: ' . $MS->errorNumber() . ': ' . $MS->errorMessage());
				}
				
				break;
			
			case 'AUTH_CAPTURE' :  
				if ($transaction = $MS->authorize($Card, $amount)){
					
					//Do something with array here, we could skip
					if ($Transaction = $MS->capture($Transaction, $amount)){
						
						$this->setApproved( true );
						$this->setTransactionId($transaction->getTransactionID());
					
//						$customerID = $str['ClientTransId'];
//						$walletID = $MS->addWallet($customerID, $Card);
					} else {
						$this->setError( true );
						$this->setErrorDescription('An error occured during capture: ' . $MS->errorNumber() . ': ' . $MS->errorMessage());
					}
				} else {
					$this->setError( true );
					$this->setErrorDescription('An error occured during authorization: ' . $MS->errorNumber() . ': ' . $MS->errorMessage());
				}
				$this->setApproved( true );
				$this->setTransactionId($str['CreditCardTransID']);
				break;
		}
			
		
		//******************************************************
	}
	
			

	public function getFullUrl() {
		return "dumyUrl";
	}

	public function getAvsCode() {
		
	}
	
	public function setTransaction($variables){
		$this->transaction = $variables;
	}
}
