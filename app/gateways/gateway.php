<?php

namespace Maven\Gateways;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of mvn-gateway-pro-abs
 *
 * @author mustela
 */
abstract class Gateway {

	private $fields = array( );
	
	/**
	 *
	 * @var \Maven\Settings\Option 
	 */
	protected $options = array( );
	private $liveUrl = "";
	private $testUrl = "";
	private $parameterPrefix = "";
	private $errorDescription = "";
	private $rawResponse = false;
	private $pluginId = false;
	private $itemDelimiter;
	protected $name;
	protected $key;
	private $testMode = true;
	private $firstName;
	private $lastName;
	private $email;
	private $ccNumber;
	private $ccHolderName;
	private $ccVerificationCode;
	private $ccMonth;
	private $ccYear;
	private $gatewayKey;
	private $notes;
	
	private $isDonation = false;
	private $isRecurring = false;
		
	/*	 * **   These properties are for CIM methods *** */
	private $manageProfile = false;
	private $customerId;
	private $customerProfileId;
	private $customerPaymentProfileId;
	private $customerShippingAddressId;

	/**
	 *
	 * @var \Maven\Core\CreditCardType
	 */
	private $ccType;
	private $address;
	private $city;
	private $state;
	private $zip;
	private $country;
	private $shipToFirstName;
	private $shipToLastName;
	private $shipToCity;
	private $shipToState;
	private $shipToZip;
	private $shipToCountry;
	private $shipToAddress;
	private $shippingAmount = 0;
	private $shippingName;
	private $shippingDescription;
	private $invoiceNumber;
	private $amount;
	private $description;
	
	private $phone;

	/**
	 *
	 * @var \Maven\Gateways\GatewayOrderItem[] 
	 */
	private $orderItems = array( );

	
	private $discountAmount=0;
	
	public function __construct( $name ) {
		
		$this->name = $name;
		$this->key = sanitize_key( $name );
		
	}

	/*	 * **   These properties are for CIM methods *** */

    public function setCustomFields ($order) {
        
    }
    
	public function getCustomerId() {
		return $this->customerId;
	}

	public function setCustomerId( $customerId ) {

		$this->customerId = $customerId;
	}

	public function getCustomerProfileId() {
		return $this->customerProfileId;
	}

	public function setCustomerProfileId( $customerProfileId ) {
		$this->customerProfileId = $customerProfileId;
	}

	public function getCustomerPaymentProfileId() {

		return $this->customerPaymentProfileId;
	}

	public function setCustomerPaymentProfileId( $customerPaymentProfileId ) {

		
		$this->customerPaymentProfileId = $customerPaymentProfileId;
	}

	public function getCustomerShippingAddressId() {
		return $this->customerShippingAddressId;
	}

	public function setCustomerShippingAddressId( $customerShippingAddressId ) {

		
		$this->customerShippingAddressId = $customerShippingAddressId;
	}

	/**
	 *  It tells if the gateway has a Profile feature.
	 * @return bool
	 */
	public function getManageProfile() {
		return $this->manageProfile;
	}

	public function setManageProfile( $manageProfile ) {
		$this->manageProfile = $manageProfile;
	}

	/*	 * ***************** */

	public function getPhone() {
		return $this->phone;
	}

	public function setPhone( $phone ) {
		$this->phone = $phone;
	}

	public function getCCHolderName() {
		return $this->ccHolderName;
	}

	public function setCCHolderName( $ccHolderName ) {
		$this->ccHolderName = $ccHolderName;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription( $description ) {
		$this->description = $description;
	}

	private $error = false;
	private $approved = false;
	private $declined = false;
	private $heldForReview = false;
	private $transactionId = '';
	private $externalRedirect = false;
	private $canceled = false;
	
		
	//TODO: We have to study all these properties again, since they aren't useful for all the gateways.
//	private $maxFailedPayments = '';
//	private $autoBillOutAmount = '';
//	private $trialBillingPeriod = '';
//	private $trialBillingFrequency = '';
//	private $trialTotalBillingCycles = '';
	private $billingPeriod = '';
	private $billingFrequency = 1;
//	private $totalBillingCycles = '';
//	private $payerStatus = '';
//	private $shipToPhonenumber = '';
//	private $failedInitAmount = '';
//	private $currencyCode = '';
//	private $isTrial = false;

	public abstract function execute();

	public function getTransactionId() {
		return $this->transactionId;
	}

	public function setTransactionId( $transactionId ) {
		$this->transactionId = $transactionId;
	}

	public function setPluginId( $pluginId ) {

		$this->pluginId = $pluginId;
	}
	
	public function getNotes() {
		return $this->notes;
	}

	public function setNotes( $notes ) {
		$this->notes = $notes;
	}


	public function isExternalRedirect() {
		return $this->externalRedirect;
	}
	
	public function isApproved() {
		return $this->approved;
	}

	public function isHeldForReview() {
		return $this->heldForReview;
	}

	public function isDeclined() {
		return $this->declined;
	}

	public function getCanceled() {
		return $this->canceled;
	}

	public function setCanceled( $canceled ) {
		$this->canceled = $canceled;
	}

	public function isError() {
		return $this->error;
	}
	
	public function setExternalRedirect( $externalRedirect ) {
		$this->externalRedirect = $externalRedirect;
	}

	public function setApproved( $approved ) {
		$this->approved = $approved;
	}

	public function setHeldForReview( $heldForReview ) {
		$this->heldForReview = $heldForReview;
	}

	public function setDeclined( $declined ) {
		$this->declined = $declined;
	}

	/**
	 * 
	 * @return string
	 */
	public function getCcType() {
		return $this->ccType;
	}

	/**
	 * 
	 * @param string $ccType
	 */
	public function setCcType( $ccType ) {
		$this->ccType = $ccType;
	}

	public abstract function getAvsCode();

	/**
	 * 
	  invoice_num
	  description
	  amount
	  email
	  first_name
	  last_name
	  address
	  city
	  state
	  zip
	  country
	  exp_month
	 *  exp_year
	 *  card_holder_name

	 * @param type $key
	 * @param type $value 
	 */
	public function setParameter( $key, $value ) {
		$this->fields[ $this->parameterPrefix . trim( $key ) ] = $value;
	}

	public function setDefaultParameter( $key, $value ) {
		if ( !isset( $this->fields[ $this->parameterPrefix . trim( $key ) ] ) )
			$this->fields[ $this->parameterPrefix . trim( $key ) ] = trim( $value );
	}

	public function getParameter( $key ) {
		return isset( $this->fields[ $this->parameterPrefix . trim( $key ) ] ) ? $this->fields[ $this->parameterPrefix . trim( $key ) ] : false;
	}

	/**
	 * Set test mode
	 * @param bool $test_mode TRUE to activate test mode or FALSE to deactivate it
	 */
	public function setTestMode( $test_mode = true ) {
		$this->testMode = $test_mode;
	}

	public function isTestMode() {
		return $this->testMode;
	}

	public function getFields() {
		return $this->fields;
	}

	public function setFields( $fields ) {
		$this->fields = $fields;
	}

	public function getLiveUrl() {
		return $this->liveUrl;
	}

	public function setLiveUrl( $liveUrl ) {
		$this->liveUrl = $liveUrl;
	}

	public function getTestUrl() {
		return $this->testUrl;
	}

	public function setTestUrl( $testUrl ) {
		$this->testUrl = $testUrl;
	}

	public function getParameterPrefix() {
		return $this->parameterPrefix;
	}

	public function setParameterPrefix( $parameterPrefix ) {
		$this->parameterPrefix = $parameterPrefix;
	}

	public function setError( $error ) {
		$this->error = $error;
	}

	public function getErrorDescription() {
		return $this->errorDescription;
	}

	public function setErrorDescription( $errorDescription ) {
		$this->errorDescription = $errorDescription;
	}

	public function getRawResponse() {
		return $this->rawResponse;
	}

	public function setRawResponse( $rawResponse ) {
		$this->rawResponse = $rawResponse;
	}

	public function getItemDelimiter() {
		return $this->itemDelimiter;
	}

	public function setItemDelimiter( $itemDelimiter ) {
		$this->itemDelimiter = $itemDelimiter;
	}

	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}

	/**
	 * 
	 * @param \Maven\Settings\Option $option
	 */
	protected function addSetting( \Maven\Settings\Option $option ) {

		$this->options[ $option->getName() ] = $option;
	}

	protected function addSettings( $options ) {

		foreach ( $options as $option ) {
			$this->addSetting( $option );
		}

		$this->syncronize();
	}
	
	protected function updateSettings( $options ) {

		foreach ( $options as $option ) {
			$this->addSetting( $option );
		}

	}

	protected function getSetting( $key ) {
		return isset( $this->options[ $key ] ) ? $this->options[ $key ]->getValue() : '';
	}

	/**
	 * Get Settings
	 * @return Maven\Settings\Option[]
	 */
	public function getSettings() {
		return $this->options;
	}

	public function getFirstName() {
		return $this->firstName;
	}

	public function setFirstName( $firstName ) {
		$this->firstName = $firstName;
	}

	public function getLastName() {
		return $this->lastName;
	}

	public function setLastName( $lastName ) {
		$this->lastName = $lastName;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail( $email ) {
		$this->email = $email;
	}

	public function getCCNumber() {
		return $this->ccNumber;
	}

	public function setCCNumber( $ccNumber ) {
		$this->ccNumber = $ccNumber;
	}

	public function getCCMonth() {
		return $this->ccMonth;
	}

	public function setCCMonth( $ccMonth ) {
		$this->ccMonth = $ccMonth;
	}

	public function getCCYear() {
		return $this->ccYear;
	}

	public function setCCYear( $ccYear ) {
		$this->ccYear = $ccYear;
	}

	public function getCCVerificationCode() {
		return $this->ccVerificationCode;
	}

	public function setCCVerificationCode( $ccVerificationCode ) {
		$this->ccVerificationCode = $ccVerificationCode;
	}

	public function getAddress() {
		return $this->address;
	}

	public function setAddress( $address ) {
		$this->address = $address;
	}

	public function getCity() {
		return $this->city;
	}

	public function setCity( $city ) {
		$this->city = $city;
	}

	public function getState() {
		return $this->state;
	}

	public function setState( $state ) {
		$this->state = $state;
	}

	public function getZip() {
		return $this->zip;
	}

	public function setZip( $zip ) {
		$this->zip = $zip;
	}

	public function getCountry() {
		return $this->country;
	}

	public function setCountry( $country ) {
		$this->country = $country;
	}

	public function getShipToFirstName() {
		return $this->shipToFirstName;
	}

	public function setShipToFirstName( $shipToFirstName ) {
		$this->shipToFirstName = $shipToFirstName;
	}

	public function getShipToLastName() {
		return $this->shipToLastName;
	}

	public function setShipToLastName( $shipToLastName ) {
		$this->shipToLastName = $shipToLastName;
	}

	public function getShipToAddress() {
		return $this->shipToAddress;
	}

	public function setShipToAddress( $shipToAddress ) {
		$this->shipToAddress = $shipToAddress;
	}

	public function getShipToCity() {
		return $this->shipToCity;
	}

	public function setShipToCity( $shipToCity ) {
		$this->shipToCity = $shipToCity;
	}

	public function getShipToState() {
		return $this->shipToState;
	}

	public function setShipToState( $shipToState ) {
		$this->shipToState = $shipToState;
	}

	public function getShipToZip() {
		return $this->shipToZip;
	}

	public function setShipToZip( $shipToZip ) {
		$this->shipToZip = $shipToZip;
	}

	public function getShipToCountry() {
		return $this->shipToCountry;
	}

	public function setShipToCountry( $shipToCountry ) {
		$this->shipToCountry = $shipToCountry;
	}

	public function getShippingAmount() {
		return $this->shippingAmount;
	}

	public function setShippingAmount( $shippingAmount ) {
		$this->shippingAmount = $shippingAmount;
	}

	public function getShippingName() {
		return $this->shippingName;
	}

	public function setShippingName( $shippingName ) {
		$this->shippingName = $shippingName;
	}

	public function getShippingDescription() {
		return $this->shippingDescription;
	}

	public function setShippingDescription( $shippingDescription ) {
		$this->shippingDescription = $shippingDescription;
	}

	public function getInvoiceNumber() {
		return $this->invoiceNumber;
	}

	public function setInvoiceNumber( $invoiceNumber ) {
		$this->invoiceNumber = $invoiceNumber;
	}

	public function getAmount() {
		return $this->amount;
	}

	public function setAmount( $amount ) {

		if ( ! is_numeric( $amount ) )
			throw new \Maven\Exceptions\GatewayException( 'Amount must be a valid number' );


		$this->amount = $amount;
	}

	/**
	 * 
	 * @param \Maven\Settings\Option[] $options
	 */
	public function saveOptions( $options ) {

		//Save the options in the WP table
		update_option( $this->getGatewayKey(), $options );

		//$this->setOptions( $options );
	}

//	public function set( $key, $value ) {
//		
//		parent::set( $key, $value);
//			
//		update_option( $this->getSettingKey(), $this->getOptions() );
//			
//	}

	private function getGatewayKey() {

		//We need to sanitize the key just one time
		if ( !$this->gatewayKey )
			$this->gatewayKey = "mvn-gateway-" . $this->getKey();

		return $this->gatewayKey;
	}
	
	public function getKey(){
		return $this->key;
		
	}

	private function syncronize() {

		// Get the options from the db
		$existingsOptions = get_option( $this->getGatewayKey() );

		// Get the saved options in the object
		$options = $this->getSettings();

		// If options exists we need to merge them with the default ones
		if ( $existingsOptions ) {
			foreach ( $existingsOptions as $option ) {
				if ( isset( $options[ $option->getName() ] ) )
					$options[ $option->getName() ]->setValue( $option->getValue() );
			}
		}

		//$this->addSettings( $options );
	}

	public function getOrderItems() {
		return $this->orderItems;
	}

	public function hasOrderItems() {
		return $this->orderItems && count( $this->orderItems ) > 0;
	}

	public function setOrderItems( $orderItems ) {
		$this->orderItems = $orderItems;
	}

	public function addOrderItem( \Maven\Gateways\GatewayOrderItem $item ) {

		$this->orderItems[ ] = $item;
	}

	public function isInvalid() {
		return ( $this->error || $this->declined );
	}
//
//	public function setMaxFailedPayments( $count ) {
//		$this->maxFailedPayments = $count;
//	}
//
//	public function getMaxFailedPayments() {
//		return $this->maxFailedPayments;
//	}
//
//	/**
//	 * (Optional) Indicates whether you would like PayPal to automatically bill the outstanding balance amount in the next billing cycle. 
//	 * The outstanding balance is the total amount of any previously failed scheduled payments that have yet to be successfully paid. 
//	 * It is one of the following values:
//	 * @param string $value [optional] <b>NoAutoBill</b>,<b>AddToNextBilling</b>
//	 */
//	public function setAutoBillOutAmount( $value ) {
//		$this->autoBillOutAmount = $value;
//	}
//
//	public function getAutoBillOutAmount() {
//		return $this->autoBillOutAmount;
//	}
//
//	/**
//	 * Unit for billing during this subscription period; 
//	 * required if you specify an optional trial period. It is one of the following values:
//	 * @param string $value [optional] Day, Week, SemiMonth, Month, Year
//	 */
//	public function setTrialBillingPeriod( $period ) {
//		$this->trialBillingPeriod = $period;
//	}
//
//	public function getTrialBillingPeriod() {
//		return $this->trialBillingPeriod;
//	}
//
//	/**
//	 * Number of billing periods that make up one billing cycle; required if you specify an optional trial period.
//	 * The combination of billing frequency and billing period must be less than or equal to one year. 
//	 * For example, if the billing cycle is Month, the maximum value for billing frequency is 12. 
//	 * Similarly, if the billing cycle is Week, the maximum value for billing frequency is 52.
//	 * Note: If the billing period is SemiMonth, the billing frequency must be 1.
//	 * @param string $frequency
//	 */
//	public function setTrialBillingFrequency( $frequency ) {
//		$this->trialBillingFrequency = $frequency;
//	}
//
//	public function getTrialBillingFrequency() {
//		return $this->trialBillingFrequency;
//	}
//
//	/**
//	 * (Optional) Number of billing cycles for trial payment period.
//	 * @param string $cycles
//	 */
//	public function setTrialTotalBillingCycles( $cycles ) {
//		$this->trialTotalBillingCycles = $cycles;
//	}
//
//	public function getTrialTotalBillingCycles() {
//		return $this->trialTotalBillingCycles;
//	}
//
	/**
	 * (Required) Unit for billing during this subscription period. It is one of the following values:
	 * @param string $period  [optional] Day, Week, SemiMonth, Month, Year
	 */
	public function setBillingPeriod( $period ) {
		$this->billingPeriod = $period;
	}

	public function getBillingPeriod() {
		return $this->billingPeriod;
	}

	/**
	 * (Required) Number of billing periods that make up one billing cycle.
	 * The combination of billing frequency and billing period must be less than or equal to one year. 
	 * For example, if the billing cycle is Month, the maximum value for billing frequency is 12. 
	 * Similarly, if the billing cycle is Week, the maximum value for billing frequency is 52.
	 * @param string $frequency
	 */
	public function setBillingFrequency( $frequency ) {
		$this->billingFrequency = $frequency;
	}

	public function getBillingFrequency() {
		return $this->billingFrequency;
	}
//
//	/**
//	 * (Optional) Number of billing cycles for payment period.
//	 * For the regular payment period, if no value is specified or the value is 0,
//	 * the regular payment period continues until the profile is canceled or deactivated.
//	 * For the regular payment period, if the value is greater than 0, 
//	 * the regular payment period will expire after the trial period is finished 
//	 * and continue at the billing frequency for TotalBillingCycles cycles.
//	 * @param string $cycles
//	 */
//	public function setTotalBillingCycles( $cycles ) {
//		$this->totalBillingCycles = $cycles;
//	}
//
//	public function getTotalBillingCycles() {
//		return $this->totalBillingCycles;
//	}
//
//	/**
//	 * (Optional) Status of buyer. It is one of the following values: 
//	 * verified, unverified
//	 * @param string $status  verified, unverified
//	 */
//	public function setPayerStatus( $status ) {
//		$this->payerStatus = $status;
//	}
//
//	public function getPayerStatus() {
//		return $this->payerStatus;
//	}
//
//	/**
//	 * (Optional) Phone number. Character length and limitations: 20 single-byte characters
//	 * @param string $number
//	 */
//	public function setShipToPhonenumber( $number ) {
//		$this->shipToPhonenumber = $number;
//	}
//
//	public function getShipToPhonenumber() {
//		return $this->shipToPhonenumber;
//	}
//
//	/**
//	 * (Optional) Initial non-recurring payment amount due immediately upon profile creation. 
//	 * Use an initial amount for enrolment or set-up fees.
//	 * @param string $amount
//	 */
//	public function setFailedInitAmount( $amount ) {
//		$this->failedInitAmount = $amount;
//	}
//
//	public function getFailedInitAmount() {
//		return $this->failedInitAmount;
//	}

	/**
	 * (Required) Currency code (default is USD).
	 * @param string $code
	 */
	public function setCurrencyCode( $code = 'USD' ) {
		$this->currencyCode = $code;
	}

	public function getCurrencyCode() {
		return $this->currencyCode;
	}

	/**
	 * 
	 * @param bool $trial
	 */
//	public function setTrial( $trial = false ) {
//		$this->isTrial = $trial;
//	}
//
//	public function getTrial() {
//		return $this->isTrial;
//	}
//	
	public function getUrl() {
		if ( $this->isTestMode() )
			return $this->getTestUrl();

		return $this->getLiveUrl();
	}
	
	/**
	 * Check if the transaction is a donation or a regular tran.
	 * @return boolean
	 */
	public function isDonation() {
		return $this->isDonation;
	}

	public function setIsDonation( $isDonation ) {
		$this->isDonation = $isDonation;
	}

	/**
	 * Check if the transaction is a recurring transation or not.
	 * @return boolean
	 */
	public function isRecurring() {
		return $this->isRecurring;
	}

	public function setIsRecurring( $isRecurring ) {
		$this->isRecurring = $isRecurring;
	}
	
	public function getRemoteIp(){
		return $_SERVER['REMOTE_ADDR'];
	}
	
	public function getDiscountAmount() {
		return $this->discountAmount;
	}

	public function setDiscountAmount( $discountAmount ) {
		$this->discountAmount = $discountAmount;
	}



}

class BillingPeriod{
	
	const Days = "d";
	const Weeks = "d";
	const Months = "m";
	const Years = "y";
	
}
