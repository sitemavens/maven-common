<?php

namespace Maven\Exceptions;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class MavenException extends \Exception {

	public function getDefaultMessage() {
		return "Maven Exception.";
	}

	public function __toString() {
		if ( $this->getMessage() )
			return $this->getMessage();

		return $this->getDefaultMessage();
	}

}
