<?php


namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class OrderStatusManager {
	
	public static function getCancelledStatus() {
		return self::getStatus( 'cancelled' );
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\OrderStatus
	 */
	public static function getCompletedStatus() {
		return self::getStatus( 'completed' );
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\OrderStatus
	 */
	public static function getDeclinedStatus() {
		return self::getStatus( 'declined' );
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\OrderStatus
	 */
	public static function getErrorStatus() {
		return self::getStatus( 'error' );
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\OrderStatus
	 */
	public static function getPendingStatus() {
		return self::getStatus( 'pending' );
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\OrderStatus
	 */
	public static function getOnHoldStatus() {
		return self::getStatus( 'on-hold' );
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\OrderStatus
	 */
	public static function getProcessingStatus() {
		return self::getStatus( 'processing' );
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\OrderStatus
	 */
	public static function getReadyToShipStatus() {
		return self::getStatus( 'ready-to-ship' );
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\OrderStatus
	 */
	public static function getRefundedStatus() {
		return self::getStatus( 'refunded' );
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\OrderStatus
	 */
	public static function getShippedStatus() {
		return self::getStatus( 'shipped' );
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\OrderStatus
	 */
	public static function getVoidedStatus() {
		return self::getStatus( 'voided' );
	}
	
	/**
	 * Get received status object
	 * @return \Maven\Core\Domain\OrderStatus
	 */
	public static function getReceivedStatus(){
		return self::getStatus( 'received' );
	}
	
	/**
	 * Get recovered status object
	 * @return \Maven\Core\Domain\OrderStatus
	 */
	public static function getRecoveredStatus(){
		return self::getStatus( 'recovered' );
	}

	/**
	 * 
	 * @param type $id
	 * @return type
	 */
	public static function getStatus( $id ) {
		$manager = new \Maven\Core\OrderStatusManager();
		return $manager->get( $id );
	}
	
	
	private $statusMapper;
	public function __construct( ) {
		
		$this->statusMapper = new Mappers\OrderStatusMapper();
	}
	
	/**
	 * 
	 * @param int $id
	 * @return \Maven\Core\Domain\OrderStatus | boolean
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function get( $id ){
		
		if ( !$id ) {
			throw new \Maven\Exceptions\MissingParameterException( "Status id is required" );
		}

		$statusMapper = new \Maven\Core\Mappers\OrderStatusMapper();

		return $statusMapper->get( $id );
		
	}
	
	public function getAll(){
		
		$statusMapper = new \Maven\Core\Mappers\OrderStatusMapper();

		return $statusMapper->getAll( );
		
	}
	
	
	public function getOrderHistory( $orderId ){
		
		return $this->statusMapper->getOrderHistory( $orderId );
	}
	
	/**
	 * 
	 * @param \Maven\Core\Domain\OrderStatus $status
	 * @param \Maven\Core\Domain\Order $order
	 */
	public function addStatus( \Maven\Core\Domain\OrderStatus $status, \Maven\Core\Domain\Order $order ){
		
		if ( !$status->getId() ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Status: id is required' );
		}

		if ( !$order->getId() ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Order: id is required' );
		}

		$this->statusMapper->addStatus( $status, $order );
	}
	
	public function removeOrderHistory( $orderId ){
		
		return $this->statusMapper->removeOrderHistory( $orderId );
	}
}

