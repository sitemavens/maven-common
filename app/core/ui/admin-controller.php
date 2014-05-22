<?php

namespace Maven\Core\Ui;

abstract class AdminController {

	/**
	 * It holds the component which is trying to execute an action
	 * 
	 * @var \Maven\Core\Component 
	 */
	private $currentComponent;

	/**
	 * 
	 * @var \Maven\Core\Ui\Ouput 
	 */
	private $output;

	/**
	 *
	 * @var \Maven\Core\Request 
	 */
	private $request;

	/**
	 *
	 * @var \Maven\Settings\Registry 
	 */
	private $registry;

	/**
	 *
	 * @var \Maven\Core\Message\MessageManager
	 */
	private $messageManager;
	
	/**
	 *
	 * @var \Maven\Core\Language 
	 */
	private $lang;

	/**
	 *
	 * @var \Maven\Core\HookManager 
	 */
	private $hookManager;
	
	
	public function __construct( \Maven\Settings\Registry $registry ) {

		$this->output = new \Maven\Core\Ui\Output( $registry->getPluginDir() );
		$this->request = new \Maven\Core\Request();
		$this->registry = $registry;
		$this->lang = $this->registry->getLanguage();
		$this->hookManager = \Maven\Core\HookManager::instance();

		//Add the messageManager
		$this->messageManager = new \Maven\Core\Message\MessageManager( new \Maven\Core\Message\RandomMessageKeyGenerator( 4 ) );

		//Load the transient from the request(if exists)
		$message_key = $this->request->getProperty( \Maven\Core\Message\MessageManager::message_slug );
		if ( $message_key ) {
			//Recover the messages on the transient
			$this->messageManager->loadMessages( $message_key );
		}

		//We have to set the language to the ouput
		$this->output->addData( "lang", $this->lang );
		
		//Set the message manager to the output
		$this->output->addData( "messages", $this->messageManager );
	}
	
	/**
	 * 
	 * @return \Maven\Core\HookManager
	 */
	public function getHookManager () {
		return $this->hookManager;
	}
	
	public function __( $text ){
		return $this->lang->__( $text );
	}

	/**
	 * Get the current component
	 * 
	 * @return \Maven\Core\Component 
	 */
	public function getCurrentComponent() {
		return $this->currentComponent;
	}

	/**
	 * Set the component which is trying to execute an action
	 * 
	 * @param \Maven\Core\Component $value
	 */
	public function setCurrentComponent( \Maven\Core\Component $value ) {
		$this->currentComponent = $value;
	}

	/**
	 * Get output object
	 * @return \Maven\Core\Ui\Ouput 
	 */
	protected function getOutput() {
		return $this->output;
	}

	/**
	 * Get request object
	 * @return \Maven\Core\Request 
	 */
	protected function getRequest() {
		return $this->request;
	}

	/**
	 *
	 * @param type \Maven\Core\Message\MessageManager
	 */
	protected function setMessageManager( $messageManager ) {

		$this->messageManager = $messageManager;
		//$this->output->addData("message", $messageManager);
	}

	/**
	 *
	 * @return \Maven\Core\Message\MessageManager 
	 */
	public function getMessageManager() {

		return $this->messageManager;
	}

	/**
	 *
	 * @return \Maven\Settings\Registry  
	 */
	protected function getRegistry() {

		return $this->registry;
	}

	/**
	 * Add a value to the data array
	 * @param string $key
	 * @param mixed $value
	 */
	protected function addData( $key, $value ){
		
		$this->getOutput()->addData( $key, $value );
		
	}
	
	/**
	 * Add a value to the data array
	 * @param string $key
	 * @param mixed $value
	 */
	protected function addJSONData( $key, $value ){
		
		$this->getOutput()->addData( $key, $this->getOutput()->toJSON( $value ) );
		
	}
	
	/**
	 * It lets you add an array of data objects. 
	 * @param array $data
	 */
	function addMassiveData( $data ){
		
		$this->getOutput()->addMassiveData( $data );
		
	}
	
	/**
	 * @return \Maven\Core\Ui\ActionResponse 
	 */
	abstract function showForm();

	/**
	 * @return \Maven\Core\Ui\ActionResponse 
	 */
	abstract function showList();

	
}