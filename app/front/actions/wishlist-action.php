<?php

namespace Maven\Front\Actions;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

abstract class WishlistAction {

	/**
	 *
	 * @var \Maven\Core\HookManager 
	 */
	private $hookManager;

	/**
	 *
	 * @var \Maven\Front\Thing; 
	 */
	private $thing;

	/**
	 * 
	 * @var \Maven\Core\profile 
	 */
	private $profile;
	private $data = array();

	/**
	 * Instantiate the action
	 * @param \Maven\Core\profile $profile
	 */
	public function __construct( \Maven\Front\Thing $thing, \Maven\Core\Profile $profile, $data = array() ) {

		$this->thing = $thing;
		$this->profile = $profile;
		$this->data = $data;
		$this->hookManager = \Maven\Core\HookManager::instance();
	}

	/**
	 * @return \Maven\Core\Message 
	 */
	abstract function execute();
	
	/**
	 * 
	 * @return \Maven\Front\Thing
	 */
	public function getThing () {
		return $this->thing;
	}


	/**
	 * 
	 * @return \Maven\Core\Profile 
	 */
	public function getProfile() {
		return $this->profile;
	}

	public function getData() {
		return $this->data;
	}

	public function setData( $data ) {
		$this->data = $data;
	}

	public function setDataValue( $key, $data ) {
		$this->data[ $key ] = $data;
	}

	public function getDataValue( $key ) {
		return isset( $this->data[ $key ] ) ? $this->data[ $key ] : false;
	}

	/**
	 * Get Hook Manager
	 * @return \Maven\Core\HookManager
	 */
	public function getHookManager() {
		return $this->hookManager;
	}

}
