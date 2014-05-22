<?php

namespace Maven\Gateways;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 *
 * @author mustela
 */
class GatewayOrderItem {

	private $itemId;
	private $name = "";
	private $desription ="";
	private $quantity = 0;
	private $unitPrice = 0;
	private $taxable = false;
	
	public function getItemId() {
		return $this->itemId;
	}

	public function setItemId( $itemId ) {
		$this->itemId = $itemId;
	}

	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}

	public function getDesription() {
		return $this->desription;
	}

	public function setDesription( $desription ) {
		$this->desription = $desription;
	}

	public function getQuantity() {
		return $this->quantity;
	}

	public function setQuantity( $quantity ) {
		$this->quantity = $quantity;
	}

	public function getUnitPrice() {
		return $this->unitPrice;
	}

	public function setUnitPrice( $unitPrice ) {
		$this->unitPrice = $unitPrice;
	}

	public function getTaxable() {
		return $this->taxable;
	}

	public function setTaxable( $taxable ) {
		$this->taxable = $taxable;
	}

		
	public function __construct() {
		;
	}
}