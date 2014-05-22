<?php

namespace Maven\Front\Actions;

use \Maven\Front\DataToCollect;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * 
 */
class UpdateCartAction extends Action {

	public function __construct( \Maven\Front\Step $step, \Maven\Core\Cart $cart, $data = array() ) {
		parent::__construct( $step, $cart, $data );

		$this->initializeData();
	}

	private function initializeData() {

		// Credit card information
		$ccDefault = array(
		);

		$data = $this->getData();
		$data = wp_parse_args( $data, array(
		    Consts::CreditCard => array(),
		    Consts::Contact => array(),
		    Consts::BillingContact => array(),
		    Consts::ShippingContact => array() )
			);

		$data[ Consts::CreditCard ] = wp_parse_args( $data[ Consts::CreditCard ], array(
		    Consts::SecurityCode => "",
		    Consts::Number => "",
		    Consts::NameOnCard => "",
		    Consts::Month => "",
		    Consts::Year => "",
		    Consts::CreditCardType => ""
			) );


		$data[ Consts::Contact ] = wp_parse_args( $data[ Consts::Contact ], array(
		    Consts::FirstName => "",
		    Consts::LastName => "",
		    Consts::AddressFirstLine => "",
		    Consts::AddressSecondLine => "",
		    Consts::AddressCity => "",
		    Consts::AddressCountry => "",
		    Consts::Phone => "",
		    Consts::AddressState => "",
		    Consts::AddressZipCode => "",
		    Consts::Email => ""
			) );

		$data[ Consts::BillingContact ] = wp_parse_args( $data[ Consts::BillingContact ], array(
		    Consts::FirstName => "",
		    Consts::LastName => "",
		    Consts::AddressFirstLine => "",
		    Consts::AddressSecondLine => "",
		    Consts::AddressCity => "",
		    Consts::AddressCountry => "",
		    Consts::Phone => "",
		    Consts::AddressState => "",
		    Consts::AddressZipCode => "",
		    Consts::Email => ""
			) );

		$data[ Consts::ShippingContact ] = wp_parse_args( $data[ Consts::ShippingContact ], array(
		    Consts::FirstName => "",
		    Consts::LastName => "",
		    Consts::AddressFirstLine => "",
		    Consts::AddressSecondLine => "",
		    Consts::AddressCity => "",
		    Consts::AddressCountry => "",
		    Consts::Phone => "",
		    Consts::AddressState => "",
		    Consts::AddressZipCode => "",
		    Consts::Email => "",
		    Consts::SameAsBilling => false
			) );

		$data[ Consts::ShippingMethod ] = "";

		if ( ! \array_key_exists( Consts::Notes, $data ) ) {
			$data[ Consts::Notes ] = " ";
		}

		$this->setData( $data );
	}

	/**
	 * Get credit card data
	 * @return array
	 */
	private function getCreditCardData() {
		$data = $this->getData();

		return $data[ Consts::CreditCard ];
	}

	private function getContactData() {
		$data = $this->getData();

		return $data[ Consts::Contact ];
	}

	private function getShippingMethodData() {
		$data = $this->getData();

		return $data[ Consts::ShippingMethod ];
	}

	private function getBillingContactData() {
		$data = $this->getData();

		return $data[ Consts::BillingContact ];
	}

	private function getShippingContactData() {
		$data = $this->getData();

		return $data[ Consts::ShippingContact ];
	}

	

	/**
	 * 
	 * @return \Maven\Core\Message
	 * @throws \Maven\Exceptions\InvalidObjectTypeException
	 */
	public function execute() {

		$result = \Maven\Core\Message\MessageManager::createErrorMessage( "No actions returned" );

		//$item = apply_filters( "maven/cart/update", $this->step->getThing() );

		$cart = \Maven\Core\Cart::current();

		$order = $cart->getOrder();

		if ( ! $order ) {
			return \Maven\Core\Message\MessageManager::createErrorMessage( "No order placed" );
		}

		if ( $this->getStep()->getNotes() ){
			$order->setDescription( $this->getStep()->getNotes());
		}
		
		if ( $this->getStep()->hasExtraFields() ) {
			$extraFields = $this->getCart()->getExtraFields();

			$fields = array(
			);

			foreach ( $fields as $field => $label ) {

				$value = isset( $extraFields[ $field ] ) ? $extraFields[ $field ] : false;

				if ( ! $value ) {
					continue;
				}

				$ef = new Maven\Core\Domain\ExtraField();
				$ef->setId( $field );
				$ef->setValue( $value );
				$ef->setLabel( $label );

				$order->addExtraField( $ef );
			}


			$cart->update( $order );
		}

		if ( $this->getStep()->hasItems() ) {

			$items = $this->getStep()->getItems();

			$result = $cart->update( $order );

			if ( $items && count( $items ) > 0 ) {
				foreach ( $items as $item ) {

					// Do we have to remove the item? 
					if ( isset( $item[ 'remove' ] ) ) {
						$cart->removeItem( $item[ Consts::Id ] );
					} else {

						// Check if the item has change its quantity
						$orderItem = $order->getItem( $item[ Consts::Id ] );

						if ( $orderItem ) {

							if ( $orderItem->getQuantity() != $item[ Consts::Quantity ] || $orderItem->getPrice() != $item[ Consts::Price ] ) {

								$newItem = clone($orderItem);

								$newItem->setQuantity( $item[ Consts::Quantity ] );
								$newItem->setPrice( $item[ Consts::Price ] );

								// If the item has a different quantity lets remove it and add it again. 
								$cart->removeItem( $item[ Consts::Id ] );

								// Add the new one 
								$cart->addToCart( $newItem );
							}
						}
					}
				}
			}
		}


		/*** *************************************
		 *  Check if there any promotion to apply
		 * *************************************** */
		if ( $this->getStep()->getPromotionCode() ) {

			$result = $cart->applyPromotion( $this->getStep()->getPromotionCode() );

			if ( ! $result ) {
				return $cart->getResult();
			}
		}

		/*		 * ***************************************
		 *  Check if the country was selected. We need it to calculate taxes
		 * *************************************** */
		if ( $this->getStep()->getShippingCountry() ) {

			// Set the shipping country to calculate taxes
			$order->getShippingContact->getPrimaryAddress()->setCountry( $this->getStep()->getShippingCountry() );

			$result = $cart->update( $order );
		}

		// Check if there any shipping method available
		if ( $this->getShippingMethodData() ) {

			$shippingMethodId = $this->getShippingMethodData();

			$shippingManager = new \Maven\Core\ShippingMethodManager();
			$shippingMethod = $shippingManager->get( $shippingMethodId );

			if ( $shippingMethod ) {
				$cart->getOrder()->setShippingMethod( $shippingMethod );
			}
		}

		
		

		/**		 * **************************************
		 *  Check if there any promotion to remove
		 * *************************************** */
//		if ( $this->getStep()->hasPromotions() ) {
//			$promotions = $this->getStep()->getPromotions();
//
//			foreach ( $promotions as $key => $value ) {
//				if ( is_array( $value ) && count( $value ) > 0 && $value[ 'remove' ] ) {
//					$cart->removePromotion( $key );
//				}
//			}
//		}


		if ( $this->getStep()->hasToCollect() ) {
			$this->collectData( $order );

			$result = $cart->update( $order );
		}

		if ( $this->getStep()->hasToCollect( DataToCollect::All ) || $this->getStep()->hasToCollect( DataToCollect::CreditCardInfo ) ) {

			// Get the credit card information
			$creditCard = $order->getCreditCard();
			$creditCardData = $this->getCreditCardData();

			$creditCard->setHolderName( $creditCardData[ Consts::NameOnCard ] );
			$creditCard->setNumber( $creditCardData[ Consts::Number ] );
			$creditCard->setMonth( $creditCardData[ Consts::Month ] );
			$creditCard->setYear( $creditCardData[ Consts::Year ] );
			$creditCard->setSecurityCode( $creditCardData[ Consts::SecurityCode ] );
			$creditCard->setType( $creditCardData[ Consts::CreditCardType ] );

			$result = $cart->update( $order );
		}

		return \Maven\Core\Message\MessageManager::createSuccessfulMessage( 'Order updated' );
	}

	function collectData( \Maven\Core\Domain\Order $order ) {
		
		\Maven\Loggers\Logger::log()->message( 'UpdateCartAction/collectData' );
		
		if ( $this->getStep()->hasToCollect( DataToCollect::All ) || $this->getStep()->hasToCollect( DataToCollect::ContactInfo ) ) {

			// Get the contact information
			$contactData = $this->getContactData();

			$contact = $order->getContact();
			$contact->setFirstName( $contactData[ Consts::FirstName ] );
			$contact->setLastName( $contactData[ Consts::LastName ] );
			$contact->setEmail( $contactData[ Consts::Email ] );
			$contact->setPhone( $contactData[ Consts::Phone ] );

			//		switch( $contactData['sex'] ){
			//			case "Female":
			//				$contact->setSex( \Maven\Core\Domain\ProfileSex::Female );
			//				break;
			//			case "Male":
			//				$contact->setSex( \Maven\Core\Domain\ProfileSex::Male );
			//				break;
			//			case "Other":
			//				$contact->setSex( \Maven\Core\Domain\ProfileSex::Other );
			//				break;
			//		}

			$primaryAddress = $contact->getPrimaryAddress();
			$primaryAddress->setFirstLine( $contactData[ Consts::AddressFirstLine ] );
			$primaryAddress->setSecondLine( $contactData[ Consts::AddressSecondLine ] );
			$primaryAddress->setState( $contactData[ Consts::AddressState ] );
			$primaryAddress->setZipcode( $contactData[ Consts::AddressZipCode ] );
			$primaryAddress->setCity( $contactData[ Consts::AddressCity ] );
			$primaryAddress->setCountry( $contactData[ Consts::AddressCountry ] );
			$primaryAddress->setPhone( $contactData[ Consts::Phone ] );
			
			\Maven\Loggers\Logger::log()->message( 'UpdateCartAction/collectData: Contact updated' );
		}

		if ( $this->getStep()->hasToCollect( DataToCollect::All ) || $this->getStep()->hasToCollect( DataToCollect::BillingInfo ) ) {

			// Get the contact information
			$contactData = $this->getBillingContactData();

			$billingContact = $order->getBillingContact();
			$billingContact->setFirstName( $contactData[ Consts::FirstName ] );
			$billingContact->setLastName( $contactData[ Consts::LastName ] );
			$billingContact->setEmail( $contactData[ Consts::Email ] );


			//		switch( $contactData['sex'] ){
			//			case "Female":
			//				$contact->setSex( \Maven\Core\Domain\ProfileSex::Female );
			//				break;
			//			case "Male":
			//				$contact->setSex( \Maven\Core\Domain\ProfileSex::Male );
			//				break;
			//			case "Other":
			//				$contact->setSex( \Maven\Core\Domain\ProfileSex::Other );
			//				break;
			//		}

			$billingAddress = $billingContact->getBillingAddress();
			$billingAddress->setFirstLine( $contactData[ Consts::AddressFirstLine ] );
			$billingAddress->setSecondLine( $contactData[ Consts::AddressSecondLine ] );
			$billingAddress->setState( $contactData[ Consts::AddressState ] );
			$billingAddress->setZipcode( $contactData[ Consts::AddressZipCode ] );
			$billingAddress->setCity( $contactData[ Consts::AddressCity ] );
			$billingAddress->setCountry( $contactData[ Consts::AddressCountry ] );
			$billingAddress->setPhone( $contactData[ Consts::Phone ] );
			
			\Maven\Loggers\Logger::log()->message( 'UpdateCartAction/collectData: Billing updated' );
		}

		if ( $this->getStep()->hasToCollect( DataToCollect::All ) || $this->getStep()->hasToCollect( DataToCollect::ShippingInfo ) ) {

			// Get the contact information
			$contactData = $this->getShippingContactData();

			if ( $contactData[ Consts::SameAsBilling ] ) {
				$contactData = $this->getBillingContactData();
			}
			$shippingContact = $order->getShippingContact();
			$shippingContact->setFirstName( $contactData[ Consts::FirstName ] );
			$shippingContact->setLastName( $contactData[ Consts::LastName ] );
			$shippingContact->setEmail( $contactData[ Consts::Email ] );

			$shippingAddress = $shippingContact->getShippingAddress();
			$shippingAddress->setFirstLine( $contactData[ Consts::AddressFirstLine ] );
			$shippingAddress->setSecondLine( $contactData[ Consts::AddressSecondLine ] );
			$shippingAddress->setState( $contactData[ Consts::AddressState ] );
			$shippingAddress->setZipcode( $contactData[ Consts::AddressZipCode ] );
			$shippingAddress->setCity( $contactData[ Consts::AddressCity ] );
			$shippingAddress->setCountry( $contactData[ Consts::AddressCountry ] );
			$shippingAddress->setPhone( $contactData[ Consts::Phone ] );
			
			\Maven\Loggers\Logger::log()->message( 'UpdateCartAction/collectData: Shipping updated' );
		}

		// If we have to collect all and the contact info isn't there, we clone the billing info
		if ( $this->getStep()->hasToCollect( DataToCollect::All ) && ! $order->getContact()->getEmail() ) {

			// Get the contact information
			$contactData = $this->getBillingContactData();

			$contact = $order->getContact();
			$contact->setFirstName( $contactData[ Consts::FirstName ] );
			$contact->setLastName( $contactData[ Consts::LastName ] );
			$contact->setEmail( $contactData[ Consts::Email ] );

			$primaryAddress = $contact->getPrimaryAddress();
			$primaryAddress->setFirstLine( $contactData[ Consts::AddressFirstLine ] );
			$primaryAddress->setSecondLine( $contactData[ Consts::AddressSecondLine ] );
			$primaryAddress->setState( $contactData[ Consts::AddressState ] );
			$primaryAddress->setZipcode( $contactData[ Consts::AddressZipCode ] );
			$primaryAddress->setCity( $contactData[ Consts::AddressCity ] );
			$primaryAddress->setCountry( $contactData[ Consts::AddressCountry ] );
			$primaryAddress->setPhone( $contactData[ Consts::Phone ] );
		}
	}

}
