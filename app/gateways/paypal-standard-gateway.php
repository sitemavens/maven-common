<?php

namespace Maven\Gateways;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

use Maven\Settings\OptionType,
	Maven\Settings\Option;

/**
 * Paypal Standar checkout 
 * @link https://developer.paypal.com/webapps/developer/docs/classic/button-manager/integration-guide/NVP/ButtonMgrOverview/ 
 * @author Emiliano Jankowski
 */
class PaypalStandardGateway extends Gateway implements iExternalProcess {

	private $returnUrl;
	private $cancelUrl;
	private $notifyUrl;
	
		
	public function __construct() {

		parent::__construct();

		$this->setLiveUrl( "https://www.paypal.com/cgi-bin/webscr" );
		$this->setTestUrl( "https://www.sandbox.paypal.com/cgi-bin/webscr" );

		$this->setName( "PaypalStandard" );
		$this->setManageProfile( false );

		$defaultOptions = array(
			new Option(
					"email", "Email", '', '', OptionType::Input
			)
		);

		$this->addSettings( $defaultOptions );
	}
	
	
	public function getNotifyUrl() {
		return $this->notifyUrl;
	}

	public function setNotifyUrl( $notifyUrl ) {
		$this->notifyUrl = $notifyUrl;
	}

	
	public function getReturnUrl() {
		return $this->returnUrl;
	}

	public function setReturnUrl( $returnUrl ) {
		$this->returnUrl = $returnUrl;
	}

	public function getCancelUrl() {
		return $this->cancelUrl;
	}

	public function setCancelUrl( $cancelUrl ) {
		$this->cancelUrl = $cancelUrl;
	}
	
	
	public function isExternalTransaction(){
		
		$request = \Maven\Core\Request::current();
		
		return $request->exists( 'txn_type' );
			
	}
	
	
	/**
	 * @link https://cms.paypal.com/uk/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_html_formbasics
	 * @link https://cms.paypal.com/uk/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_html_Appx_websitestandard_htmlvariables 
	 */
	public function doExternalPost() {
		
		//How recurring work https://cms.paypal.com/uk/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_html_subscribe_buttons#id08ADF600ZPM
		
		
		$command = "_xclick";
		
		if ( $this->isDonation() )
			$command = '_donations';
		elseif ( $this->isRecurring() )
			$command = '_xclick-subscriptions';
		
		
		
		//		D – for days; allowable range for p3 is 1 to 90
		//		W – for weeks; allowable range for p3 is 1 to 52
		//		M – for months; allowable range for p3 is 1 to 24
		//		Y – for years; allowable range for p3 is 1 to 5
		
		$t3 = "D";
		
		switch( $this->getBillingPeriod() ){
			
			case BillingPeriod::Days:
				$t3 = "D";
				break;
			case BillingPeriod::Weeks:
				$t3 = "W";
				break;
			case BillingPeriod::Months:
				$t3 = "M";
				break;
			case BillingPeriod::Years:
				$t3 = "Y";
				break;
		}
		
		$p3 = $this->getBillingFrequency();
		
		 
		?>

		<html>
			<head>
				<title>Papal Standard helper page</title>
			</head>
			<body OnLoad="OnLoadEvent();" style="display:none;">
				<form name="theForm" method="POST" action="<?php echo $this->getUrl(); ?>" >
					<input type="hidden" name="amount" id="amount" value="<?php echo $this->getAmount(); ?>">
					<input type="hidden" name="business" id="business" value="<?php echo $this->getSetting( 'email' ); ?>">
					<input type="hidden" name="cmd" value="<?php echo $command ?>">
					
					<?php if ( $this->isRecurring() ): ?>
					
					<!-- Recurring --> 
					<input type="hidden" name="a3" id="a3" value="<?php echo $this->getAmount(); ?>" />
					<input type="hidden" name="t3" id="a3" value="<?php echo $t3; ?>" />
					<input type="hidden" name="p3" id="p3" value="<?php echo $p3; ?>"/>
					
					<?php endif; ?>
					
					<input type="hidden" name="item_name" value="<?php echo $this->getDescription(); ?>">  
					<input type="hidden" name="item_number" value="<?php echo $this->getInvoiceNumber(); ?>">  
					<input type="hidden" name="first_name" value="<?php echo $this->getFirstName(); ?>">  
					<input type="hidden" name="last_name" value="<?php echo $this->getLastName(); ?>">  
					<input type="hidden" name="address1" value="<?php echo $this->getAddress(); ?>">  
					<input type="hidden" name="city" value="<?php echo $this->getCity(); ?>">  
					<input type="hidden" name="state" value="<?php echo $this->getState(); ?>">  
					<input type="hidden" name="zip" value="<?php echo $this->getZip(); ?>">  
					<input type="hidden" name="night_phone_a" value="<?php echo $this->getPhone(); ?>">  
					<input type="hidden" name="email" value="<?php echo $this->getEmail(); ?>">  
					<input type="hidden" name="rm" value="2">  
					<input type="hidden" name="cancel_return" value="2">  
					<input type="hidden" name="no_shipping" value="1">
<!--				<input type="hidden" name="currency_code" value="">-->
					<input type="hidden" name="cancel_url" value="<?php echo $this->getCancelUrl(); ?>">
					<input TYPE="hidden" name="notify_url" value="<?php echo $this->getNotifyUrl(); ?>">
					<input type="hidden" NAME="return" value="<?php echo $this->getReturnUrl(); ?>">
					<input type="hidden" name="no_note" value="1">
<!--					<input TYPE="hidden" name="address_override" value="1">
						
					-->
					<noscript>
					<br/><br/>
					<div align="center">,
						<h1>Processing your Paypal Standar transaction</h1>,
						<h2>JavaScript is currently disabled or is not supported by your browser.</h2><br/>,
						<h3>Please click Submit to continue the processing your secure transaction.</h3><br/>,
						<input type="submit" value="Submit"/>
					</div>
					</noscript>
				</form>
				<script language="Javascript">
				<!--
					 function OnLoadEvent()
				{
					// Make the form post as soon as it has been loaded.
					document.theForm.submit();
				}
				// -->
				</script>
			</body>
		</html>
		

		<?php
		exit();
	}

	public function executeExternalCall(){
		// https://developer.paypal.com/webapps/developer/applications/ipn_simulator
		
		$request = \Maven\Core\Request::current();
		$this->setInvoiceNumber( $request->getProperty( 'item_number' ) );
			
		// STEP 1: Read POST data
		// reading posted data from directly from $_POST causes serialization 
		// issues with array data in POST
		// reading raw POST data from input stream instead. 
		$raw_post_data = file_get_contents( 'php://input' );
		$raw_post_array = explode( '&', $raw_post_data );
		$myPost = array( );
		foreach ( $raw_post_array as $keyval ) {
			$keyval = explode( '=', $keyval );
			if ( count( $keyval ) == 2 )
				$myPost[ $keyval[ 0 ] ] = urldecode( $keyval[ 1 ] );
		}
		
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';
		if ( function_exists( 'get_magic_quotes_gpc' ) ) {
			$get_magic_quotes_exists = true;
		}
		foreach ( $myPost as $key => $value ) {
			if ( $get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1 ) {
				$value = urlencode( stripslashes( $value ) );
			} else {
				$value = urlencode( $value );
			}
			$req .= "&$key=$value";
		}


		// STEP 2: Post IPN data back to paypal to validate
		// 'https://www.paypal.com/cgi-bin/webscr' 
		$ch = curl_init( $this->getUrl() );
		curl_setopt( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $req );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 1 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
		curl_setopt( $ch, CURLOPT_FORBID_REUSE, 1 );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Connection: Close' ) );

		// In wamp like environments that do not come bundled with root authority certificates,
		// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path 
		// of the certificate as shown below.
		// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
		if ( !($res = curl_exec( $ch )) ) {
			// error_log("Got " . curl_error($ch) . " when processing IPN data");
			curl_close( $ch );
			exit;
		}
		curl_close( $ch );

		// STEP 3: Inspect IPN validation result and act accordingly
		if ( strcmp( $res, "VERIFIED" ) == 0 ) {
			
			// check whether the payment_status is Completed
			// check that txn_id has not been previously processed
			// check that receiver_email is your Primary PayPal email
			// check that payment_amount/payment_currency are correct
			// process payment
			// assign posted variables to local variables
			//$payment_status = $_POST[ 'payment_status' ];
			 
			if ( $request->exists( 'ipn_track_id') ){
				$this->setTransactionId( $request->getProperty( 'ipn_track_id' ) );
				$this->setNotes( 'IPN Track ID');
			}
			else
				$this->setTransactionId( $request->getProperty( 'txn_id' ) );
			
			$this->setNotes( $request->getProperty( 'memo' ) );
			$this->setApproved( true );
			
			if ( $request->exists( 'subscr_id' ) )
				$this->setCustomerId ( $request->getProperty( 'subscr_id' ) );
			
			
		} else if ( strcmp( $res, "INVALID" ) == 0 ) {
			
			$this->setError( true );
			$this->setErrorDescription( 'INVALID' );
		}
	}
	
	
	/**
	 * 
	 * @param array $args 
	 * 
	 */
	public function execute() {

		$this->setExternalRedirect( true );
		
	}

	

	public function getAvsCode() {
		
	}

}



/*
  Thank you response: http://local.whiteboardmedia.com.ar/maven/donations/entry-point?
 
 * mc_gross=4.00&
 * protection_eligibility=Ineligible&
 * address_status=confirmed&
 * payer_id=CMGN7ZGZBZRHN&
 * tax=0.00&
 * address_street=1+Main+St&payment_date=07%3A00%3A03+May+22%2C+2013+PDT&
 * payment_status=Pending&
 * charset=windows-1252&
 * address_zip=95131&
 * first_name=Emiliano&address_country_code=US&
 * address_name=Emiliano+JankowskiTesting&
 * notify_version=3.7&
 * custom=&
 * payer_status=verified&
 * address_country=United+States&
 * address_city=San+Jose&
 * quantity=0&
 * payer_email=emiliano%40dinkuminteractive.com&
 * verify_sign=A7MQhub9uzfw0qPFxBsPJhQVy1g3APn1uAnT3i54Ly9HHXgWv9MkPipu&txn_id=8KC89714HD6077233&
 * payment_type=instant&
 * last_name=JankowskiTesting&address_state=CA&
 * receiver_email=emi%40emi.com&
 * pending_reason=unilateral&
 * txn_type=web_accept&
 * item_name=Donation+-+Public%3A++-+Emiliano+Jankowski&
 * mc_currency=USD&item_number=34&
 * residence_country=US&test_ipn=1&
 * transaction_subject=Donation+-+Public%3A++-+Emiliano+Jankowski&
 * payment_gross=4.00&
 * merchant_return_link=Return+to+donations+coordinator&
 * auth=ADV6GmnLcJuruprHEBDeMMHsKq3wbOCNvsrkOnbYfLn3SUDd78X6FgsG1StuPxPydfuFUFBWNbeVr9ZhV09GTGg
 */



/* Thank you post response */

//a:40:{s:8:"mc_gross";s:4:"1.00";s:22:"protection_eligibility";s:8:"Eligible";s:14:"address_status";s:9:"confirmed";s:8:"payer_id";s:13:"CMGN7ZGZBZRHN";s:3:"tax";s:4:"0.00";s:14:"address_street";s:9:"1 Main St";s:12:"payment_date";s:25:"13:42:37 May 22, 2013 PDT";s:14:"payment_status";s:9:"Completed";s:7:"charset";s:12:"windows-1252";s:11:"address_zip";s:5:"95131";s:10:"first_name";s:8:"Emiliano";s:6:"mc_fee";s:4:"0.33";s:20:"address_country_code";s:2:"US";s:12:"address_name";s:25:"Emiliano JankowskiTesting";s:14:"notify_version";s:3:"3.7";s:6:"custom";s:0:"";s:12:"payer_status";s:8:"verified";s:8:"business";s:30:"business@dinkuminteractive.com";s:15:"address_country";s:13:"United States";s:12:"address_city";s:8:"San Jose";s:8:"quantity";s:1:"0";s:11:"payer_email";s:30:"emiliano@dinkuminteractive.com";s:11:"verify_sign";s:56:"AFcWxV21C7fd0v3bYYYRCpSSRl31A8abIJXzipl0TZ3teK-gvVNnEnZ4";s:6:"txn_id";s:17:"82S59901K1779632E";s:12:"payment_type";s:7:"instant";s:9:"last_name";s:16:"JankowskiTesting";s:13:"address_state";s:2:"CA";s:14:"receiver_email";s:30:"business@dinkuminteractive.com";s:11:"payment_fee";s:4:"0.33";s:11:"receiver_id";s:13:"X7ZXGZGJ2SVY8";s:8:"txn_type";s:10:"web_accept";s:9:"item_name";s:40:"Donation - Public:  - Emiliano Jankowski";s:11:"mc_currency";s:3:"USD";s:11:"item_number";s:2:"44";s:17:"residence_country";s:2:"US";s:8:"test_ipn";s:1:"1";s:19:"transaction_subject";s:40:"Donation - Public:  - Emiliano Jankowski";s:13:"payment_gross";s:4:"1.00";s:20:"merchant_return_link";s:40:"Return to Business Testing\'s Test Store";s:4:"auth";s:87:"ACgvI5IKDkooxcnYsYdUe1vhDqdxln1oHnvpliIDR9iyuZGNsbesr6op0fZuxjFBsgdzmWX9tbx9D6AImOKpCKg";}

/* Response from a recurring subscription 
 * 

array (size=29)
  'transaction_subject' => string 'Donation - Public:  - Pop Purru' (length=31)
  'payment_date' => string '12:23:12 May 27, 2013 PDT' (length=25)
  'txn_type' => string 'subscr_payment' (length=14)
  'subscr_id' => string 'I-332GKG8JTC0S' (length=14)
  'last_name' => string 'JankowskiTesting' (length=16)
  'residence_country' => string 'US' (length=2)
  'item_name' => string 'Donation - Public:  - Pop Purru' (length=31)
  'payment_gross' => string '1.00' (length=4)
  'mc_currency' => string 'USD' (length=3)
  'business' => string 'business@dinkuminteractive.com' (length=30)
  'payment_type' => string 'instant' (length=7)
  'protection_eligibility' => string 'Ineligible' (length=10)
  'verify_sign' => string 'AOc7QvH5jmKbnG7LTYGo-scDZ7dbAepG8xriYiybG7lq2Uatnv.8N19M' (length=56)
  'payer_status' => string 'verified' (length=8)
  'test_ipn' => string '1' (length=1)
  'payer_email' => string 'emiliano@dinkuminteractive.com' (length=30)
  'txn_id' => string '2DR71462F3446343X' (length=17)
  'receiver_email' => string 'business@dinkuminteractive.com' (length=30)
  'first_name' => string 'Emiliano' (length=8)
  'payer_id' => string 'CMGN7ZGZBZRHN' (length=13)
  'receiver_id' => string 'X7ZXGZGJ2SVY8' (length=13)
  'item_number' => string '63' (length=2)
  'payment_status' => string 'Completed' (length=9)
  'payment_fee' => string '0.33' (length=4)
  'mc_fee' => string '0.33' (length=4)
  'mc_gross' => string '1.00' (length=4)
  'charset' => string 'windows-1252' (length=12)
  'notify_version' => string '3.7' (length=3)
  'ipn_track_id' => string 'a87692dc60a51' (length=13)

 * 
 */




/**
 * 
 array (size=24)
  'txn_type' => string 'subscr_signup' (length=13)
  'subscr_id' => string 'I-XJMNEVSXN5CM' (length=14)
  'last_name' => string 'JankowskiTesting' (length=16)
  'residence_country' => string 'US' (length=2)
  'mc_currency' => string 'USD' (length=3)
  'item_name' => string 'Donation - Public:  - Emiliano Jankowski' (length=40)
  'business' => string 'business@dinkuminteractive.com' (length=30)
  'amount3' => string '1.00' (length=4)
  'recurring' => string '0' (length=1)
  'verify_sign' => string 'A6updWaEoKI72YOOvdemPxQEf0H4A18uwXPsKi5nmuaxTd3-veQ8U1Yi' (length=56)
  'payer_status' => string 'verified' (length=8)
  'test_ipn' => string '1' (length=1)
  'payer_email' => string 'emiliano@dinkuminteractive.com' (length=30)
  'first_name' => string 'Emiliano' (length=8)
  'receiver_email' => string 'business@dinkuminteractive.com' (length=30)
  'payer_id' => string 'CMGN7ZGZBZRHN' (length=13)
  'reattempt' => string '1' (length=1)
  'item_number' => string '68' (length=2)
  'subscr_date' => string '12:59:57 May 27, 2013 PDT' (length=25)
  'charset' => string 'windows-1252' (length=12)
  'notify_version' => string '3.7' (length=3)
  'period3' => string '1 D' (length=3)
  'mc_amount3' => string '1.00' (length=4)
  'ipn_track_id' => string '374558d536ce2' (length=13)

 */