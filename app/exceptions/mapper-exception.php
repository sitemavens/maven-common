<?php

namespace Maven\Exceptions;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class MapperException extends MavenException {

	public function getDefaultMessage() {
		return "Mapper Exception.";
	}

}
