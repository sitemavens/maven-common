<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class WpActionController implements ActionController, ActionControllerObservable {

	/**
	 *
	 * @var type
	 */
	private $observers = array( );

	/**
	 *
	 * @var type ∫
	 */
	private $request = null;
	private $currentComponent;
	private $currentController;

	/**
	 *
	 * @var \Maven\Core\ComponentManager 
	 */
	private $componentManager;

	/**
	 *
	 * @var \Maven\Core\HookManager 
	 */
	private $hookManager;

	/**
	 *
	 * @var \Maven\Core\ResourceManager
	 */
	private $resourceManager;

	/**
	 *
	 * @param \Maven\Core\ComponentManager  $componentManager 
	 * @param \Maven\Core\HookManager		$hookManager
	 */
	public function __construct( \Maven\Core\ComponentManager $componentManager, \Maven\Core\HookManager $hookManager, \Maven\Core\ResourceManager $resourceManager ) {

		$this->request = new \Maven\Core\Request();

		$this->componentManager = $componentManager;
		$this->hookManager = $hookManager;
		$this->resourceManager = $resourceManager;

		$this->hookManager->addInit( array( $this, 'validRequest' ) );


		/**
		 * We need to check if it is a post (for instance a save from a form), in order 
		 * to handle the action. 
		 * If it is a post, we have to hook the handleRequest funtion to the init wp action, 
		 * we we can have all the wp components availables.
		 */
		if ( $this->request->isPost() ) {
			$this->hookManager->addInit( array( $this, 'handleRequestInit' ) );

			$this->hookManager->addAdminInit( array( $this, 'handleRequestAdminInit' ) );
		}
	}

	private function isDoingAjax() {

		return $this->request->isDoingAjax();
	}

	function validRequest() {

		if ( $this->currentComponent ) {
			return true;
		}

		if ( !$this->componentManager ) {
			throw new \Exception( "No component manager setted" );
		}



		if ( $this->isDoingAjax() ) {
			$key = $this->request->getProperty( 'component' );
		} else {
		//Let's see if it is a regular request
			$key = $this->request->getProperty( 'page' );
		}

		if ( !$key ) {
			return false;
		}

		// Is there any registered component for the current page?
		$component = $this->componentManager->getComponent( $key );

		if ( !$component ) {
			return false;
		}

		//Let people know that there is a component trying to do something
		$this->notify( $component );

		$this->currentComponent = $component;


		//Register the ajax actions 
		$actions = $component->getAjaxActions();
		foreach ( $actions as $action ) {
			$this->hookManager->addAjaxAction( $action->getName(), array( &$this, 'mavenHandleRequest' ) );
		}

		$backboneBaseUrl = $component->getRegistry()->getPluginUrl()."admin/assets/js/".  $component->getUrl()."/";
		// We have to localize the component, so we can validate wich component is calling
		$this->resourceManager->addLocalizedScript( 'maven', 'Maven', 
													array( 
														'component' => $component->getKey(), 
														'requireBaseUrl'=>$backboneBaseUrl,
														'translations'=>$component->getTranslations(),
														'errorMessages'=>$component->getErrorMessages()
														));

		return true;
	}

	/**
	 *  This function is used for ajax. It doen's have any other purpose. Just be a unique name
	 */
	public function mavenHandleRequest() {

		$this->handleRequest();
	}

	function handleRequestAdminInit() {

		if ( ! $this->validRequest() )
			return;

		if ( $this->currentComponent->isAdmin() )
			$this->handleRequest();
	}

	function handleRequestInit() {

		if ( ! $this->validRequest() )
			return;

		if ( ! $this->currentComponent->isAdmin() )
			$this->handleRequest();
	}

	private $redirect = false;

	function handleRequest() {

		$component = $this->currentComponent;

		if ( ! $component )
			return;

		/*
		 * This handleRequest is executed 2 times in a POST. The first one is when it is called within a handleRequestAdminInit or handleRequestInit
		 * and then, from the menu handler. So, the first time, it will fire the action it self, for instance "save", and then, when the menu execute 
		 * the handler, it will call the component default action.
		 */
		if ( $this->redirect ) {
			$urlAction = $this->getUrlAction();
			if ( ! $urlAction )
				$urlAction = $component->getDefaultAction();

			$actionStatus = $this->currentController->{$urlAction}();
		} else {


			$type = $component->getType();

			// Check if it has a registry setted. If it is, we need to send it to the constructor
			if ( $component->getRegistry() )
				$this->currentController = new $type( $component->getRegistry() );
			else
				$this->currentController = new $type;

			if ( $this->currentController instanceof \Maven\Core\Ui\AdminController )
				$this->currentController->setCurrentComponent( $component );

			// Get the action
			$action = $this->getAction();

			$actionStatus = Ui\ActionStatus::Redirect;

			// If it is doing ajax, and the action itself didn't finish it, we will do it!
			if ( $this->isDoingAjax() && ! $component->isValidAction( $action ) )
				die( 'Action not found: ' . $action );

			//Check if it is a valid action, so execute
			if ( $component->isValidAction( $action ) )
				$actionStatus = $this->currentController->{$action}();
			else
			// If not, just fire the default action
				$actionStatus = $this->currentController->{$component->getDefaultAction()}();

			//$this->currentComponent = null;
			// If it is doing ajax, and the action itself didn't finish it, we will do it!
			//LUCAS: Ver como pasar los mensajes de error en caso que ocurran
			if ( $this->isDoingAjax() )
				die(); //Removed the Executed response

			// Check if it is a valid result
			//LUCAS: Si hay errores(del messageManager) no redirecciona
			//Si no, revisar el actionStatus y ver que hacer(por defecto redirect)
			if ( $this->request->isPost() && ! $this->currentController->getMessageManager()->getErrorMessages() ) {
				if ( ( $actionStatus instanceof Ui\ActionStatus
					&& $actionStatus->getStatus() === Ui\ActionStatus::Redirect ) || ! $actionStatus ) {

					//Its a redirect, we need to save the messages in the trascient first
					$key = $this->currentController->getMessageManager()->saveMessages();

					$url = add_query_arg( \Maven\Core\Message\MessageManager::message_slug, $key, 'admin.php?page=' . $component->getSlug() );

					// Redirect 
					wp_safe_redirect( $url );
					//die();
				}
			}

			// If there was no redirect, probably there is an error or the user doesn't want to redirect
			$this->redirect = true;
		}
	}

	private function getAction() {

		// IF we are doing POST check the action first
		if ( $this->request->isPost() ) {

			// First we need to check if there is a button trying to execute an action
			// We need to check if it is not in the form of mvn-action$xxx	
			$action_matches = false;
			$actionName = \Maven\Constants::$mavenActionName;

			$expression = "/^{$actionName}-(.+)/";

			$keys = $this->request->getKeys();
			$unextracted_actions = preg_grep( $expression, $keys );

			if ( count( $unextracted_actions ) > 0 ) {

				$key = key( $unextracted_actions );
				$action_string = $unextracted_actions[ $key ];
				preg_match( $expression, $action_string, $action_matches );

				if ( $action_matches && count( $action_matches ) > 0 )
					return $action_matches[ 1 ];
			}
		}

		// If there is no button 
		$action = $this->request->getProperty( \Maven\Constants::$mavenActionName );
		if ( ! $action )
			$action = $this->request->getProperty( \Maven\Constants::$mavenAjaxActionName );

		// If the action doesn't exists, we need to check if it is not in the form of mvn-action$xxx
		/* if ( ! $action) {
		  $action = $this->getAction();
		  } */


		return $action;
	}

	private function getUrlAction() {


		// If there is no button 
		$action = $this->request->getProperty( \Maven\Constants::$mavenActionName );
		if ( ! $action )
			$action = $this->request->getProperty( \Maven\Constants::$mavenAjaxActionName );

		// If the action doesn't exists, we need to check if it is not in the form of mvn-action$xxx
		/* if ( ! $action) {
		  $action = $this->getAction();
		  } */


		return $action;
	}

	public function attach( ActionControllerObserver $observer ) {

		$this->observers[ ] = $observer;
	}

	public function detach( ActionControllerObserver $observer ) {

		$newobservers = array( );
		foreach ( $this->observers as $obs ) {
			if ( ( $obj !== $observer ) )
				$newobservers[ ] = $obs;
		}

		$this->observers = $newobservers;
	}

	public function notify( \Maven\Core\Component $component ) {

		foreach ( $this->observers as $obs ) {
			$obs->update( $component );
		}
	}

}
