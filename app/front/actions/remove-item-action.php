<?php

namespace Maven\Front\Actions;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

/**
 * 
 */
class RemoveItemAction extends Action {

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

		$this->getHookManager()->doAction( "maven/cart/removeItem/{$thing->getPluginKey()}", $thing );

		$item = new \Maven\Core\Domain\OrderItem();
		$item->setThingId( $thing->getId() );
		$item->setPluginKey( $thing->getPluginKey() );
		
		if ( !$item->getThingId() ) {
			throw new \Maven\Exceptions\RequiredException( "Thing ID is required" );
		}

		$result = $this->getCart()->removeItem( $item );

		return $result;
	}

}
