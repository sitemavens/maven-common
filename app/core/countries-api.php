<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class CountriesApi {

	public function __construct() {
		
	}

	/**
	 * Get countries
	 * @return type
	 */
	public static function getAllCountries( $wordlwide = true ) {

		$manager = new CountryManager( );

		return $manager->getAll( $wordlwide );
	}

	/**
	 * Get the country
	 * @param int/object $countryCode
	 */
	public static function getCountry( $countryCode ) {

		if ( !$countryCode ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Country code is required.' );
		}

		$manager = new CountryManager();

		$country = $manager->get( $countryCode );

		return $country;
	}
	
	public static function getStates( $countryCode ){
		$manager = new CountryManager();

		$states = $manager->getStates( $countryCode );

		return $states;
	}
}