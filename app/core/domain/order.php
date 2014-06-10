<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Order extends \Maven\Core\DomainObject {

	private $number;
	private $description;
	private $orderDate;
	private $subtotal = 0;
	private $total = 0;
	//private $shippingMethod;
	private $shippingAmount = 0;
	private $discountAmount = 0;
	//Temp value to aid total calculation
	private $taxAmount = 0;
	private $pluginId;
	private $statusId;

	/**
	 * Save who was adding the order
	 * @var \Maven\Core\Domain\User 
	 */
	private $user;

	/**
	 *
	 * @var \Maven\Core\Domain\OrderStatus 
	 */
	private $status = null;
	private $transactionId;

	/**
	 *
	 * @var \Maven\Core\Domain\CreditCard 
	 */
	private $creditCard;

	/**
	 *
	 * @var \Maven\Core\Domain\Contact 
	 */
	private $contact;
	private $contactId;

	/**
	 *
	 * @var \Maven\Core\Domain\Contact 
	 */
	private $shippingContact;
	private $shippingContactId;

	/**
	 *
	 * @var \Maven\Core\Domain\Contact 
	 */
	private $billingContact;
	private $billingContactId;

	/**
	 *
	 * @var \Maven\Core\Domain\Promotion[] 
	 */
	private $promotions;

	/**
	 *
	 * @var \Maven\Core\Domain\Tax[] 
	 */
	private $taxes;

	/**

	  /**
	 *
	 * @var \Maven\Core\Domain\OrderItem[]
	 * @collectionType: \Maven\Core\Domain\OrderItem 
	 */
	private $items = array();

	/**
	 *
	 * @var \Maven\Core\Domain\ExtraField[] 
	 */
	private $extraFields;

	/**
	 *
	 * @var \Maven\Core\Domain\OrderStatus[] 
	 * @collectionType: \Maven\Core\Domain\OrderStatus
	 */
	private $statusHistory = array();

	/**
	 * 
	 * @var \Maven\Core\Domain\ShippingMethod 
	 */
	private $shippingMethod;
	private $shippingCarrier;
	private $shippingTrackingCode;
	private $shippingTrackingUrl;

	public function __construct( $id = false ) {
		parent::__construct( $id );

		$this->billingContact = new Contact();
		$this->shippingContact = new Contact();
		$this->contact = new Contact();
		$this->user = new User();
		$this->creditCard = new CreditCard();
		$this->status = new OrderStatus();

		$rules = array(
		    'contactId' => \Maven\Core\SanitizationRule::Integer,
		    'shippingContactId' => \Maven\Core\SanitizationRule::Integer,
		    'billingContactId' => \Maven\Core\SanitizationRule::Integer,
		    'statusId' => \Maven\Core\SanitizationRule::Integer,
		    'pluginId' => \Maven\Core\SanitizationRule::Key,
		    'discountAmount' => \Maven\Core\SanitizationRule::Float,
		    'total' => \Maven\Core\SanitizationRule::Float,
		    'subTotal' => \Maven\Core\SanitizationRule::Float,
		    'number' => \Maven\Core\SanitizationRule::Text,
		    'description' => \Maven\Core\SanitizationRule::TextWithHtml,
		    'transactionId' => \Maven\Core\SanitizationRule::Text,
		    'shippingContact' => \Maven\Core\SanitizationRule::SerializedObject,
		    'billingContact' => \Maven\Core\SanitizationRule::SerializedObject,
		    'contact' => \Maven\Core\SanitizationRule::SerializedObject,
		    'shippingCarrier' => \Maven\Core\SanitizationRule::Text,
		    'shippingTrackingCode' => \Maven\Core\SanitizationRule::Text,
		    'shippingTrackingUrl' => \Maven\Core\SanitizationRule::Text
		);

		$this->setSanitizationRules( $rules );
	}

	/**
	 * @collectionType: \Maven\Core\Domain\OrderItem
	 * @return \Maven\Core\Domain\OrderItem[]
	 */
	public function getItems() {
		return $this->items;
	}

	public function getTotalQuantityItems() {

		$totalQuantity = 0;
		foreach ( $this->items as $item ) {
			$totalQuantity +=$item->getQuantity();
		}

		return $totalQuantity;
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\OrderItem[] $items
	 */
	public function setItems( $items ) {

		//$this->items = $items;
		//We need to add items one by one, to generate the keys for the array
		foreach ( $items as $item ) {
			$this->addItem( $item );
		}
	}

	/**
	 * Add item to the order
	 * @param \Maven\Core\Domain\OrderItem $item
	 */
	public function addItem( \Maven\Core\Domain\OrderItem $item ) {

		if ( ! $item->getPluginKey() ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Plugin Key is required' );
		}

		//TODO: Check if the item exists, we have to remove it and add the new one.
		if ( $this->itemExists( $item->getIdentifier() ) ) {
			$this->removeItem( $this->order, $item );
		}

		if ( $item->getQuantity() && $item->getQuantity() <= 0 ) {
			throw new \Maven\Exceptions\MavenException( 'Quantity must be at least 1' );
		}

		$this->items[ $item->getIdentifier() ] = $item;

		$this->recalculateSubtotal();
	}

	public function recalculateSubtotal() {
		
		$subTotal = 0;
		foreach ( $this->items as $itemAux ) {
			$subTotal += ( $itemAux->getPrice() * $itemAux->getQuantity() );
		}

		$this->setSubtotal( $subTotal );
	}

	/**
	 * 
	 * @param type $id
	 * @param type $groupKey
	 * @return type
	 */
	public function itemExists( $identifier ) {

		return $this->items && count( $this->items ) > 0 && isset( $this->items[ $identifier ] );
	}

	public function getItem( $identifier ) {

		if ( $this->itemExists( $identifier ) ) {
			return $this->items[ $identifier ];
		}

		return false;
	}

	/**
	 * Remove item from order
	 * @param int $id
	 * @return boolean
	 */
	public function removeItem( $id ) {

		if ( $this->itemExists( $id ) ) {

			unset( $this->items[ $id ] );

			$this->recalculateSubtotal();
			
			return true;
		}

		return false;
	}

	public function getStatusId() {
		return $this->statusId;
	}

	public function setStatusId( $statusId ) {
		$this->statusId = $statusId;
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\OrderStatus
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\OrderStatus $status
	 */
	public function setStatus( \Maven\Core\Domain\OrderStatus $status ) {
		$this->status = $status;
	}

	public function getNumber() {
		return $this->number;
	}

	public function setNumber( $number ) {
		$this->number = $number;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription( $description ) {
		$this->description = $description;
	}

	public function getOrderDate() {
		return $this->orderDate;
	}

	public function setOrderDate( $orderDate ) {
		$this->orderDate = $orderDate;
	}

	public function getSubtotal() {
		return $this->subtotal;
	}

	public function setSubtotal( $subtotal ) {
		$this->subtotal = $subtotal;
	}

	public function getTotal() {
		return $this->total;
	}

	public function setTotal( $total ) {
		$this->total = $total;
	}

	/**
	 * @serialized
	 * @return \Maven\Core\Domain\ShippingMethod 
	 */
	public function getShippingMethod() {
		return $this->shippingMethod;
	}

	public function setShippingMethod( \Maven\Core\Domain\ShippingMethod $shippingMethod ) {
		$this->shippingMethod = $shippingMethod;
	}

	public function getShippingAmount() {
		return $this->shippingAmount;
	}

	public function setShippingAmount( $shippingAmount ) {
		$this->shippingAmount = $shippingAmount;
	}

	public function getDiscountAmount() {
		return $this->discountAmount;
	}

	public function setDiscountAmount( $discountAmount ) {
		$this->discountAmount = $discountAmount;
	}

	public function getPluginId() {
		return $this->pluginId;
	}

	public function setPluginId( $pluginId ) {
		$this->pluginId = $pluginId;
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\OrderItem
	 */
	public function newItem() {
		$item = new \Maven\Core\Domain\OrderItem();

		return $item;
	}

	/**
	 * Check if the order has items.
	 * @return boolean
	 */
	public function hasItems() {
		if ( $this->items && count( $this->items ) > 0 )
			return true;

		return false;
	}

	/**
	 * @serialized
	 * @return \Maven\Core\Domain\CreditCard
	 */
	public function getCreditCard() {
		return $this->creditCard;
	}

	public function setCreditCard( \Maven\Core\Domain\CreditCard $creditCard ) {
		$this->creditCard = $creditCard;
	}

	/*	 * *********************************** 
	 * 			Contact 
	 * *********************************** */

	/**
	 * @serialized
	 * @return \Maven\Core\Domain\Contact
	 */
	public function getShippingContact() {
		return $this->shippingContact;
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Contact $shippingContact
	 */
	public function setShippingContact( \Maven\Core\Domain\Profile $shippingContact ) {
		$this->shippingContact = $shippingContact;
	}

	/**
	 * 
	 * @return int
	 */
	public function getShippingContactId() {
		return $this->shippingContactId;
	}

	/**
	 * 
	 * @param int $shippingContactId
	 */
	public function setShippingContactId( $shippingContactId ) {
		$this->shippingContactId = $shippingContactId;
	}

	/**
	 * @serialized
	 * @return \Maven\Core\Domain\Contact
	 */
	public function getBillingContact() {
		return $this->billingContact;
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Contact $billingContact
	 */
	public function setBillingContact( \Maven\Core\Domain\Profile $billingContact ) {
		$this->billingContact = $billingContact;
	}

	/**
	 * 
	 * @return int
	 */
	public function getBillingContactId() {
		return $this->billingContactId;
	}

	public function setBillingContactId( $billingContactId ) {
		$this->billingContactId = $billingContactId;
	}

	/**
	 * @serialized
	 * @return \Maven\Core\Domain\Contact
	 */
	public function getContact() {
		return $this->contact;
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Contact $contact
	 */
	public function setContact( \Maven\Core\Domain\Profile $contact ) {

		$this->contact = $contact;
	}

	public function getContactId() {
		return $this->contactId;
	}

	public function setContactId( $contactId ) {
		$this->contactId = $contactId;
	}

	/**
	 * @collectionType: \Maven\Core\Domain\Promotion
	 * @serialized
	 * @return \Maven\Core\Domain\Promotion[] 
	 */
	public function getPromotions() {

		if ( $this->promotions )
			return $this->promotions;

		return array();
	}

	/**
	 * Get the taxes
	 * @return \Maven\Core\Domain\Tax[] 
	 */
	public function getTaxes() {

		if ( $this->taxes )
			return $this->taxes;

		return array();
	}

	/**
	 * Add a Promotion
	 * @param \Maven\Core\Domain\Promotion $promotion
	 */
	public function addPromotion( \Maven\Core\Domain\Promotion $promotion ) {

		if ( ! $this->isPromotionAdded( $promotion ) )
			$this->promotions[ $promotion->getCode() ] = $promotion;
	}

	public function isPromotionAdded( $promotion ) {

		$promotionCode = $promotion;

		if ( $promotion instanceof \Maven\Core\Domain\Promotion )
			$promotionCode = $promotion->getCode();


		return isset( $this->promotions[ $promotionCode ] );
	}

	public function hasPromotions() {
		return $this->promotions && count( $this->promotions ) > 0;
	}

	public function hasCreditCard() {
		return $this->creditCard && $this->creditCard->getNumber();
	}

	public function removePromotion( \Maven\Core\Domain\Promotion $promotionToRemove ) {

		foreach ( $this->promotions as $promotion ) {

			if ( $promotion->getCode() === $promotionToRemove->getCode() ) {

				unset( $this->promotions[ $promotion->getCode() ] );
			}
		}
	}

	/**
	 * Add a Tax
	 * @param \Maven\Core\Domain\Tax $tax
	 */
	public function addTax( \Maven\Core\Domain\Tax $tax ) {

		$this->taxes[ $tax->getId() ] = $tax;
	}

	public function hasTaxes() {
		return $this->taxes && count( $this->taxes ) > 0;
	}

	public function removeTax( \Maven\Core\Domain\Tax $taxToRemove ) {

		foreach ( $this->taxes as $tax ) {

			if ( $tax->getId() === $taxToRemove->getId() ) {

				unset( $this->taxes[ $tax->getId() ] );
			}
		}
	}

	/**
	 * Check if the order has contact information
	 * @return boolean
	 */
	public function hasContactInformation() {
		return $this->getContact() && $this->getContact()->getEmail();
	}

	/**
	 * Check if the order has billing information
	 * @return boolean
	 */
	public function hasBillingInformation() {
		return $this->getShippingContact() && $this->getShippingContact()->getEmail();
	}

	/**
	 * Check if the order has shipping information
	 * @return boolean
	 */
	public function hasShippingInformation() {
		return $this->getBillingContact() && $this->getBillingContact()->getEmail();
	}

	/**
	 * Check if the order has user information
	 * @return boolean
	 */
	public function hasUserInformation() {
		return $this->getUser() && $this->getUser()->getEmail();
	}

	public function sanitize() {

		parent::sanitize();

		foreach ( $this->items as $item ) {
			$item->sanitize();
		}

		$this->billingContact->sanitize();
		$this->shippingContact->sanitize();
		$this->contact->sanitize();
		$this->creditCard->sanitize();
	}

	public function calculateTotal() {

		$subtotal = $this->getSubtotal();

		\Maven\Loggers\Logger::log()->message( 'Order/calculateTotal: Items Subtotal Amount: ' . $subtotal );

		$taxAmount = 0;

		if ( $this->taxes ) {
			foreach ( $this->taxes as $tax ) {
				$taxAmount += $tax->getTaxAmount();
			}
		}

		$this->setTaxAmount( $taxAmount );

		\Maven\Loggers\Logger::log()->message( 'Order/calculateTotal: Taxes Amount: ' . $taxAmount );

		$itemDiscount = 0;

		if ( $this->promotions ) {
			foreach ( $this->promotions as $promotion ) {
				if ( $promotion->getSection() === 'item' ) {
					$itemDiscount += $promotion->calculateDiscount( $this );
				}
			}
		}
		if ( $itemDiscount > ($subtotal + $taxAmount) ) {
			$itemDiscount = $subtotal + $taxAmount;
		}

		\Maven\Loggers\Logger::log()->message( 'Order/calculateTotal: Item Discount: ' . $itemDiscount );

		$shippingAmount = $this->getShippingAmount();

		$this->setShippingAmount( $shippingAmount );

		\Maven\Loggers\Logger::log()->message( 'Order/calculateTotal: Shipping Amount: ' . $shippingAmount );

		$shippingDiscount = 0;
		if ( $this->promotions ) {
			foreach ( $this->promotions as $promotion ) {
				if ( $promotion->getSection() === 'shipping' ) {
					$shippingDiscount += $promotion->calculateDiscount( $this );
				}
			}
		}
		if ( $shippingDiscount > $shippingAmount ) {
			$shippingDiscount = $shippingAmount;
		}

		\Maven\Loggers\Logger::log()->message( 'Order/calculateTotal: Shipping Discount: ' . $shippingDiscount );

		$total = $subtotal + $taxAmount + $shippingAmount;

		\Maven\Loggers\Logger::log()->message( 'Order/calculateTotal: SubTotal(items + tax + shhipping): ' . $total );

		$cartDiscount = 0;
		if ( $this->promotions ) {
			foreach ( $this->promotions as $promotion ) {
				if ( $promotion->getSection() === 'cart' ) {
					$cartDiscount += $promotion->calculateDiscount( $this );
				}
			}
		}
		if ( $cartDiscount > $total ) {
			$cartDiscount = $total;
		}
		\Maven\Loggers\Logger::log()->message( 'Order/calculateTotal: Cart Discount: ' . $cartDiscount );

		$total = ($subtotal + $taxAmount - $itemDiscount) + ($shippingAmount - $shippingDiscount);

		if ( $cartDiscount > $total ) {
			$cartDiscount = $total;
		}

		$discountAmount = $itemDiscount + $shippingDiscount + $cartDiscount;

		$this->setDiscountAmount( $discountAmount );

		$total = $total - $cartDiscount;

		if ( $total < 0 ) {
			$total = 0;
		}

		$this->setTotal( $total );

		\Maven\Loggers\Logger::log()->message( 'Order/calculateTotal: Amount: ' . $total );


		return $total;
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\ExtraField
	 */
	public function newExtraField() {
		$extraField = new \Maven\Core\Domain\ExtraField();
		$this->addExtraField( $extraField );
		return $extraField;
	}

	/**
	 * @serialized
	 * @return \Maven\Core\Domain\ExtraField[]
	 */
	public function getExtraFields() {
		return $this->extraFields;
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\ExtraField[] $extraFields
	 */
	public function setExtraFields( $extraFields ) {
		$this->extraFields = $extraFields;
	}

	/**
	 * Add extra Field to the order
	 * @param \Maven\Core\Domain\ExtraField $extraField
	 */
	public function addExtraField( \Maven\Core\Domain\ExtraField $extraField ) {

		if ( ! $extraField->getId() )
			throw new \Maven\Exceptions\MissingParameterException( 'Id is required' );

		if ( ! $extraField->getLabel() )
			throw new \Maven\Exceptions\MissingParameterException( 'Label is required' );


		$this->extraFields[ $extraField->getId() ] = $extraField;
	}

	public function extraFieldExists( $id ) {

		return $this->extraFields && count( $this->extraFields ) > 0 && isset( $this->extraFields[ $id ] );
	}

	public function getExtraField( $id ) {

		if ( $this->extraFieldExists( $id ) )
			return $this->extraFields[ $id ];

		return false;
	}

	public function getExtraFieldValue( $id ) {

		if ( $this->extraFieldExists( $id ) )
			return $this->extraFields[ $id ]->getValue();

		return '';
	}

	public function removeExtraField( $label ) {

		if ( $this->extraFieldExists( $label ) ) {

			unset( $this->extraFields[ $label ] );

			return true;
		}

		return false;
	}

	/**
	 * Get status history
	 * @collectionType: \Maven\Core\Domain\OrderStatus
	 * @return \Maven\Core\Domain\OrderStatus[]
	 */
	public function getStatusHistory() {
		return $this->statusHistory;
	}

	public function setStatusHistory( $statusHistory ) {
		$this->statusHistory = $statusHistory;
	}

	public function setPromotions( $promotions ) {
		//$this->promotions = $promotions;
		
		//I know this is ineficient, but we use promotion code as array key,
		//and that information is not always available in the coming array.
		foreach ( $promotions as $promo ) {
			$this->addPromotion( $promo );
		}
	}

	/**
	 * 
	 * @return string
	 */
	public function getTransactionId() {
		return $this->transactionId;
	}

	/**
	 * Set transaction id 
	 * @param string $transactionId
	 */
	public function setTransactionId( $transactionId ) {
		$this->transactionId = $transactionId;
	}

	/**
	 * Get the user that placed the order
	 * @serialized
	 * @return \Maven\Core\Domain\User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * Save who is placing the order
	 * @param \Maven\Core\Domain\User $user
	 */
	public function setUser( \Maven\Core\Domain\User $user ) {
		$this->user = $user;
	}

	public function getShippingCarrier() {
		return $this->shippingCarrier;
	}

	public function getShippingTrackingCode() {
		return $this->shippingTrackingCode;
	}

	public function getShippingTrackingUrl() {
		return $this->shippingTrackingUrl;
	}

	public function setShippingCarrier( $shippingCarrier ) {
		$this->shippingCarrier = $shippingCarrier;
	}

	public function setShippingTrackingCode( $shippingTrackingCode ) {
		$this->shippingTrackingCode = $shippingTrackingCode;
	}

	public function setShippingTrackingUrl( $shippingTrackingUrl ) {
		$this->shippingTrackingUrl = $shippingTrackingUrl;
	}

	public function getTaxAmount() {
		return $this->taxAmount;
	}

	public function setTaxAmount( $taxAmount ) {
		$this->taxAmount = $taxAmount;
	}

}
