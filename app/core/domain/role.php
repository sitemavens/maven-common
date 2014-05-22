<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class Role extends \Maven\Core\DomainObject{
	
	private $name;
	private $systemRole = false;
	
	
	/**
	 *
	 * @var Array()
	 */
	private $capabilities = array();
	 
	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}
	
	public function isSystemRole() {
		return $this->systemRole;
	}

	public function setSystemRole( $systemRole ) {
		$this->systemRole = $systemRole;
	}

	
	/**
	 * 
	 * @return Array
	 */
	public function getCapabilities() {
		
		if ( ! $this->hasMainCapability() )
			$this->capabilities[] = $this->getRoleKey ( );
		
		
		return $this->capabilities;
	}

	/**
	 * 
	 * @param Array $capabilities
	 */
	public function setCapabilities( $capabilities ) {
		$this->capabilities = $capabilities;
	}
	
	public function hasCapabilities(){
		return ! \Maven\Core\Utils::isEmpty( $this->capabilities );
	}

	
	public function __construct( $id = false ) {
		parent::__construct( $id );
	}
	
	private function hasMainCapability(){
		if ( $this->hasCapabilities() )
			return isset( $this->capabilities[ $this->getRoleKey()] );
		
		return false;
	}
	
	private function getRoleKey(){
		if ( $this->getId() )
			return $this->getId ( );
		
		return $this->getSanitizedName();
	}
	
	public function getSanitizedName(){
		return sanitize_key ($this->name);
	}
	
	
}