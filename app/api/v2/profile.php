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
class Profile {
 
	private static $instance;

	public static function current () {
		if ( !self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private $profileManager;

	public function __construct () {
		$this->profileManager = new \Maven\Core\ProfileManager();
	}

	public function registerRestApi () {

		\Maven\Core\HookManager::instance()->addFilter( 'json_endpoints', array( $this, 'registerRouters' ) );
	}

	public function registerRouters ( $routes ) {

		$routes['/maven/v2/profile'] = array(
			array( array( $this, 'add' ), \WP_JSON_Server::CREATABLE | \WP_JSON_Server::ACCEPT_JSON )
		);

		$routes['/maven/v2/profile/(?P<identifier>\d+)'] = array(
			array( array( $this, 'update' ), \WP_JSON_Server::EDITABLE | \WP_JSON_Server::ACCEPT_JSON )
		);

		$routes['/maven/v2/profile/(?P<identifier>\d+)/password'] = array(
			array( array( $this, 'changePassword' ), \WP_JSON_Server::EDITABLE | \WP_JSON_Server::ACCEPT_JSON )
		);

		$routes['/maven/v2/profile/convert-from-user'] = array(
			array( array( $this, 'convertFromUser' ), \WP_JSON_Server::EDITABLE | \WP_JSON_Server::ACCEPT_JSON )
		);

		return $routes;
	}

	public function convertFromUser ( $data ) {

		if ( $this->userCan() ) {

			if ( isset( $data['email'] ) ) {
				$result = $this->profileManager->convertWpUserToMaven( $data['email'] );

				$this->sendResponse( \Maven\Core\Message\MessageManager::createSuccessfulMessage( 'User converted' ) );
			} else {
				$this->sendResponse( \Maven\Core\Message\MessageManager::createErrorMessage( 'You need to specify the email' ) );
			}
		}
	}

	private function userCan () {
		//maven create roles with the capabilities's name the same as the role name
		$defaultRole = apply_filters( 'maven/api/profile/capabilities', '' );
		if ( current_user_can( 'read' ) || current_user_can( $defaultRole ) ) {
			return true;
		} else {
			$this->sendResponse( \Maven\Core\Message\MessageManager::createErrorMessage( 'You don\'t have permissions to do it' ) );
		}
	}

	public function update ( $identifier, $data ) {

		if ( $this->userCan() ) {

			$this->isValid();

			$profile = new \Maven\Core\Domain\Profile();
			$profile->load( $data );

			$this->profileManager->addProfile( $profile );

			$this->sendResponse( \Maven\Core\Message\MessageManager::createSuccessfulMessage( 'Profile added' ) );
		}
	}

	public function changePassword ( $identifier, $data ) {

		if ( $this->userCan() ) {

			$this->isValid();

			$profile = new \Maven\Core\Domain\Profile();
			$profile->load( $data );
			if ( $this->profileManager->isWPUser( $profile->getEmail() ) && !empty( $data['password'] ) ) {
				$this->profileManager->changeWpPassword( $data['password'], $profile->getUserId() );
				$autoLoginKey = $this->profileManager->generateAutoLoginKey( $profile->getEmail() );
				\Maven\Core\UserManager::autoLogin( $profile->getEmail(), $autoLoginKey );
				$this->sendResponse( \Maven\Core\Message\MessageManager::createSuccessfulMessage( 'Password saved' ) );
			} else {
				$this->sendResponse( \Maven\Core\Message\MessageManager::createErrorMessage( 'Password missing' ) );
			}
		}
	}

	public function addItem ( $data ) {

		$defaultItem = array(
			'id' => '',
			'pluginKey' => '',
			'name' => '',
			'quantity' => 0,
			'price' => 0
		);

		$item = wp_parse_args( $data, $defaultItem );

		if ( !$item['pluginKey'] ) {
			$this->sendResponse( \Maven\Core\Message\MessageManager::createErrorMessage( 'Plugin Key is required' ) );
		}

		$orderItem = new \Maven\Core\Domain\OrderItem();
		$orderItem->setName( $item['name'] );
		$orderItem->setPluginKey( $item['pluginKey'] );
		$orderItem->setThingId( $item['id'] );
		$orderItem->setPrice( $item['price'] );
		$orderItem->setQuantity( $item['quantity'] );


		$this->sendResponse( $this->getCurrentCart()->addToCart( $orderItem ) );
	}

	private function isValid () {

		return true;
//		
//		if ( !$this->getCurrentCart()->hasOrder() ) {
//			$this->sendResponse( \Maven\Core\Message\MessageManager::createErrorMessage( 'There is no order' ) );
//		}
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
