<?php

namespace Maven\Front;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class FrontEndManager {

	private $request = null;
	private $registry = null;
	private $inputKey = "mvnField";
	private static $instance;
	private static $currentStep;

	const MavenTransactionKey = "mavenTransactionKey";

	/**
	 * 
	 * @param \Maven\Settings\Registry $registry
	 * @param string $inputKey
	 */
	public function __construct() {
		$this->request = \Maven\Core\Request::current();
		$this->registry = \Maven\Settings\MavenRegistry::instance();
	}

	public static function init() {
		self::current()->manageRequest();
	}
	
	public function registerRestApi(  ){
		\Maven\Core\HookManager::instance()->addFilter( 'json_endpoints', array( $this, 'registerRouters' ) );
		
	}
	
	public function registerRouters( $routes ){
		
		$routes[ '/maven/cart/do-action' ] = array(
			array( array( $this, 'manageJsonRequest' ), \WP_JSON_Server::CREATABLE | \WP_JSON_Server::ACCEPT_JSON)
		);
		 
		return $routes;
	}

	/**
	 * 
	 * @return \Maven\Front\Step
	 */
	private static function setCurrentStep( $data = array() ) {

		if ( ! self::$currentStep ) {
			self::$currentStep = new Step( $data );
		}

		return self::$currentStep;
	}

	/**
	 * 
	 * @return \Maven\Front\FrontEndManager
	 */
	public static function current() {

		if ( ! self::$instance ) {
			self::$instance = new FrontEndManager();
		}

		return self::$instance;
	}

	/**
	 * Return the step
	 * @return \Maven\Front\Step
	 */
	public static function getCurrentStep() {

		if ( self::$currentStep ) {
			return self::$currentStep;
		}

		return false;
	}

	public function getRequest() {
		return $this->request;
	}

	public function getRegistry() {
		return $this->registry;
	}

	public function isMavenTransactionRequest() {
		$nonce = $this->request->getProperty( self::MavenTransactionKey );
		
		return ( $this->getRequest()->isPost() && wp_verify_nonce( $nonce, self::MavenTransactionKey ) );
	}

	public function getOptions() {

		$options = $this->getRequest()->getProperty( $this->inputKey );

		return $options;
	}

	/**
	 * 
	 * @return \Maven\Front\Step
	 */
	public function newStep() {
		$step = new \Maven\Front\Step( $this->getMavenTransactionRequestKey(), $this->inputKey );

		return $step;
	}

	public static function writeTransactionFields() {

		wp_nonce_field( self::MavenTransactionKey, self::MavenTransactionKey );
	}
	
	public static function getTransactionNonce() {
		return wp_create_nonce( self::MavenTransactionKey );
	}

	function manageJsonRequest( $data  ){
		
		$simulatedRequest = array();
		$simulatedRequest[self::MavenTransactionKey] = $data['transaction'];
		$simulatedRequest['mvn']['thing'] = $data['thing'];
		$simulatedRequest['mvn']['step'] = $data['step'];
		//$simulatedRequest['_wp_json_nonce'] = $data['step'];
		
		\Maven\Core\Request::simulate($simulatedRequest);
		
		$result = $this->manageRequest();
		
		if ( ! $result ){
			//\Maven\Core\Request::current()->isPost();
			$nonce = $this->request->getProperty( self::MavenTransactionKey );
		
//		return ( $this->getRequest()->isPost() && wp_verify_nonce( $nonce, self::MavenTransactionKey ) );
//		echo ($nonce);
//		echo ("\n");
//		echo (self::MavenTransactionKey);"7a7d7f25d9"
			echo(get_current_user_id());
			 //wp_verify_nonce( "123123123", "mavenTransactionKey" );
			die('fin');
		}
		
		die('paso!');
	}
	
	
	function manageRequest(  ) {
		

//die(var_dump($_POST));
		if ( ! $this->isMavenTransactionRequest() ) {
			
			return false;
		}

		$cart = \Maven\Core\Cart::current();
		$request = \Maven\Core\Request::current();

		// Do we need to add some kind of validation?
		$data = $request->getProperty( 'mvn' );

		$step = self::setCurrentStep( $data );

		//Lets fire the action
		$actionName = $step->getAction();

		$actionName = "Maven\Front\Actions\\{$actionName}Action";

		$action = new $actionName( $step, $cart, $data );

		$result = $action->execute();

		// Save the result in the step
		$step->setActionResult( $result );

		if ( $request->isDoingAjax() || $request->isDoingJSon()) {
			if ( $result->isSuccessful() ) {
				$result = array( 'successful' => true, 'error' => false, 'description' => $result->getContent(), 'data' => $result->getData() );
			} else {
				$result = array( 'successful' => false, 'error' => true, 'description' => $result->getContent() );
			}

			die( json_encode( $result ) );
		}


		if ( $result->isSuccessful() ) {
			$step->getOnComplete()->doAction();
		} else {
			$step->getOnError()->doAction();
		}



//            if ( $step->getNextStep() && !$request->exists( 'updateCartButton' ) ) {
//
//                wp_redirect( site_url( $step->getNextStep() ) );
//                exit();
//            }
	}

}
