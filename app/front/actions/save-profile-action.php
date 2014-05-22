<?php

namespace Maven\Front\Actions;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * 
 */
class SaveProfileAction extends Action {

	public function __construct( \Maven\Front\Step $step, \Maven\Core\Cart $cart, $data ) {
		parent::__construct( $step, $cart, $data );
	}

	/**
	 * 
	 * @return \Maven\Core\Message
	 * @throws \Maven\Exceptions\InvalidObjectTypeException
	 */
	public function execute() {

		$data = $this->getData();
		$manager = new \Maven\Core\ProfileManager();
		try {
			//Get the original profile
			$profile = $manager->get( $data[ 'id' ] );
			//set profile values
			$profile->setFirstName( $data[ 'firstName' ] );
			$profile->setLastName( $data[ 'lastName' ] );
			$profile->setEmail( $data[ 'email' ] );
			$profile->setPhone( $data[ 'phone' ] );

			//set home adress
			$homeAddress = $profile->getHomeAddress();
			//Use the same billing address for home
			$homeAddress->setFirstLine( $data[ 'addresses' ][ 'billing' ][ 'firstLine' ] );
			$homeAddress->setSecondLine( $data[ 'addresses' ][ 'billing' ][ 'secondLine' ] );
			$homeAddress->setCity( $data[ 'addresses' ][ 'billing' ][ 'city' ] );
			$homeAddress->setState( $data[ 'addresses' ][ 'billing' ][ 'state' ] );
			$homeAddress->setCountry( $data[ 'addresses' ][ 'billing' ][ 'country' ] );
			$homeAddress->setZipCode( $data[ 'addresses' ][ 'billing' ][ 'zipCode' ] );

			//set billing adress
			$billingAddress = $profile->getBillingAddress();

			$billingAddress->setFirstLine( $data[ 'addresses' ][ 'billing' ][ 'firstLine' ] );
			$billingAddress->setSecondLine( $data[ 'addresses' ][ 'billing' ][ 'secondLine' ] );
			$billingAddress->setCity( $data[ 'addresses' ][ 'billing' ][ 'city' ] );
			$billingAddress->setState( $data[ 'addresses' ][ 'billing' ][ 'state' ] );
			$billingAddress->setCountry( $data[ 'addresses' ][ 'billing' ][ 'country' ] );
			$billingAddress->setZipCode( $data[ 'addresses' ][ 'billing' ][ 'zipCode' ] );

			//set shipping adress
			$shippingAddress = $profile->getShippingAddress();

			$shippingAddress->setFirstLine( $data[ 'addresses' ][ 'shipping' ][ 'firstLine' ] );
			$shippingAddress->setSecondLine( $data[ 'addresses' ][ 'shipping' ][ 'secondLine' ] );
			$shippingAddress->setCity( $data[ 'addresses' ][ 'shipping' ][ 'city' ] );
			$shippingAddress->setState( $data[ 'addresses' ][ 'shipping' ][ 'state' ] );
			$shippingAddress->setCountry( $data[ 'addresses' ][ 'shipping' ][ 'country' ] );
			$shippingAddress->setZipCode( $data[ 'addresses' ][ 'shipping' ][ 'zipCode' ] );

			//var_dump( $profile );
			//var_dump( $this->data );
			$profile = $manager->addProfile( $profile );
			return \Maven\Core\Message\MessageManager::createSuccessfulMessage( "Your profile was saved" );
		} catch ( \Exception $e ) {
			return \Maven\Core\Message\MessageManager::createErrorMessage( $e->getMessage() );
		}
	}

}
