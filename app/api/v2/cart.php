<?php

namespace Maven\Api\V2;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

/**
 * Description of observer
 *
 * @author mustela
 */
class Cart {
	
	private static $instance;
	
	public static function current(){
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}

	public function registerRestApi () {
		
		\Maven\Core\HookManager::instance()->addFilter( 'json_endpoints', array( $this, 'registerRouters' ) );
	}

	public function registerRouters ( $routes ) {

		$routes[ '/maven/v2/cart/items/remove/(?P<identifier>.+)' ] = array(
			array( array( $this, 'removeItem' ), \WP_JSON_Server::ALLMETHODS )
		);

//		$routes[ '/maven/taxes' ] = array(
//			array( array( $this, 'getTaxes' ), \WP_JSON_Server::READABLE ),
//			array( array( $this, 'newTax' ), \WP_JSON_Server::CREATABLE | \WP_JSON_Server::ACCEPT_JSON ),
//		);
//		$routes[ '/maven/taxes/(?P<id>\d+)' ] = array(
//			array( array( $this, 'getTax' ), \WP_JSON_Server::READABLE ),
//			array( array( $this, 'editTax' ), \WP_JSON_Server::EDITABLE | \WP_JSON_Server::ACCEPT_JSON ),
//			array( array( $this, 'deleteTax' ), \WP_JSON_Server::DELETABLE ),
//		);
//		

		return $routes;
	}

	public function removeItem ( $identifier ) {

		$this->isValid();

		$this->sendResponse( $this->getCurrentCart()->removeItem( $identifier ) );
	}

	private function isValid () {
		
		if ( !$this->getCurrentCart()->hasOrder() ) {
			$this->sendResponse( \Maven\Core\Message\MessageManager::createErrorMessage( 'There is no order' ) );
		}
	}

	private function getCurrentCart () {

		$cart = \Maven\Core\Cart::current();

		return $cart;
	}

	private function sendResponse ( \Maven\Core\Message\Message $result ) {

		$output = new \Maven\Core\UI\OutputTranslator();
		$transformedOrder = $output->convert( $this->getCurrentCart()->getOrder() );

		if ( $result->isSuccessful() ) {
			$result = array( 'successful' => true, 'error' => false, 'description' => $result->getContent(), 'data' => $result->getData(), 'order' => $transformedOrder );
		} else {
			$result = array( 'successful' => false, 'error' => true, 'description' => $result->getContent(), 'data' => $result->getData(), 'order' => $transformedOrder );
		}

		die( json_encode( $result ) );
	}

}
