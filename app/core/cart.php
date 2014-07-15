<?php

//https://github.com/EllisLab/CodeIgniter/blob/develop/system/libraries/Session/Session.php
//https://github.com/EllisLab/CodeIgniter/blob/develop/system/libraries/Session/drivers/Session_cookie.php

namespace Maven\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

use \Maven\Core\OrdersApi;

class Cart {

	/**
	 *
	 * @var \Maven\Core\Domain\Order 
	 */
	private $order = false;
	private $result = false;

	/**
	 * Hold the cart instance
	 * @var \Maven\Core\Cart 
	 */
	private static $instance;

	private function __construct () {
		
	}

	/**
	 * Hold the cart instance
	 * @return \Maven\Core\Cart 
	 */
	public static function current () {

		if ( !self::$instance ) {
			self::$instance = new static();
		}

		return self::$instance;
	}

	/**
	 * Load and existing order, remember it can't be completed or shipped.
	 * @param int $orderId
	 * 
	 * @return \Maven\Core\Domain\Order
	 */
	public function loadOrder ( $orderId ) {

		\Maven\Loggers\Logger::log()->message( 'Maven/Cart/loadOrder' );

		$orderManager = new OrderManager();
		$order = $orderManager->get( $orderId );

		if ( $order->getStatus()->getId() == OrderStatusManager::getCompletedStatus()->getId() || $order->getStatus()->getId() == OrderStatusManager::getShippedStatus()->getId() ) {
			throw new \Maven\Exceptions\MavenException( 'The Order is completed, you can\'t load it' );
		}

		if ( !$order->isEmpty() ) {
			return $this->newOrder( $order );
		}

		return false;
	}

	private function newOrder ( \Maven\Core\Domain\Order $order = null ) {

		\Maven\Loggers\Logger::log()->message( 'Maven/Cart/newOrder' );

		if ( $order && !$order->isEmpty() ) {
			$this->loadExistingOrder( $order );
		} else {
			$this->createNewOrder();
		}

		$this->loadUserLoggedProfile();

		$session = \Maven\Session\SessionManager::get();

		//Check if there is another existing order and someone wants to re-creatate a new order
		$session->addData( $this->getSessionKey(), $this->order );


		return $this->order;
	}

	public function updateItemQuantity ( $identifier, $newQuantity ) {

		$order = $this->getOrder();

		if ( $order->itemExists( $identifier ) ) {
			$orderItem = &$order->getItem( $identifier );

			$orderItem->setQuantity( $newQuantity );

			$this->update();

			return \Maven\Core\Message\MessageManager::createSuccessfulMessage( 'Item updated' );
		}

		return \Maven\Core\Message\MessageManager::createErrorMessage( 'Item not found' );
	}

	public function loadExistingOrder ( \Maven\Core\Domain\Order $order ) {

		\Maven\Loggers\Logger::log()->message( 'Maven/Cart/loadExistingOrder' );

		$this->order = $order;

		// Since we don't really know how old is the order or what have changed, we need to recalculate taxes, promotions, and so.
		$orderManager = new OrderManager();
		$order = $orderManager->reCalculateOrderTotals( $order );

		// Save the order 
		$this->update();

		HookManager::instance()->doAction( 'maven/cart/loadOrder', $this->order );
	}

	public function createNewOrder () {


		\Maven\Loggers\Logger::log()->message( 'Cart: createNewOrder' );

		//Register the order api
		$orderApi = new OrdersApi();

		// Create a new order
		$this->order = $orderApi->newOrder();

		HookManager::instance()->doAction( 'maven/cart/newOrder', $this->order );
	}

	private function orderExists () {

		return !Utils::isEmpty( $this->order );
	}

	/**
	 * Return the result
	 * @return \Maven\Core\Message\Message
	 */
	public function getResult () {
		return $this->result;
	}

	/**
	 * Set the cart result
	 * @param \Maven\Core\Message\Message $message
	 * @return type
	 */
	public function setResult ( \Maven\Core\Message\Message $message ) {
		$this->result = $message;
		return $this->result;
	}

	private function getSessionKey () {

		//$sesionKey = $this->registry->getPluginKey();
		$sesionKey = "maven-session-key-order";

		return $sesionKey;
	}

	/**
	 * 
	 */
	public function isReadyToBePaid () {

		$result = $this->orderExists() && $this->order->hasItems();
		$result = $result && !$this->order->getContact()->isCompleted() && !$this->order->getBillingContact()->isCompleted() && !$this->order->getShippingContact()->isCompleted();

		return $result;
	}

	/**
	 * Return the order
	 * @return \Maven\Core\Domain\Order
	 */
	public function getOrder () {

		\Maven\Loggers\Logger::log()->message( 'Maven/Cart/getOrder' );

		if ( !$this->order ) {

			// Check if it exists in session
			$session = \Maven\Session\SessionManager::get();

			$order = $session->getData( $this->getSessionKey() );


			if ( $order && !$order->isEmpty() ) {

				\Maven\Loggers\Logger::log()->message( 'Cart: getOrder - Order exists in session' );


				$this->order = $order;

				// Just verify if the ord$er need to be repopulate with user info 
				$this->loadUserLoggedProfile();

				// If the order doesn't exists, probably it's because we are adding the first item
				//return $this->newOrder();
			} else {
				\Maven\Loggers\Logger::log()->message( 'Cart: Order doesnt exists in session' );
			}

//			else{
//				$this->order = $order;
//				
//				// Just verify if the ord$er need to be repopulate with user info 
//				$this->loadUserLoggedProfile();
//			}
		} else {
			\Maven\Loggers\Logger::log()->message( 'Cart: Order already loaded - ' . ($this->order->getId()) );
		}



		return $this->order;
	}

	/**
	 * Add an item to the cart
	 * @param \Maven\Core\Domain\OrderItem $item
	 * @param string $key
	 * @return \Maven\Core\Message\Message
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function addToCart ( \Maven\Core\Domain\OrderItem $item ) {

		\Maven\Loggers\Logger::log()->message( 'Maven/Cart/addToCart' );

		// Check if it already exists in session
		$order = $this->getOrder();

		// If we don't have an order yet, lets create a new one
		if ( !$this->orderExists() ) {
			$this->newOrder();
		}

		// Ensure that we are adding an item with quantity
		if ( ( int ) $item->getQuantity() <= 0 ) {
			return Message\MessageManager::createErrorMessage( 'Item quantity must be greater than 0' );
		}


		$orderApi = new OrdersApi();

		$this->order = $orderApi->addItem( $this->order, $item );

		$this->update();

		return Message\MessageManager::createRegularMessage( 'Item added sucessfully', $this->order );
	}

	/**
	 * Remove item from an order. $item can be the OrderItem or an Item Identifier
	 * @param string | \Maven\Core\Domain\OrderItem $item
	 * @return \Maven\Core\Message\Message message
	 */
	public function removeItem ( $item ) {

		if ( is_object( $item ) ) {
			\Maven\Loggers\Logger::log()->message( 'Maven/Cart/removeItem: Object: ' . $item->getIdentifier() );
		} else {
			\Maven\Loggers\Logger::log()->message( 'Maven/Cart/removeItem: Identifier: ' . $item );
		}

		$item = is_object( $item ) ? $item : $this->getOrder()->getItem( $item );

		$orderApi = new OrdersApi( );

//		die(print_r($this->getOrder()->getItems(),true));
		//TODO: Check if the item exists, we have to remove it and add the new one.
		if ( $this->getOrder()->itemExists( $item->getIdentifier() ) ) {
			$orderApi->removeItem( $this->order, $item );

			$this->update();

			return Message\MessageManager::createRegularMessage( 'Item removed sucessfully', $this->order );
		}

		return Message\MessageManager::createRegularMessage( 'Item not found', $this->order );
	}

	/**
	 * Return a new order item. There is no need to use "addToCart" later, because it's already added
	 * @param string $pluginKey
	 * @return \Maven\Core\Domain\OrderItem
	 */
	public function newItem ( $pluginKey ) {

		\Maven\Loggers\Logger::log()->message( 'Maven/Cart/newItem' );

		$item = new \Maven\Core\Domain\OrderItem( $pluginKey );

		return $item;
	}

	/**
	 * Verify if the cart has items or not
	 * @return boolean
	 */
	public function hasItems () {

		$order = $this->getOrder();

		if ( !$order ) {
			return false;
		}

		return $order->hasItems();
	}

	/**
	 * Gets data collected of the cart
	 * 
	 * @return array
	 */
	public function getCartInfo () {

		$data = array(
			'itemsCount' => 0,
			'total' => 0
		);

		$order = $this->getOrder();

		if ( !$order ) {
			return $data;
		}

		$data[ 'itemsCount' ] = count( $order->getItems() );
		$data[ 'total' ] = $order->getTotal();

		return $data;
	}

	public function getItemsCount () {
		$order = $this->getOrder();

		if ( !$order ) {
			return 0;
		}

		return count( $order->getItems() );
	}

	/**
	 * Pay the order
	 * @return \Maven\Core\Message\Message
	 */
	public function pay () {

		//Check if the cc information is valid
		$order = $this->getOrder();

		\Maven\Loggers\Logger::log()->message( 'Maven/Cart/pay: Order: ' . $order->getId() . ' Start:' . date( 'h:i:s' ) );


		if ( !$order->getCreditCard() || !$order->getCreditCard()->isValid() ) {
			return $this->setResult( Message\MessageManager::createErrorMessage( 'Invalid credit card' ) );
		}

		$promotionApi = new \Maven\Core\PromotionsApi();
		$invalidPromotions = array();
		//Check if promotions are still valids
		foreach ( $order->getPromotions() as $promotion ) {
			$valid = $promotionApi->isValid( $promotion->getCode() );
			if ( $valid->isError() ) {
				$invalidPromotions[] = $promotion;
			}
		}

		if ( !empty( $invalidPromotions ) ) {
			//some promotions are no longer valid, maybe they are expired
			// or use limit has been reached.
			//Remove the promotion from the order
			foreach ( $invalidPromotions as $promo ) {
				$order->removePromotion( $promo );
			}
			$this->update();

			//notify the user, that order has changed
			$result = $this->setResult( Message\MessageManager::createErrorMessage( 'One or more promotions codes are no longer valid. Please review your order total.' ) );

			\Maven\Loggers\Logger::log()->message( 'Maven/Cart/pay: Order: ' . $order->getId() . ' Promotions invalid error: ' . date( 'h:i:s' ) );

			return $result;
		}

		// First we need to save the order
		//$this->update();
		// Get the gateway 
		$gateway = \Maven\Gateways\GatewayFactory::getGateway( \Maven\Settings\MavenRegistry::instance() );

		// Contact information
		$gateway->setFirstName( $order->getBillingContact()->getFirstName() );
		$gateway->setLastName( $order->getBillingContact()->getLastName() );
		$gateway->setCity( $order->getBillingContact()->getBillingAddress()->getCity() );
		$gateway->setState( $order->getBillingContact()->getBillingAddress()->getState() );
		$gateway->setCountry( $order->getBillingContact()->getBillingAddress()->getCountry() );
		$gateway->setAddress( $order->getBillingContact()->getBillingAddress()->getFirstLine() . ", " . $order->getBillingContact()->getBillingAddress()->getSecondLine() );
		$gateway->setEmail( $order->getBillingContact()->getEmail() );
		$gateway->setPhone( $order->getBillingContact()->getBillingAddress()->getPhone() );
		$gateway->setZip( $order->getBillingContact()->getBillingAddress()->getZipcode() );
		$gateway->setAmount( $order->getTotal() );
		$gateway->setShippingAmount( $order->getShippingAmount() );

		$gateway->setDescription( $order->getDescription() );

		// Lets add the items
		$items = $order->getItems();

		foreach ( $items as $item ) {

			$gatewayItem = new \Maven\Gateways\GatewayOrderItem();
			$gatewayItem->setName( $item->getName() );
			$gatewayItem->setItemId( $item->getId() );
			$gatewayItem->setQuantity( $item->getQuantity() );
			$gatewayItem->setUnitPrice( $item->getPrice() );

			$gateway->addOrderItem( $gatewayItem );
		}

		$gateway->setDiscountAmount( $order->getDiscountAmount() );

		// Credit card information
		$gateway->setCCHolderName( $order->getCreditCard()->getHolderName() );
		$gateway->setCCMonth( $order->getCreditCard()->getMonth() );
		$gateway->setCCYear( $order->getCreditCard()->getYear() );
		$gateway->setCCVerificationCode( $order->getCreditCard()->getSecurityCode() );
		$gateway->setCCNumber( $order->getCreditCard()->getNumber() );
		$gateway->setCcType( $order->getCreditCard()->getType() );

		\Maven\Loggers\Logger::log()->message( 'Maven/Cart/pay: Order: ' . $order->getId() . ' Gateway Execute:' . date( 'h:i:s' ) );
		//if ( $order->getTotal() !== 0 ) {
		$gateway->execute();
		//}

		\Maven\Loggers\Logger::log()->message( 'Maven/Cart/pay: Order: ' . $order->getId() . ' Gateway Finish Execute:' . date( 'h:i:s' ) );

		// If it was approved, then we need to clean the order
		if ( $gateway->isApproved() ) {//|| $order->getTotal() === 0 ) {
			$order->setStatus( OrdersApi::getCompletedStatus() );

			$order->setTransactionId( $gateway->getTransactionId() );

			//update promotions user count
			foreach ( $order->getPromotions() as $promotion ) {
				$promotionApi->usePromotion( $promotion->getCode() );
			}

			$this->saveOrder();

			//send mails
			$this->sendEmail( $order );

			\Maven\Loggers\Logger::log()->message( 'Maven/Cart/pay: Order: ' . $order->getId() . ' Completed Success: ' . date( 'h:i:s' ) );

			// Track the order 
			$this->trackTransaction( $order );

			$result = $this->setResult( Message\MessageManager::createRegularMessage( 'Success' ), $order );

			$items = $this->order->getItems();
			foreach ( $items as $item ) {
				HookManager::instance()->doAction( "maven/cart/itemPaid/{$item->getPluginKey()}", $item );
			}

			HookManager::instance()->doAction( 'maven/cart/orderPaid', $this->order );

			//Lets clean the order
			$this->clear();

			return $result;
		} else {

			// If it was an error we need to save the error and the status. 
			$status = OrdersApi::getErrorStatus();
			$status->setStatusDescription( $gateway->getErrorDescription() );

			$order->setStatus( $status );
			$this->saveOrder();
		}

		//send email on invalid transaction
		$this->sendInvalidTransactionEmail( $order, $gateway );

		$result = $this->setResult( Message\MessageManager::createErrorMessage( $gateway->getErrorDescription() ) );

		\Maven\Loggers\Logger::log()->message( 'Maven/Cart/pay: Order: ' . $order->getId() . ' Completed error: ' . date( 'h:i:s' ) );

		return $result;
	}

	/**
	 * @label Add or update an Attendee
	 * @action updateAttendee
	 * @description It will fire when an attendee is added
	 * @param \MavenEvents\Core\Domain\Attendee $attendee
	 */
	public function trackTransaction ( \Maven\Core\Domain\Order $order ) {

		$transaction = new \Maven\Tracking\EcommerceTransaction();
		$transaction->setTotal( $order->getTotal() );
		$transaction->setOrderId( $order->getNumber() );
		$transaction->setShipping( $order->getShippingAmount() );
		$transaction->setTaxes( $order->getTaxes() );

		if ( $order->hasItems() ) {

			foreach ( $order->getItems() as $item ) {
				$transactionItem = new \Maven\Tracking\EcommerceItem();
				$transactionItem->setName( $item->getName() );
				$transactionItem->setOrderId( $order->getNumber() );
				$transactionItem->setPrice( $item->getPrice() );
				$transactionItem->setQuantity( $item->getQuantity() );
				$transactionItem->setSku( $item->getIdentifier() );

				$transaction->addItem( $transactionItem );
			}
		}

		\Maven\Tracking\Tracker::addTransaction( $transaction );

		//do_action('action:mavenEvents/attendee/add', $attendee);
	}

	public function sendInvalidTransactionEmail ( \Maven\Core\Domain\Order $order, \Maven\Gateways\Gateway $gateway ) {
		$mavenSettings = \Maven\Settings\MavenRegistry::instance();

		$admin = TRUE;
		$output = new Ui\Output( "", array(
			'order' => $order,
			'admin' => $admin,
			'gateway' => $gateway )
		);

		$message = $output->getTemplate( 'email-invalid-transaction.html' );

		$mail = \Maven\Mail\MailFactory::build();
		$mail->to( $mavenSettings->getExceptionNotification() )
				->message( $message )
				->subject( $mavenSettings->getLanguage()->__( $mavenSettings->getOrganizationName() . ': Transaction Error' ) )
				->fromAccount( $mavenSettings->getSenderEmail() )
				->fromMessage( $mavenSettings->getSenderName() )
				->send();
	}

	/**
	 * 
	 * TODO// Todo esto hay que moverlo a una clase que se encargue del parseo
	 */
	public function sendEmail ( \Maven\Core\Domain\Order $order ) {

		$mavenSettings = \Maven\Settings\MavenRegistry::instance();

		//Process the form
		$url = $mavenSettings->getPluginUrl() . 'templates';
		$admin = FALSE;

		$output = new Ui\Output( "", array(
			'order' => $order,
			'url' => $url,
			'admin' => $admin )
		);

		$emailReceipt = TemplateProcessor::DefaultEmailReceipt;

		$emailReceiptFullPath = HookManager::instance()->applyFilters( 'maven/cart/emailReceiptTemplateFullPath', '' );

		$useTemplate = \Maven\Core\Utils::isEmpty( $emailReceiptFullPath );

		$message = $emailReceiptFullPath ? $output->getExternalTemplate( $emailReceiptFullPath ) : $output->getTemplate( $emailReceipt );

		$subject = "Receipt for Order " . $order->getNumber();
		$subject = HookManager::instance()->applyFilters( 'maven/cart/receipOrderSubject', $subject );

		$mail = \Maven\Mail\MailFactory::build();
		$mail->bcc( $mavenSettings->getBccNotificationsTo() )
				->useTemplate( $useTemplate )
				->to( $order->getContact()->getEmail() )
				->message( $message )
				->subject( $subject )
				->fromAccount( $mavenSettings->getSenderEmail() )
				->fromMessage( $mavenSettings->getSenderName() )
				->send();
		//Notify admins
		$this->sendNotificationEmail( $order, 'New order placed' );
	}

	private function sendNotificationEmail ( \Maven\Core\Domain\Order $order, $subject ) {

		$mavenSettings = \Maven\Settings\MavenRegistry::instance();


		//Process the form
		$url = $mavenSettings->getPluginUrl() . 'templates';
		$admin = TRUE;

		$output = new Ui\Output( "", array(
			'order' => $order,
			'url' => $url,
			'admin' => $admin )
		);

		$emailReceipt = TemplateProcessor::DefaultEmailReceipt;

		$emailReceiptFullPath = HookManager::instance()->applyFilters( 'maven/cart/emailReceiptTemplateFullPath', '' );

		$useTemplate = \Maven\Core\Utils::isEmpty( $emailReceiptFullPath );

		$message = $emailReceiptFullPath ? $output->getExternalTemplate( $emailReceiptFullPath ) : $output->getTemplate( $emailReceipt );


		$mail = \Maven\Mail\MailFactory::build();
		$mail->to( $mavenSettings->getBccNotificationsTo() )
				->useTemplate( $useTemplate )
				->message( $message )
				->subject( $mavenSettings->getLanguage()->__( $subject ) )
				->fromAccount( $mavenSettings->getSenderEmail() )
				->fromMessage( $mavenSettings->getSenderName() )
				->send();
	}

	private function saveOrder ( $addStatus = true ) {

		\Maven\Loggers\Logger::log()->message( 'Maven/Cart/saveOrder' );

		$order = $this->getOrder();

		$orderApi = new OrdersApi( );

		return $orderApi->addOrder( $order, $addStatus );
	}

	/**
	 * Update order
	 * @param \Maven\Core\Domain\Order $orderToSave
	 * @return \Maven\Core\Domain\Oorder
	 * @throws \Maven\Exceptions\MavenException
	 */
	public function update ( \Maven\Core\Domain\Order $orderToSave = null ) {

		\Maven\Loggers\Logger::log()->message( 'Maven/Cart/update' );

		//TODO: We have to validate if the order is the same as we have in session
		//Validate the number. It's a poor validation we have to improve it. Maybe with a hash.
		$order = $this->getOrder();



		if ( !$order ) {
			throw new \Maven\Exceptions\MavenException( ' The order was not initialized' );
		}

		$session = \Maven\Session\SessionManager::get();

		$this->saveOrder( false );

		$session->addData( $this->getSessionKey(), $order );

		return $this->order;
	}

	public function clear () {

		\Maven\Loggers\Logger::log()->message( 'Maven/Cart/clear' );

		$this->order = null;

		$session = \Maven\Session\SessionManager::get();

		$session->removeData( $this->getSessionKey() );
	}

	/**
	 * Apply a promotion code if it is valid
	 * @param string $promotionCode
	 * @return boolean
	 */
	public function applyPromotion ( $promotionCode ) {

		$order = $this->getOrder();

		if ( $order->isPromotionAdded( $promotionCode ) ) {

			$this->result = Message\MessageManager::createErrorMessage( 'Promotion already added' );

			return $this->result;
		}

		$promotionApi = new \Maven\Core\PromotionsApi( );

		$result = $promotionApi->isValid( $promotionCode );

		if ( $result->isSuccessful() ) {

			$promotionApi->applyPromotion( $promotionCode, $order );

			$this->update();

			return Message\MessageManager::createSuccessfulMessage( 'Promotion added' );
		} else {
			$this->result = Message\MessageManager::createErrorMessage( 'Invalid promotion' );
		}

		$this->result = $result;

		return $this->result;
	}

	private function loadUserInformation ( Domain\User $user ) {

		\Maven\Loggers\Logger::log()->message( 'Maven/Cart/loadUserLoggedProfile: User found: ' . $user->getId() );


		if ( $user->hasProfile() ) {

			\Maven\Loggers\Logger::log()->message( 'Maven/Cart/loadUserLoggedProfile: Inside' );


			if ( !$this->order->getContact()->isCompleted() ) {

				\Maven\Loggers\Logger::log()->message( 'Maven/Cart/loadUserLoggedProfile: Empty contact' );

				$this->order->setContact( $user->getProfile()->copy() );

				//if (  $user->getProfile()->hasAddress( Domain\AddressType::Home ) ){
				$this->order->getContact()->setHomeAddress( $user->getProfile()->getHomeAddress()->copy() );
				//}

				\Maven\Loggers\Logger::log()->message( 'Maven/Cart/loadUserLoggedProfile: Contact- ' . $this->order->getContact()->getHomeAddress()->getFullAddress() );
			}


			if ( !$this->order->getBillingContact()->isCompleted() ) {

				\Maven\Loggers\Logger::log()->message( 'Maven/Cart/loadUserLoggedProfile: Empty Billing Contact' );

				$this->order->setBillingContact( $user->getProfile()->copy() );

				//if (  $user->getProfile()->hasAddress( Domain\AddressType::Billing ) ){
				$this->order->getBillingContact()->setBillingAddress( $user->getProfile()->getBillingAddress()->copy() );
				//}

				\Maven\Loggers\Logger::log()->message( 'Maven/Cart/loadUserLoggedProfile: Billing Contact- ' . $this->order->getBillingContact()->getBillingAddress()->getFullAddress() );
			}

			if ( !$this->order->getShippingContact()->isCompleted() ) {

				\Maven\Loggers\Logger::log()->message( 'Maven/Cart/loadUserLoggedProfile: Empty Shipping Contact' );

				$this->order->setShippingContact( $user->getProfile()->copy() );

				//if (  $user->getProfile()->hasAddress( Domain\AddressType::Shipping ) ){
				$this->order->getShippingContact()->setShippingAddress( $user->getProfile()->getShippingAddress()->copy() );
				//}

				\Maven\Loggers\Logger::log()->message( 'Maven/Cart/loadUserLoggedProfile: Shipping Contact- ' . $this->order->getShippingContact()->getShippingAddress()->getFullAddress() );
			}
		} else {
			\Maven\Loggers\Logger::log()->message( 'Maven/Cart/loadUserLoggedProfile: User without profile: ' . $user->getId() );
		}

		// We need to ensure that the order will be placed to the logged user. Since he could start it without being logged.
		$this->order->setUser( $user );
	}

	private function loadUserLoggedProfile () {

		\Maven\Loggers\Logger::log()->message( 'Maven/Cart/loadUserLoggedProfile' );

		$user = false;

		if ( UserManager::isUserLoggedIn() ) {
			$user = UserManager::getLoggedUser();
		}

		if ( !$user ) {
			\Maven\Loggers\Logger::log()->message( 'Maven/Cart/loadUserLoggedProfile: User not found' );
			return;
		}

		$this->loadUserInformation( $user );
	}

	public function logout () {

		// If the user is being loged out, we need to clean the cart
		$this->clear();
	}

	public function login ( $userLogin, $wpUser ) {

		if ( !$userLogin ) {
			return false;
		}

		\Maven\Loggers\Logger::log()->message( 'Maven/Cart/login: ' . $userLogin );

		$user = UserManager::getUserByLogin( $userLogin );

		\Maven\Loggers\Logger::log()->message( 'Maven/Cart/login: User: ' . $user->getId() . " Email: " . $user->getEmail() );

		$order = $this->order;

		// If there is no order in session, lets try to find sometning in the db
		if ( !$this->hasOrder() || $this->order->isEmpty() ) {

			$orderManager = new OrderManager();
			$order = $orderManager->getLastPendingOrder( $user->getId() );

			if ( $order && !$order->isEmpty() ) {
				\Maven\Loggers\Logger::log()->message( 'Maven/Cart/login: Order Id: ' . $order->getId() );

				// We need to ensure that the order will be placed to the logged user. Since he could start it without being logged.
				$order->setUser( $user );

				$this->newOrder( $order );
			}
		}


		if ( ( $this->order && $this->order->isEmpty() ) ) {

			\Maven\Loggers\Logger::log()->message( 'Maven/Cart/login: Empty Order' );

			//Does the user has a profile ? If so, lets populate the order with the profile
			if ( $user->getProfile() ) {
				$this->loadUserInformation( $user );
			}
		}
	}

	public function removePromotion ( $promotionCode ) {

		$order = $this->getOrder();

		$promotionApi = new \Maven\Core\PromotionsApi( );

		$result = $promotionApi->isValid( $promotionCode );

		if ( $result->isSuccessful() ) {

			$promotionApi->removePromotion( $promotionCode, $order );

			$this->update();

			return true;
		}

		$this->result = $result;

		return false;
	}

	public function hasOrder () {

		return $this->getOrder() ? true : false;
	}

}
