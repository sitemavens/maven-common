<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

abstract class Manager {
	
	/**
	 *
	 * @var \Maven\Core\HookManager 
	 */
	private $hookManager;
	
	public function __construct () {
		$this->hookManager = HookManager::instance();
	}
	
	
	public function getHookManager () {
		return $this->hookManager;
	}


	
}