<?php

namespace Maven\Front\Actions;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

/**
 * 
 */
class AddToCartAction extends Action {

	public function __construct ( \Maven\Front\Step $step, \Maven\Core\Cart $cart, $data ) {
		parent::__construct( $step, $cart, $data );
	}

	/**
	 * 
	 * @return \Maven\Core\Message
	 * @throws \Maven\Exceptions\InvalidObjectTypeException
	 */
	public function execute () {

		// We have to fire only the plugin specific hook
		//$groupKey = $this->getStep()->getThing()->getGroupKey();
		 
		$thing = $this->getStep()->getThing();
		
		if ( ! $thing->getPluginKey() ){
			throw new \Maven\Exceptions\RequiredException('You are trying to add an item without a Plugin Key');
		}
		
		
		// So we could have an array of items, instead of just one item
		$item = $this->getHookManager()->applyFilters( "maven/cart/addItem/{$thing->getPluginKey()}", $thing );
		
		if ( $item->getStatus()->isError() ){
			return $item->getStatus();
		}
		
		
		//die(print_r($item,true));
		if ( ! ( $item instanceof \Maven\Core\Domain\OrderItem ) ) {
			throw new \Maven\Exceptions\InvalidObjectTypeException( "Return filter: maven/cart/addItem, must be \Maven\Core\Domain\OrderItem type" );
		} 
		
		if ( ! $item->getThingId() ){
			throw new \Maven\Exceptions\RequiredException("Thing ID is required");
		}
		
		
		
		$result = $this->getCart()->addToCart( $item );
		$this->getStep()->setThing( $thing );
		return $result;
	}

	

}
