<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class Resource{
	
	private $name;
	private $file;
	private $version;
	private $deps = array();
	private $registerOnly = false;


	public function getRegisterOnly() 
	{
		return $this->registerOnly;
	}

	public function setRegisterOnly($value) 
	{
		$this->registerOnly = $value;
	}
	

	/**
	 * Determine if the resource is for admin area or not
	 * @var boolean 
	 */
	private $admin = true;

	/**
	 * 
	 * @param string $name
	 * @param string $file
	 * @param string $version
	 * @param array $deps
	 * @param boolean $admin
	 */
	public function __construct( $name, $file, $version, $deps, $admin = true, $registerOnly = false ){
		
		$this->name		= $name;
		$this->file		= $file ;
		$this->version	= $version;
		$this->deps		= $deps;
		$this->admin	= $admin;
		$this->registerOnly = $registerOnly;
		
	}
	
	public function getDeps() 
	{
		return $this->deps;
	}

	public function setDeps($value) 
	{
		$this->deps = $value;
	}


	public function getVersion() 
	{
		return $this->version;
	}

	public function setVersion($value) 
	{
		$this->version = $value;
	}
	
	public function getFile() 
	{
		return $this->file;
	}

	public function setFile( $value ) {
			
		$this->file = $value;
	}


	public function getName() 
	{
		return $this->name;
	}

	public function setName($value) 
	{
		$this->name = $value;
	}
	
	public function isAdmin() 
	{
		return $this->admin;
	}

	public function setAdmin($value) 
	{
		$this->admin = $value;
	}
	
	public function isStyle(){
		return $this->isStyle;
	}
	
}