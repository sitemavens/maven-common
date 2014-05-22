<?php

namespace Maven\Front;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
    exit;

use \Maven\Front\Actions;

class FrontEnd {

    private $request = null;
    private $registry = null;
    private $inputKey = null;

    /**
     * 
     * @param \Maven\Settings\Registry $registry
     * @param string $inputKey
     */
    public function __construct ( \Maven\Settings\Registry $registry, $inputKey ) {
        $this->request = \Maven\Core\Request::current();
        $this->registry = $registry;
        $this->inputKey = $inputKey;
    }

    /**
     * Return the step
     * @return \Maven\Front\Step
     */
    public function getStep () {

        if ( $this->isMavenRequest() ) {
            $step = new Step( $this->getMavenRequestKey(), $this->inputKey );

            $step->loadFromRequest();

            return $step;
        }

        return false;
    }

    public function getRequest () {
        return $this->request;
    }

    public function getRegistry () {
        return $this->registry;
    }

    protected function getMavenRequestKey () {
        return $this->registry->getPluginKey() . "Transaction";
    }

    public function isMavenRequest () {

        $key = $this->getMavenRequestKey();

        return ( $this->getRequest()->isPost() && wp_verify_nonce( $this->getRequest()->getProperty( $key ), $key ) );
    }

    public function getOptions () {

        $options = $this->getRequest()->getProperty( $this->inputKey );

        return $options;
    }

    public function newStep () {
        $step = new \Maven\Front\Step( $this->getMavenRequestKey(), $this->inputKey );

        return $step;
    }

    function manageRequest () {

        if ( $this->isMavenRequest() ) {

            $cart = \MavenShop\Core\Cart::current();

            $step = $this->getStep();

            $request = \Maven\Core\Request::current();

            if ( !$step )
                return;

            /** Let's see what we can do * */
            switch ( $step->getAction() ) {

                case \Maven\Front\Actions::AddToCart:
                    $action = new Actions\AddItemAction( $step, $cart );
                    break;
                
                case \Maven\Front\Actions::UpdateCart:
                    $action = new Actions\UpdateCartAction( $step, $cart );
                    break;
                
                case "checkout":
                    $action = new Actions\CheckoutAction( $step, $cart );
                    break;
            }

            $result = $action->execute();

//            if ( $step->getNextStep() && !$request->exists( 'updateCartButton' ) ) {
//
//                wp_redirect( site_url( $step->getNextStep() ) );
//                exit();
//            }
        }
    }

}
