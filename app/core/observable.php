<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of observer
 *
 * @author mustela
 */
interface Observable {
    function attach( Observer $observer );
    function detach( Observer $observer );
    function notify();
}
