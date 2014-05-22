<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

interface ActionController {
	
	function handleRequest();
	
}