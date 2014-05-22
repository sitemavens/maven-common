<?php

namespace Maven\Exceptions;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class CoreException extends MavenException {

	public function getDefaultMessage() {
		return "Core Exception.";
	}

}
