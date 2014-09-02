<?php

namespace Maven\Api\V2;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Description of observer
 *
 * @author lucasmpb
 */
class Wishlist {

	private static $instance;

	public static function current() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function registerRestApi() {

		\Maven\Core\HookManager::instance()->addFilter( 'json_endpoints', array( $this, 'registerRouters' ) );
	}

	public function registerRouters( $routes ) {

		$routes[ '/maven/v2/wishlist/item' ] = array(
		    array( array( $this, 'getItems' ), \WP_JSON_Server::READABLE ),
		    array( array( $this, 'addItem' ), \WP_JSON_Server::CREATABLE | \WP_JSON_Server::ACCEPT_JSON )
		);

		$routes[ '/maven/v2/wishlist/item/(?P<identifier>.+)' ] = array(
		    array( array( $this, 'removeItem' ), \WP_JSON_Server::DELETABLE ),
		    array( array( $this, 'updateItem' ), \WP_JSON_Server::EDITABLE | \WP_JSON_Server::ACCEPT_JSON )
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

	public function getItems() {
		$this->isValid();

		$wishlist = array();
		if ( $this->getCurrentUser()->getProfile()->hasWishlist() ) {
			$wishlist = $this->getCurrentUser()->getProfile()->getWishlist();
		}
		$this->sendResponse( \Maven\Core\Message\MessageManager::createSuccessfulMessage( '', $wishlist ) );
	}

	public function removeItem( $identifier ) {

		$this->isValid();

		$result = $this->getCurrentUser()->getProfile()->removeWishlistItem( $identifier );

		$this->sendResponse( $result );
	}

	public function addItem( $data ) {

		$defaultItem = array(
		    'id' => '',
		    'pluginKey' => '',
		    'name' => '',
		    'sku' => '',
		    'price' => 0
		);

		$item = wp_parse_args( $data, $defaultItem );

		if ( ! $item[ 'pluginKey' ] ) {
			$this->sendResponse( \Maven\Core\Message\MessageManager::createErrorMessage( 'Plugin Key is required' ) );
		}

		$wishlistItem = new \Maven\Core\Domain\WishlistItem();
		$wishlistItem->setName( $item[ 'name' ] );
		$wishlistItem->setPluginKey( $item[ 'pluginKey' ] );
		$wishlistItem->setThingId( $item[ 'id' ] );
		$wishlistItem->setSku( $item[ 'sku' ] );
		$wishlistItem->setPrice( $item[ 'price' ] );


		$this->sendResponse( $this->getCurrentUser()->getProfile()->addWishlistItem( $wishlistItem ) );
	}

	private function isValid() {

		if ( ! $this->getCurrentUser()->hasProfile() ) {
			$this->sendResponse( \Maven\Core\Message\MessageManager::createErrorMessage( 'There is no user/profile' ) );
		}
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\User
	 */
	private function getCurrentUser() {
		$userManager = new \Maven\Core\UserManager();
		//$userApi = new \Maven\Core\UserApi();
		$user = $userManager->getLoggedUser();

		return $user;
	}

	private function getCurrentCart() {

		$cart = \Maven\Core\Cart::current();

		return $cart;
	}

	private function sendResponse( \Maven\Core\Message\Message $result ) {

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
