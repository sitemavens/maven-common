<?php

namespace Maven\Front\Actions;

use \Maven\Front\DataToCollect;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

/**
 * 
 */
class UpdateCheckoutAction extends Action {

	public function __construct ( \Maven\Front\Step $step, \Maven\Core\Cart $cart, $data = array() ) {
		parent::__construct( $step, $cart, $data );

	}


	/**
	 * 
	 * @return \Maven\Core\Message
	 * @throws \Maven\Exceptions\InvalidObjectTypeException
	 */
	public function execute () {

		$updateCart = New UpdateCartAction($this->getStep(), $this->getCart(), $this->getData() );

		$result = $updateCart->execute();
		
		if ( $result->isSuccessful() ){
			$checkout = New CheckoutAction($this->getStep(), $this->getCart(), $this->getData() );
			
			return $checkout->execute();
		}
		
		return $result;
		    
	}

}
