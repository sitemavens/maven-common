<?php

namespace Maven\Exceptions;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class AdminException extends MavenException {

	public function getDefaultMessage() {
		return "Admin Exception.";
	}

}
