<?php

namespace Maven\Front;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Actions {

	const AddToCart = 'AddToCart';
	const UpdateCart = 'UpdateCart';
	const Checkout = 'Checkout';
	const UpdateCheckout = 'UpdateCheckout';
	const SaveProfile = 'SaveProfile';
	const GetCartInfo = 'GetCartInfo';
	const RemoveItem = 'removeItem';
}
