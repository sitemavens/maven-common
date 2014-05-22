<?php

namespace Maven\Exceptions;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class MySqlException extends MavenException {

	public function getDefaultMessage() {
		return "Mapper Exception.";
	}

}
