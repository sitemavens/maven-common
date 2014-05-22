<?php

namespace Maven\Exceptions;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class FileNotFoundException extends MavenException {

	public function getDefaultMessage() {
		return "File not found.";
	}

}
