<?php

namespace Maven\Core\Shipping;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Order Amount Tiers Shipping Method Type
 */
class OrderAmountTiersShippingMethod extends \Maven\Core\Domain\ShippingMethodType{
	
	public function __construct ( ) {
		
		parent::__construct( "Order Amount Tiers", 'order-amount-tiers' );
		
	}

	public function applyShipping ( \Maven\Core\Domain\Order $order ) {
		
	}

}