<?php

namespace Maven\Core\Mappers;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class OrderStatusMapper extends \Maven\Core\Db\WordpressMapper {

	private $status = null;
	
	private $orderStatusTable = "mvn_orders_status";

	public function __construct() {

		parent::__construct( $this->orderStatusTable );
		
		$items = array( 'received'=>'Received', 'cancelled' => 'Cancelled', 'completed' => 'Completed', 'declined' => 'Declined', 'error' => 'Error', 'on-hold' => 'On hold', 'pending' => 'Pending', 'processing' => 'Processing', 'ready-to-ship' => 'Ready To Ship', 'refunded' => 'Refunded', 'shipped' => 'Shipped', 'voided' => 'Voided', 'recovered' => 'Recovered' );

		$registry = \Maven\Settings\MavenRegistry::instance();
		
		foreach ( $items as $key => $value ) {

			$instance = new \Maven\Core\Domain\OrderStatus();
			$instance->setid( $key );
			$instance->setName( $value );
			$instance->setImageUrl( $registry->getOrderStatusImagesUrl().$key.".png");

			$this->status[ $key ] = $instance;
		}
	}

	public function getAll() {

		return $this->status;
	}

	
	public function getOrderHistory( $orderId ){
		
		$statusRows = $this->getResultsBy( 'order_id', $orderId, 'timestamp','desc');
		
		$status = array();
		
		foreach($statusRows as $statusRow ){
			
			$instance = $this->get( $statusRow->status_id );
			$this->fillObject($instance, $statusRow);
			$status[] = $instance;
			
		}
		
		return $status;
		
	}
	
	
	/**
	 * 
	 * @param int $id
	 * @return \Maven\Core\Domain\OrderStatus | Boolean
	 */
	public function get( $id ) {

		if ( isset( $this->status[ $id ] ) )
			return clone $this->status[ $id ];

		return false;
	}
	
	
	/**
	 * 
	 * @param \Maven\Core\Domain\OrderStatus $status
	 * @param \Maven\Core\Domain\Order $order
	 * @return type
	 */
	public function addStatus( \Maven\Core\Domain\OrderStatus $status, \Maven\Core\Domain\Order $order ){
		
		$status->sanitize();
		$order->sanitize();
		
		$data = array(
			
			'order_id' => $order->getId(),
			'status_id' => $status->getId(),
			'status_description' => $status->getStatusDescription()	
			
		);
		
		$format = array(
			'%d',
			'%s',
			'%s'
		);
		
		return $this->insert($data, $format, $this->orderStatusTable);
	}
	
	public function removeOrderHistory( $donationId ){
		
		$query = "DELETE FROM {$this->orderStatusTable} WHERE order_id = %d";
		$query = $this->prepare($query, $donationId );
		
		return $this->executeQuery($query);
	}

}