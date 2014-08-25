<?php

namespace Maven\Front\Actions;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * 
 */
class AddToWishlistAction extends WishlistAction {

	public function __construct( \Maven\Front\Thing $thing, \Maven\Core\Profile $profile, $data ) {
		parent::__construct( $thing, $profile, $data );
	}

	/**
	 * 
	 * @return \Maven\Core\Message
	 * @throws \Maven\Exceptions\InvalidObjectTypeException
	 */
	public function execute() {

		// We have to fire only the plugin specific hook
		//$groupKey = $this->getStep()->getThing()->getGroupKey();

		$thing = $this->getThing();

		if ( ! $thing->getPluginKey() ) {
			throw new \Maven\Exceptions\RequiredException( 'You are trying to add an item without a Plugin Key' );
		}


		// So we could have an array of items, instead of just one item
		$item = $this->getHookManager()->applyFilters( "maven/wishlist/addItem/{$thing->getPluginKey()}", $thing );

		//die(print_r($item,true));
		if ( ! ( $item instanceof \Maven\Core\Domain\WishlistItem) ) {
			throw new \Maven\Exceptions\InvalidObjectTypeException( "Return filter: maven/wishlist/addItem, must be \Maven\Core\Domain\WishlistItem type" );
		}

		if ( ! $item->getThingId() ) {
			throw new \Maven\Exceptions\RequiredException( "Thing ID is required" );
		}



		$result = $this->getProfile()->addWishlistItem( $item );

		return $result;
	}

}
