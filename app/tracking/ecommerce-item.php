<?php

namespace Maven\Tracking;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class EcommerceItem {

	private $orderId;
	private $name;
	private $quantity;
	private $price;
	private $sku;
	
	public function getOrderId() {
		return $this->orderId;
	}

	public function setOrderId( $orderId ) {
		$this->orderId = $orderId;
	}

	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}

	public function getQuantity() {
		return $this->quantity;
	}

	public function setQuantity( $quantity ) {
		$this->quantity = $quantity;
	}

	public function getPrice() {
		return $this->price;
	}

	public function setPrice( $price ) {
		$this->price = $price;
	}

	public function getSku() {
		return $this->sku;
	}

	public function setSku( $sku ) {
		$this->sku = $sku;
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



