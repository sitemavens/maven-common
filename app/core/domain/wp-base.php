<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

abstract class WpBase  {

	/**
	 *
	 * @var \Maven\Core\HookManager 
	 */
	private $hookManager;
	
	/**
	 *
	 * @var \Maven\Settings\MavenRegistry
	 */
	private $registry;
	
	/**
	 *
	 * @var \Maven\Core\Request
	 */
	private $request;
	
	public function __construct () {
		
		$this->hookManager = \Maven\Core\HookManager::instance();
		$this->registry = \Maven\Settings\MavenRegistry::instance();
		$this->request = \Maven\Core\Request::current();
		$this->output = new \Maven\Core\UI\Output( $this->registry->getPluginDir() );
		
	}
	
	public function getHookManager () {
		return $this->hookManager;
	}

	/**
	 * 
	 * @return \Maven\Settings\MavenRegistry
	 */
	public function getRegistry () {
		return $this->registry;
	}

	/**
	 * 
	 * @return \Maven\Core\Request
	 */
	public function getRequest () {
		return $this->request;
	}

	
	/**
	 * Get output object
	 * @return \Maven\Core\UI\Ouput 
	 */
	protected function getOutput() {
		return $this->output;
	}

}
	
