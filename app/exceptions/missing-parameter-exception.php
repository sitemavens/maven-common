<?php

namespace Maven\Exceptions;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class MissingParameterException extends MavenException {

	public function getDefaultMessage() {
		return "Missing parameter";
	}

}
