<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class AddressType{
	
	const Billing	= "billing";
	const Shipping	= "shipping";
	const Work		= "work";
	const Home		= "home";
	const Friend	= "friend";
	const Family	= "family";
	const Other		= "other";
	
	public static function getAddressesTypes(){
		
		return array( 
						self::Billing	=> 'Billing',
						self::Shipping	=> 'Shipping',
						self::Work		=> 'Work',
						self::Home		=> 'Home',
						self::Friend	=> 'Friend',
						self::Family	=> 'Family',
						self::Other		=> 'Other',
				);
	}
	
	public static function getAddressesTypesCollection(){
		$addresses = array(
				    array(
					'id' => self::Billing,
					'name' => 'Billing'
				    ),
				    array(
					'id' => self::Shipping,
					'name' => 'Shipping'
				    ),
				    array(
					'id' => self::Work,
					'name' => 'Work'
				    ),
				    array(
					'id' => self::Home,
					'name' => 'Home'
				    ),
				    array(
					'id' => self::Friend,
					'name' => 'Friend'
				    ),
				    array(
					'id' => self::Family,
					'name' => 'Family'
				    ),
				    array(
					'id' => self::Other,
					'name' => 'Other'
				    )
		);
		return $addresses;
	}
}