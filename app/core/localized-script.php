<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class LocalizedScript {
	
	private $scriptKey;
	private $domain;
	private $data;
	
	public function __construct( $scriptKey, $domain, $data ) {
		
		$this->scriptKey = $scriptKey;
		$this->domain	 = $domain;
		$this->data		 = $data;
		
	}


	public function getData() 
	{
		return $this->data;
	}

	public function setData($value) 
	{
		$this->data = $value;
	}

	public function getDomain() 
	{
		return $this->domain;
	}

	public function setDomain($value) 
	{
		$this->domain = $value;
	}

	public function getScriptKey() 
	{
		return $this->scriptKey;
	}

	public function setScriptKey($value) 
	{
		$this->scriptKey = $value;
	}
}


