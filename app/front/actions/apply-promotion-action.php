<?php

namespace Maven\Front\Actions;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

/**
 * 
 */
class ApplyPromotionAction extends Action {

	public function __construct ( \Maven\Front\Step $step, \Maven\Core\Cart $cart, $data ) {
		parent::__construct( $step, $cart, $data );
	}

	/**
	 * 
	 * @return \Maven\Core\Message
	 * @throws \Maven\Exceptions\InvalidObjectTypeException
	 */
	public function execute () {
		
		// So we could have an array of items, instead of just one item
		$promotionCode = $this->getHookManager()->applyFilters( "maven/cart/applyPromotion", $this->getStep()->getPromotionCode() );
		
		$result = $this->getCart()->applyPromotion($promotionCode);
		
		return $result;
	}

	

}
