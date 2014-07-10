<?php

namespace Maven\Front\Actions;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
    exit;

/**
 * 
 */
class CheckoutAction extends Action {

	public function __construct ( \Maven\Front\Step $step, \Maven\Core\Cart $cart, $data= array() ) {
		parent::__construct( $step, $cart, $data );
	}

    /**
     * 
     * @return \Maven\Core\Message
     * @throws \Maven\Exceptions\InvalidObjectTypeException
     */
    public function execute () {

        $result = $this->getCart()->pay();
		
		return $result;
    }

}
