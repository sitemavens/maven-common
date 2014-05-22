<?php

namespace Maven\Loggers;

abstract class Log {
	
	private $isEnabled = false;
	
	public function __construct ( $enabled ) {
		$this->isEnabled = $enabled;
	}
	
	abstract function message( $message );
	
	public function isEnabled(){
		return $this->isEnabled;
	}
}