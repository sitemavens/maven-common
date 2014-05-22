<?php

namespace Maven\Exceptions;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class GatewayException extends MavenException {

	public function getDefaultMessage() {
		return "Something is wrong inside the gateway!";
	}

}
