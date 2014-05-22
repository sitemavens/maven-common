<?php

namespace Maven\Exceptions;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class NotFoundException extends MavenException {

	public function getDefaultMessage() {
		return "The item doesn't exists.";
	}

}
