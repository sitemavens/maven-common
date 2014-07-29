<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class OrdersApi {

	/**
	 * 
	 * @return Maven\Core\Domain\OrderStatus
	 */
	public static function getCancelledStatus() {
		return OrderStatusManager::getCancelledStatus();
	}

	/**
	 * 
	 * @return Maven\Core\Domain\OrderStatus
	 */
	public static function getCompletedStatus() {
		return OrderStatusManager::getCompletedStatus();
	}

	/**
	 * 
	 * @return Maven\Core\Domain\OrderStatus
	 */
	public static function getDeclinedStatus() {
		return OrderStatusManager::getDeclinedStatus();
	}

	/**
	 * 
	 * @return Maven\Core\Domain\OrderStatus
	 */
	public static function getErrorStatus() {
		return OrderStatusManager::getErrorStatus();
	}

	/**
	 * 
	 * @return Maven\Core\Domain\OrderStatus
	 */
	public static function getPendingStatus() {
		return OrderStatusManager::getPendingStatus();
	}

	/**
	 * 
	 * @return Maven\Core\Domain\OrderStatus
	 */
	public static function getOnHoldStatus() {
		return OrderStatusManager::getOnHoldStatus();
	}

	/**
	 * 
	 * @return Maven\Core\Domain\OrderStatus
	 */
	public static function getProcessingStatus() {
		return OrderStatusManager::getProcessingStatus();
	}

	/**
	 * 
	 * @return Maven\Core\Domain\OrderStatus
	 */
	public static function getReadyToShipStatus() {
		return OrderStatusManager::getReadyToShipStatus();
	}

	/**
	 * 
	 * @return Maven\Core\Domain\OrderStatus
	 */
	public static function getRefundedStatus() {
		return OrderStatusManager::getRefundedStatus();
	}

	/**
	 * 
	 * @return Maven\Core\Domain\OrderStatus
	 */
	public static function getShippedStatus() {
		return OrderStatusManager::getShippedStatus();
	}

	/**
	 * 
	 * @return Maven\Core\Domain\OrderStatus
	 */
	public static function getVoidedStatus() {
		return OrderStatusManager::getVoidedStatus();
	}

	private $manager = null;

	public function __construct() {

		$this->manager = new OrderManager( );
	}

	/**
	 * Return orders for the current logged in user
	 * 
	 * @return \Maven\Core\Domain\Order[]
	 */
	public function getCurrentUserOrders() {

		if ( UserManager::isUserLoggedIn() ) {

			$user = UserManager::getLoggedUser();

			$filter = new Domain\OrderFilter();

			$filter->setUserID( $user->getId() );

			$status = array();
			$status[] = OrderStatusManager::getCompletedStatus()->getName();
			$status[] = OrderStatusManager::getShippedStatus()->getName();

			$filter->setStatusID( $status );

			$orders = $this->manager->getOrders( $filter, 'id', 'desc' );

			return $orders;
		}
		return false;
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\OrderStatus $status
	 * @return \Maven\Core\Domain\Order[]
	 */
	public function getAllOrders( Domain\OrderStatus $status = NULL ) {


		$filter = new Domain\OrderFilter();

		$filter->setPluginKey( $this->registry->getPluginKey() );

		if ( ! is_null( $status ) ) {
			$filter->setStatusID( $status->getId() );
		}
		//return $this->manager->getByPlugin( $this->registry->getPluginKey() );
		return $this->manager->getOrders( $filter );
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\Order[]
	 */
	public function getCompletedOrders() {
		return $this->getAllOrders( OrderStatusManager::getCompletedStatus() );
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\OrderFilter $filter
	 * @return \Maven\Core\Domain\Order[]
	 */
	public function getOrders( \Maven\Core\Domain\OrderFilter $filter ) {


		$filter->setPluginKey( $this->registry->getPluginKey() );

		return $this->manager->getOrders( $filter );
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\Order
	 */
	public function newOrder() {

		return $this->manager->initOrder();
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Order $order
	 * @return type
	 */
	public function addOrder( \Maven\Core\Domain\Order $order, $addStatus = true ) {

		
		\Maven\Loggers\Logger::log()->message( 'Maven/Core/OrdersApi/addOrder: Add Status:' .  $addStatus?'true':'false' );

		$status = $order->getStatus() && $order->getStatus()->getId() ? $order->getStatus()->getId() : 'empty';

		\Maven\Loggers\Logger::log()->message( 'Maven/Core/OrdersApi/addOrder: Status id:' . $status );

		return $this->manager->addOrder( $order, $addStatus );
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Order $order
	 * @param \Maven\Core\Domain\OrderItem $item
	 */
	public function addItem( \Maven\Core\Domain\Order $order, \Maven\Core\Domain\OrderItem $item ) {
		$this->manager->addItem( $order, $item );
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Order $order
	 * @param \Maven\Core\Domain\OrderItem $item
	 */
	public function removeItem( \Maven\Core\Domain\Order $order, \Maven\Core\Domain\OrderItem $item ) {
		$this->manager->removeItem( $order, $item );
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Order $order
	 * @param \Maven\Core\Domain\ExtraField $extraField
	 */
	public function addExtraField( \Maven\Core\Domain\Order $order, \Maven\Core\Domain\ExtraField $extraField ) {
		$this->manager->addExtraField( $order, $extraField );
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Order $order
	 * @param \Maven\Core\Domain\ExtraField $extraField
	 */
	public function removeExtraField( \Maven\Core\Domain\Order $order, \Maven\Core\Domain\ExtraField $extraField ) {
		$this->manager->removeExtraField( $order, $extraField );
	}

	/**
	 * 
	 * @param int/object $orderId
	 */
	public function getOrder( $orderId ) {

		if ( ! $orderId )
			throw new \Maven\Exceptions\MissingParameterException( 'Order ID is required.' );

		$order = $this->manager->get( $orderId );

		return $order;
	}

	public function getStatuses() {

		$statusManager = new \Maven\Core\OrderStatusManager();
		return $statusManager->getAll();
	}

	/**
	 * 
	 * @param int/object $orderId
	 */
	public function delete( $orderId ) {

		return $this->manager->delete( $orderId );
	}

}
