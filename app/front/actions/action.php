<?php

namespace Maven\Front\Actions;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

abstract class Action {

	/**
	 *
	 * @var \Maven\Core\HookManager 
	 */
	private $hookManager;
	
	/**
	 *
	 * @var \Maven\Front\Step 
	 */
	private $step;

	/**
	 * 
	 * @var \Maven\Core\Cart 
	 */
	private $cart;
	private $data = array();

	/**
	 * Instantiate the action
	 * @param \Maven\Front\Step $step
	 * @param \Maven\Core\Cart $cart
	 */
	public function __construct ( \Maven\Front\Step $step, \Maven\Core\Cart $cart, $data = array() ) {

		$this->step = $step;
		$this->cart = $cart;
		$this->data = $data;
		$this->hookManager = \Maven\Core\HookManager::instance();
	}

	/**
	 * @return \Maven\Core\Message 
	 */
	abstract function execute ();

	/**
	 * 
	 * @return \Maven\Front\Step
	 */
	public function getStep () {
		return $this->step;
	}

	/**
	 * 
	 * @return \Maven\Core\Cart 
	 */
	public function getCart () {
		return $this->cart;
	}

	public function getData () {
		return $this->data;
	}

	public function setData ( $data ) {
		$this->data = $data;
	}
	
	public function setDataValue ( $key, $data ) {
		$this->data[$key] = $data;
	}

	public function getDataValue ( $key ) {
		return isset( $this->data[ $key ] ) ? $this->data[ $key ] : false;
	}
	
	/**
	 * Get Hook Manager
	 * @return \Maven\Core\HookManager
	 */
	public function getHookManager () {
		return $this->hookManager;
	}
 



}
