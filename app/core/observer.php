<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Description of observer
 *
 * @author mustela
 */
interface Observer {
	function update( \Maven\Core\Observable $observable );
}

