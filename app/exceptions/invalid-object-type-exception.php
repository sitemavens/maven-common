<?php

namespace Maven\Exceptions;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class InvalidObjectTypeException extends MavenException {

	public function getDefaultMessage() {
		return "Invalid object type.";
	}

}
