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
	private static function current() {

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

	function manageRequest() {


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

		if ( $request->isDoingAjax() ) {
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
