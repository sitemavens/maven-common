<?php

namespace Maven\Core\Shipping;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class ShippingMethodTypes {

	const FlatRate = "flatRate";
	const OrderAmountTiers = "orderAmountTiers";

	private static $keys = array( self::FlatRate, self::OrderAmountTiers );

	public static function getMethods () {

		return array(
			array( 'id' => self::FlatRate, 'name' => "Flat Rate" ),
			array( 'id' => self::OrderAmountTiers, 'name' => "Order Amount Tiers" ),
		);
	}

	public static function isValid ( $methodType ) {
		return in_array( $methodType, self::$keys );
	}

}
