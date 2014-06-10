<?php

namespace Maven\Front\Actions;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

/**
 * 
 */
class UpdateItemQuantityAction extends Action {

	public function __construct ( \Maven\Front\Step $step, \Maven\Core\Cart $cart, $data ) {
		parent::__construct( $step, $cart, $data );
	}

	/**
	 * 
	 * @return \Maven\Core\Message
	 * @throws \Maven\Exceptions\InvalidObjectTypeException
	 */
	public function execute () {

		$thing = $this->getStep()->getThing();

		if ( !$thing->getPluginKey() ) {
			throw new \Maven\Exceptions\RequiredException( 'You are trying to remove an item without a Plugin Key' );
		}

		$item = $this->getHookManager()->doAction( "maven/cart/updateItemQuantity/{$thing->getPluginKey()}", $thing );

		if ( !$item ) {
			$item = new \Maven\Core\Domain\OrderItem();
			$item->setThingId( $thing->getId() );
			$item->setPluginKey( $thing->getPluginKey() );
			$item->setQuantity( $thing->getQuantity() );
		}

		if ( !$item->getThingId() ) {
			throw new \Maven\Exceptions\RequiredException( "Thing ID is required" );
		}

		if ( $item->getStatus()->isError() ) {
			return $item->getStatus();
		}

		$result = $this->getCart()->updateItemQuantity( $item );

		return $result;
	}

}
