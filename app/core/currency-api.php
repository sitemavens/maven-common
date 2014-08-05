<?php
namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class CurrencyApi {
	
	/**
	 * Return an array with all currency displays availables
	 * @return array	Currency formats or empty if the format provided doesn't exist
	 */
	public static function getCurrencyFormats( $format = '' ) {
		return CurrencyManager::pricingDisplaySetting( $format );
	}
	
	/**
	 * Format number with pricing settings
	 * @param mixed	$value number to format
	 * @param array	$options 
							decimals (number of decimals values)
							decimal_separator
							thousand_separator
	 * @return string 
	 */
	public static function numberFormat($value, $options = array()) {
		return CurrencyManager::numberFormat( $value, $options );
	}

	/**
	 * Format values to show as currency value
	 * @param mixed		$value	Number or String
	 * @param array		$args Array of arguments
	 * @return string	Value formated 
	 */
	public static function formatNumberToCurrency( $value, $args = array() ) {
		return CurrencyManager::formatNumberToCurrency( $value, $args );
	}
}