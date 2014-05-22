<?php

namespace Maven\Exceptions;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class RequiredException extends MavenException {

	public function getDefaultMessage() {
		return "The item is required.";
	}

}
