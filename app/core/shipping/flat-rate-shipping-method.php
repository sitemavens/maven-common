<?php

namespace Maven\Core\Shipping;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Flat Rate Shipping Method Type
 */
class FlatRateShippingMethod extends \Maven\Core\Domain\ShippingMethodType {
	
	public function __construct (  ) {
		
		parent::__construct( "Flat Rate", 'flat-rate' );
	}

	public function applyShipping ( \Maven\Core\Domain\Order $order ) {
		
	}

}