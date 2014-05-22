<?php

namespace Maven\Front\Actions;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * 
 */
class GetCartInfoAction extends Action {

	public function __construct( \Maven\Front\Step $step, \Maven\Core\Cart $cart, $data ) {
		parent::__construct( $step, $cart, $data );
	}

	/**
	 * 
	 * @return \Maven\Core\Message
	 * @throws \Maven\Exceptions\InvalidObjectTypeException
	 */
	public function execute() {

		try {
			$cart = \Maven\Core\Cart::current();

			$data = $cart->getCartInfo();

			return \Maven\Core\Message\MessageManager::createSuccessfulMessage( "Data collected succesfully", $data );
		} catch ( \Exception $e ) {
			return \Maven\Core\Message\MessageManager::createErrorMessage( $e->getMessage() );
		}
	}

}
