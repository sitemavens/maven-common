<?php

namespace Maven\Core\Shipping;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Empty Shipping Method Type
 * This method is usted only internally
 */
class EmptyShippingMethod extends \Maven\Core\Domain\ShippingMethodType {
	
	public function __construct (  ) {
		
		parent::__construct( "Empty Method", 'empty' );
	}

	

}