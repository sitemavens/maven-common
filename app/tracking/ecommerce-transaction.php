<?php

namespace Maven\Tracking;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class EcommerceTransaction {

	private $orderId;
	private $total;
	private $items = array( );
	private $shipping;
	private $taxes;

	public function getOrderId() {
		return $this->orderId;
	}

	public function setOrderId( $orderId ) {
		$this->orderId = $orderId;
	}

	public function getTotal() {
		return $this->total;
	}

	public function setTotal( $total ) {
		$this->total = $total;
	}

	/**
	 * 
	 * @param \Maven\Tracking\ECommerceItem $item
	 */
	public function addItem( \Maven\Tracking\ECommerceItem $item ) {
		$this->items[ ] = $item;
	}
	
	/**
	 * 
	 * @return \Maven\Tracking\ECommerceItem
	 */
	public function getFirstItem(){
		
		if ( $this->items && count( $this->items ) > 0 ) {
			
			return $this->items[0];
			
		}
		
		return null;
	}
	 
	/**
	 * 
	 * @return \Maven\Tracking\ECommerceItem[]
	 */
	public function getItems(){
		
		return $this->items;
		
	}
	
	public function getShipping () {
		return $this->shipping;
	}

	public function setShipping ( $shipping ) {
		$this->shipping = $shipping;
	}
	
	public function getTaxes () {
		return $this->taxes;
	}

	public function setTaxes ( $taxes ) {
		$this->taxes = $taxes;
	}





//	$item = new GoogleAnalytics\Item();
//	$item->setOrderId ( $donation->transaction_id );
//	$item->setName ( "60th Anniversary Campaign Donation" );
//	$item->setQuantity ( 1 );
//	$item->setPrice ( $donation->amount );
//	$item->setSku ( $donation->id );
//	$transaction = new GoogleAnalytics\Transaction();
//	$transaction->setOrderId( $donation->transaction_id );
//	$transaction->setTotal ( $donation->amount );
//	$transaction->addItem( $item );
}

