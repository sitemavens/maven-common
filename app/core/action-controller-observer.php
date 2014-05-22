<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Description of observer
 *
 * @author mustela
 */
interface ActionControllerObserver {
	
	function update( \Maven\Core\Component $component );
	
}

