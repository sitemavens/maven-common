<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of observer
 *
 * @author mustela
 */
interface ActionControllerObservable {
	
    function attach( ActionControllerObserver $observer );
    function detach( ActionControllerObserver $observer );
    function notify( \Maven\Core\Component $component );
	
}


