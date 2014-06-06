<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class OrderManager {

	private $mapper;

	public function __construct() {

		$this->mapper = new Mappers\OrderMapper( );
	}

	public function initOrder() {

		$order = new \Maven\Core\Domain\Order();

		$order->setStatus( OrderStatusManager::getReceivedStatus() );

		if ( UserManager::isUserLoggedIn() ) {
			$order->setUser( UserManager::getLoggedUser() );
			$order->getStatus()->setStatusDescription( "User " . $order->getUser()->getEmail() . " placed an order" );

			if ( ! $order->getUser()->getProfile()->isEmpty() ) {
				$order->setContactId( $order->getUser()->getProfile()->getProfileId() );
			}
		}

		$defaultShippingCountry = HookManager::instance()->applyFilters( 'maven/order/defaultShippingCountry', "" );

		if ( ! $order->getShippingContact() ) {
			$order->setShippingContact( new Domain\Contact() );
		}

		$order->getShippingContact()->getShippingAddress()->setCountry( $defaultShippingCountry );

		return $this->addOrder( $order );
	}

	public function removeItem( \Maven\Core\Domain\Order $order, \Maven\Core\Domain\OrderItem $item ) {

		$order->removeItem( $item->getIdentifier() );

		$this->reCalculateOrderTotals( $order );

		return $order;
	}

	public function removeExtraField( \Maven\Core\Domain\Order $order, \Maven\Core\Domain\ExtraField $extraField ) {

		$order->removeExtraField( $extraField->getLabel() );

		return $order;
	}

	/**
	 * Recalculate the promotions and taxes when an item changed
	 * @param \Maven\Core\Domain\Order $order
	 * @param \Maven\Core\Domain\OrderItem $item
	 * @return boolean
	 */
	public function reCalculateOrderTotals( \Maven\Core\Domain\Order $order ) {

		\Maven\Loggers\Logger::log()->message('Maven/OrderManager/reCalculateOrderTotals' );

		// Recalculate the promotions
		//$promotionManager = new PromotionManager( );

		$order->recalculateSubtotal();
		
		// First we need to reset the total amount 
		$order->setTotal( $order->getSubtotal() );

		//$promotionManager->reCalculatePromotions( $order );

		$taxesManager = new TaxManager( );

		//Calculate taxes
		$taxesManager->applyTaxes( $order );

		// Recalculate the total amount
		$order->calculateTotal();

		// We need to verify if there is a shipping method available
		$this->applyShipping( $order );

		return $order;
	}

	public function addItem( \Maven\Core\Domain\Order $order, \Maven\Core\Domain\OrderItem $item ) {

		if ( $order->itemExists( $item->getIdentifier() ) ) {
			return $order;
		}

		// Add the item
		$order->addItem( $item );

		$this->reCalculateOrderTotals( $order );

		return $order;
	}

	public function addExtraField( \Maven\Core\Domain\Order $order, \Maven\Core\Domain\ExtraField $extrafield ) {

		if ( $order->extraFieldExists( $extrafield->getLabel() ) ) {
			return $order;
		}

		// Add the extra Field
		$order->addExtraField( $extrafield );

		return $order;
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Order $order
	 * @return \Maven\Core\Domain\Order
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function addOrder( \Maven\Core\Domain\Order $order, $addStatus = true ) {

		\Maven\Loggers\Logger::log()->message('Maven/OrderManager/addOrder ' );

		if ( ! ($order->getStatus() && $order->getStatus()->getId() ) ) {
			throw new \Maven\Exceptions\MissingParameterException( "Order Status is required" );
		}


		if ( ! $order->getOrderDate() ) {
			$date = new MavenDateTime();

			$order->setOrderDate( $date->mySqlFormatDateTime() );
		}

		// If the contact is not an existing one, we have to verify 
		// We set the contacts id to make search easier and faster
		if ( $order->getContact()->getEmail() && ! $order->getContact()->getId() ) {

			$verifiedContact = $this->verifyProfile( $order->getContact() );
			$order->setContact( $verifiedContact );
			$order->setContactId( $order->getContact()->getId() );
		}

		if ( $order->getBillingContact()->getEmail() && ! $order->getBillingContact()->getId() ) {

			$verifiedBillingContact = $this->verifyProfile( $order->getBillingContact() );
			$order->setBillingContact( $verifiedBillingContact );
			$order->setBillingContactId( $order->getBillingContact()->getId() );
		}

		if ( $order->getShippingContact()->getEmail() && ! $order->getShippingContact()->getId() ) {

			$verifiedShippingContact = $this->verifyProfile( $order->getShippingContact() );
			$order->setShippingContact( $verifiedShippingContact );
			$order->setShippingContactId( $order->getShippingContact()->getId() );
		}

		// We need to update the contact/billing/shipping information in case something has changed
		$this->updateProfile( $order->getBillingContact() );

		$this->updateProfile( $order->getShippingContact() );
		$this->updateProfile( $order->getContact() );

		$this->reCalculateOrderTotals( $order );

		$order = $this->mapper->save( $order );
		if ( $addStatus ) {

			// If the status is completed, then we need to generate the order number
			if ( $order->getStatus()->getId() == OrderStatusManager::getCompletedStatus()->getId() ) {
				HookManager::instance()->doAction( 'maven/order/completed', $order );
				$this->updateOrderNumber( $order );
			}

			$orderStatusManager = new OrderStatusManager();
			$orderStatusManager->addStatus( $order->getStatus(), $order );
		}



		return $order;
	}

	
	public function sendShipmentNotice( Domain\Order $order ) {

		try {
			$shippingStatus = OrderStatusManager::getShippedStatus();

			$shippingStatus->setStatusDescription( "Shipped via {$order->getShippingCarrier()} with tracking code {$order->getShippingTrackingCode()}." );

			//Send the shipment notice
			//If is a registered user 
			if ( $order->hasUserInformation() ) {
				$email = $order->getUser()->getEmail();
				$firstName = $order->getUser()->getFirstName();
			} else {
				$email = $order->getContact()->getEmail();
				$firstName = $order->getContact()->getFirstName();
			}

			$items = "";
			foreach ( $order->getItems() as $item ) {
				$items = $items . "<li>{$item->getName()}</li>";
			}
			$items = "<ul>{$items}</ul>";

			$variables = array(
			    'first_name' => $firstName,
			    'order_number' => $order->getNumber(),
			    'items' => $items,
			    'carrier' => $order->getShippingCarrier(),
			    'tracking_code' => $order->getShippingTrackingCode(),
			    'tracking_code_url' => $order->getShippingTrackingUrl()
			);

			$mavenSettings = \Maven\Settings\MavenRegistry::instance();
			$template = \Maven\Core\Loader::getFileContent( $mavenSettings->getTemplatePath() . 'shipping/shipping-notice.html' );
			$templateProcesor = new \Maven\Core\TemplateProcessor( $template, $variables );

			$message = $templateProcesor->getProcessedTemplate();

			$mail = \Maven\Mail\MailFactory::build();

			$mail->to( $email )
				->bcc( $mavenSettings->getBccNotificationsTo() )
				->message( $message )
				->subject( 'Your order has been shipped' )
				->fromAccount( $mavenSettings->getSenderEmail() )
				->fromMessage( $mavenSettings->getSenderName() )
				->send();

			return $shippingStatus;
		} catch ( \Exception $e ) {
			\Maven\Loggers\Logger::log($e->message);
			return false;
		}
	}

	/**
	 * Update order number adding +1
	 * @param \Maven\Core\Domain\Order $order
	 * @return \Maven\Core\Domain\Order
	 */
	public function updateOrderNumber( \Maven\Core\Domain\Order $order ) {

		$orderNumber = $this->mapper->updateOrderNumber( $order->getId() );

		$order->setNumber( $orderNumber );

		return $order;
	}

	private function applyShipping( \Maven\Core\Domain\Order $order ) {

		$shippingMethodManager = new ShippingMethodManager();
		$shippingAddress = $order->getShippingContact()->getShippingAddress();
		$shippingAmount = 0;

		if ( $order->getShippingMethod() ) {

			$shippingMethod = $order->getShippingMethod();

			$shippingAmount = $shippingMethodManager->findShippingAmount( $order->getSubtotal(), $shippingAddress->getCountry(), $shippingAddress->getState(), $shippingMethod );
		} else if ( $order->getShippingContact()->getShippingAddress()->getCountry() ) {
			$shippingAmount = $shippingMethodManager->findShippingAmount( $order->getSubtotal(), $shippingAddress->getCountry(), $shippingAddress->getState() );
		}

		$order->setShippingAmount( $shippingAmount );
		\Maven\Loggers\Logger::log()->message('Maven/OrderManager/applyShipping: Amount: '.$shippingAmount );
		
		$order->calculateTotal();
	}

	private function updateProfile( \Maven\Core\Domain\Profile $profile ) {

		if ( $profile && $profile->getEmail() ) {
			$profileManager = new ProfileManager();
			$profile = $profileManager->updateProfile( $profile );

			return $profile;
		}
	}

	/**
	 * Verify if a profile already exists. If exists return, if not it inserts the contact
	 * @param \Maven\Core\Domain\Contact $contact
	 * @return \Maven\Core\Domain\Contact
	 */
	private function verifyProfile( \Maven\Core\Domain\Profile $contact ) {

		$profileManager = new ProfileManager();

		// Check if the profile exists
		$existingProfileId = $profileManager->exists( $contact->getEmail() );

		if ( ! $existingProfileId ) {
			$profile = $profileManager->addProfile( $contact );

			$existingProfileId = $profile->getId();
		}

		$contact->setId( $existingProfileId );

		return $contact;
	}

	public function getOrderLastUpdate( $orderId ) {
		if ( ! $orderId ) {
			throw new \Maven\Exceptions\MissingParameterException( "Order id is required" );
		}

		return $this->mapper->getOrderLastUpdate( $orderId );
	}

	/**
	 * Check if order id exist in the database
	 * 
	 * @param mixed $orderId
	 * @return boolean
	 */
	public function orderExists( $orderId ) {
		return $this->mapper->orderExist( $orderId );
	}

	/**
	 * 
	 * @param mixed $orderId
	 * @return \Maven\Core\Domain\Order
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function get( $orderId ) {

		if ( ! $orderId ) {
			throw new \Maven\Exceptions\MissingParameterException( "Event id is required" );
		}

		$order = $this->mapper->get( $orderId );

//		$contactManager = new ContactManager();
		$orderStatusManager = new OrderStatusManager();
//
//		if ( $order->getContactId() ) {
//			//Get the contact information
//			$contact = $contactManager->get( $order->getContactId() );
//
//			if ( $contact ) {
//				$order->setContact( $contact );
//			}
//		}

		$order->setStatusHistory( $orderStatusManager->getOrderHistory( $order->getId() ) );

		return $order;
	}

	/**
	 * Return the last pending order
	 * @param int $userId
	 * @return \Maven\Core\Domain\Order
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function getLastPendingOrder( $userId ) {

		return $this->mapper->getLastPendingOrder( $userId );
	}

	public function getByPlugin( $pluginKey ) {

		if ( ! $pluginKey ) {
			throw new \Maven\Exceptions\MissingParameterException( "Plugin Key is required" );
		}

		return $this->mapper->getByPlugin( $pluginKey );
	}

	/**
	 * Get orders by filter
	 * @param \Maven\Core\Domain\OrderFilter  $filter
	 * @return \Maven\Core\Domain\Order[]
	 */
	public function getOrders( \Maven\Core\Domain\OrderFilter $filter, $orderBy = "id", $orderType = 'asc', $start = "0", $limit = "1000" ) {

		//if ( ! $filter->getPluginKey() )
		//	throw new \Maven\Exceptions\MissingParameterException( "Plugin Key is required" );

		return $this->mapper->getOrders( $filter, $orderBy, $orderType, $start, $limit );
	}

	public function getOrdersCount( \Maven\Core\Domain\OrderFilter $filter ) {
		//if ( ! $filter->getPluginKey() )
		//	throw new \Maven\Exceptions\MissingParameterException( "Plugin Key is required" );

		return $this->mapper->getOrdersCount( $filter );
	}

	public function delete( $orderId ) {

		$this->mapper->delete( $orderId );

		$orderStatusMapper = new OrderStatusManager();

		$orderStatusMapper->removeOrderHistory( $orderId );

		return true;
	}

	/**
	 * 
	 * @param string $status
	 * @param MavenDateTime $from
	 * @param MavenDateTime $to
	 * @return int
	 */
	public function getCount( $status, $from = false, $to = false ) {
		return $this->mapper->getCount( $status, $from, $to );
	}

	/**
	 * 
	 * @param MavenDateTime $from
	 * @param MavenDateTime $to
	 * @return int
	 */
	public function getRevenue( $status, $from = false, $to = false ) {
		return $this->mapper->getRevenue( $status, $from, $to );
	}

	/**
	 * Remove all received orders that are older than a week and has no information
	 */
	public function cleanReceivedOrders() {

		$filter = new \Maven\Core\Domain\OrderFilter();
		$filter->setStatusID( OrderStatusManager::getReceivedStatus()->getId() );

		// A week old
		$today = MavenDateTime::getWPCurrentDateTime();

		$toDate = new \Maven\Core\MavenDateTime( $today );
		$toDate->subFromInterval( 'P1W' );

		$filter->setOrderDateTo( $toDate->mySqlFormatDate() );

		$orders = $this->getOrders( $filter );

		foreach ( $orders as $order ) {

			//Check if the order has some contact on it.
			if ( ! $order->hasBillingInformation() && ! $order->hasShippingInformation() && ! $order->hasContactInformation() ) {
				$this->delete( $order->getId() );
			}
		}
	}

}
