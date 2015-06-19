<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Promotion extends \Maven\Core\DomainObject {

	private $section;
	private $rules = array();
	private $name;
	private $description;
	private $code;
	private $type;
	private $value;
	private $limitOfUse;
	private $uses;
	private $from;
	private $to;
	private $enabled = true;
	private $statusImageUrl;
	private $exclusive = false;
	private $pluginId;
	private $discountAmount;

	public function __construct( $id = false ) {

		parent::__construct( $id );

		$rules = array(
		    'section' => \Maven\Core\SanitizationRule::Text,
		    'rules' => \Maven\Core\SanitizationRule::SerializedObject,
		    'name' => \Maven\Core\SanitizationRule::Text,
		    'description' => \Maven\Core\SanitizationRule::TextWithHtml,
		    'code' => \Maven\Core\SanitizationRule::Text,
		    'type' => \Maven\Core\SanitizationRule::Text,
		    'value' => \Maven\Core\SanitizationRule::Text,
		    'from' => \Maven\Core\SanitizationRule::DateTime,
		    'uses' => \Maven\Core\SanitizationRule::Integer,
		    'to' => \Maven\Core\SanitizationRule::DateTime,
		    'enabled' => \Maven\Core\SanitizationRule::Boolean,
		    'exclusive' => \Maven\Core\SanitizationRule::Boolean,
		    'pluginId' => \Maven\Core\SanitizationRule::Key,
		    'discountAmount' => \Maven\Core\SanitizationRule::Float,
		);


		$this->setSanitizationRules( $rules );
	}

	public function getSection() {
		return $this->section;
	}

	public function setSection( $section ) {
		$this->section = $section;
	}

	/**
	 * @serialized
	*/
	public function getRules() {
		return $this->rules;
	}

	public function setRules( $rules ) {
		$this->rules = $rules;
	}

	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription( $description ) {
		$this->description = $description;
	}

	public function getCode() {
		return $this->code;
	}

	public function setCode( $code ) {
		$this->code = $code;
	}

	public function getType() {
		return $this->type;
	}

	public function setType( $type ) {
		$this->type = $type;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue( $value ) {
		$this->value = $value;
	}

	public function getLimitOfUse() {
		return $this->limitOfUse;
	}

	public function setLimitOfUse( $limitOfUse ) {
		$this->limitOfUse = $limitOfUse;
	}

	public function getFrom() {
		return $this->from;
	}

	public function setFrom( $from ) {
		$this->from = $from;
	}

	public function getTo() {
		return $this->to;
	}

	public function setTo( $to ) {
		$this->to = $to;
	}

	public function isEnabled() {
		return $this->enabled;
	}

	public function setEnabled( $enabled ) {
		if ( $enabled === 'false' || $enabled === false ) {
			$this->enabled = FALSE;
		} else {
			$this->enabled = $enabled;
		}
	}

	public function isExclusive() {
		return $this->exclusive;
	}

	public function setExclusive( $exclusive ) {
		if ( $exclusive === 'false' || $exclusive === false ) {
			$this->exclusive = FALSE;
		} else {
			$this->exclusive = $exclusive;
		}
	}

	public function getPluginId() {
		return $this->pluginId;
	}

	public function setPluginId( $pluginId ) {
		$this->pluginId = $pluginId;
	}

	public function getStatusImageUrl() {
		return $this->statusImageUrl;
	}

	public function setStatusImageUrl( $statusImageUrl ) {
		$this->statusImageUrl = $statusImageUrl;
	}

	public function getDiscountAmount() {
		return $this->discountAmount;
	}

	public function setDiscountAmount( $discountAmount ) {
		$this->discountAmount = $discountAmount;
	}

	public function calculateDiscount( Order $order ) {
		$section = $this->getSection();
		$discount = 0;
		switch ( $section ) {
			case 'cart':
				$discount = $this->calculatePromo( $order->getSubtotal() + $order->getTaxAmount() + $order->getShippingAmount() );
				break;
			case 'item':
				$discount = $this->calculatePromo( $order->getSubtotal() + $order->getTaxAmount() );
				break;
			case 'shipping':
				$discount = $this->calculatePromo( $order->getShippingAmount() );
				break;
			default: //do nothing
				break;
		}
		return $discount;
	}

	private function calculatePromo( $price ) {
		$type = $this->getType();
		$value = $this->getValue();
		$discount = 0;
		switch ( $type ) {
			case 'percentage': //percentage discount
				$discount = (($price * $value) / 100.00);
				break;
			case 'amount': //amount discount
				$discount = $value;
				break;
			default: //If type not recognize, do nothing
				break;
		}

		if ( $discount > $price ) {
			$discount = $price;
		}

		$this->setDiscountAmount( $discount );

		return $discount;
	}
	
	public function isCartPromotion(){
		return $this->getSection()==='cart';
	}
	
	public function isShippingPromotion(){
		return $this->getSection()==='shipping';
	}
	
	public function isItemPromotion(){
		return $this->getSection()==='item';
	}
	
	public function getUses() {
		return $this->uses;
	}

	public function setUses( $uses ) {
		$this->uses = $uses;
	}



}
