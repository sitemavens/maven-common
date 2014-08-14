<?php
namespace Maven\Core;
use \Maven\Settings\MavenRegistry;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class CurrencyManager {
	/**
	 * Return an array with all currency displays availables
	 * @return array	Currency formats
	 */
	public static function getPricingDisplaySetting( $option = '' ) {
		$options = array('symbol_number' => array('format' => '%symbol%%number%', 'example' => '$99.99'),
						'symbol_space_number' => array('format' => '%symbol% %number%', 'example' => '$ 99.99'),
						'number_symbol' => array('format' => '%number%%symbol%', 'example' => '99.99$'),
						'number_space_symbol' => array('format' => '%number% %symbol%', 'example' => '99.99 $'),
						'code_number' => array('format' => '%code%%number%', 'example' => 'USD99.99'),
						'code_space_number' => array('format' => '%code% %number%', 'example' => 'USD 99.99'),
						'number_code' => array('format' => '%number%%code%', 'example' => '99.99USD'),
						'number_space_code' => array('format' => '%number% %code%', 'example' => '99.99 USD')
						);
		if( !empty($option) && isset($options[$option]) ){
			return $options[$option];
		}elseif( !empty($option) ){
			return array();
		}

		return $options;
	}

	/**
	 *	Get princing options array or specific pricing option
	 * @param string	$option Possible values 'decimals' | 'thousand_separator' | 'decimal_separator'
	 * @return mixed	Array pricing options, String specific pricing option or null
	 */
	public static function getPricingSettings( $option = ARRAY_A ) {
		$mavenRegistry = MavenRegistry::instance();
		$pricing['decimalDigits'] = (int) $mavenRegistry->getCurrencyDecimalDigits();
		$pricing['decimalSeparator'] = ( $mavenRegistry->getCurrencyDecimalSeparator() ? $mavenRegistry->getCurrencyDecimalSeparator() : '.' );
		$pricing['thousandSeparator'] = ( $mavenRegistry->getCurrencyThousandSeparator() ? $mavenRegistry->getCurrencyThousandSeparator() : '' );

		if( $option != ARRAY_A && isset($pricing[$option]) ){
			return $pricing[$option];
		}else{
			return $pricing;
		}
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
	public static function formatNumber($value, $options = array()) {
		if( empty($value) && $value != 0 ){
			return '';
		}

		$pricingSettings = self::getPricingSettings();

		$pricingSettings = wp_parse_args($options, $pricingSettings);

		$precision = ($pricingSettings['decimalDigits']) ? $pricingSettings['decimalDigits'] : 0;

		// Do this to fix wrong rounded values on number_format

		//$factor = pow(10, -1 * $precision);
		$factor = pow(10, $precision);


		//$value = (ceil($value / $factor) * $factor);

		$ceil = ceil($value * $factor) / $factor;
		$floor = floor($value * $factor) / $factor;

		$factor = pow(10, $precision + 1);

		$diffCeil     = $factor * ($ceil - $value);
		$diffFloor     = $factor * ($value - $floor) + ($value < 0 ? -1 : 1);

		if($diffCeil >= $diffFloor) {
			$value = $floor;
		} else {
			$value = $ceil;
		}

		// Format the number with settings selections
		$numberFormated = number_format(
											$value,
											$precision,
											$pricingSettings['decimalSeparator'],
											$pricingSettings['thousandSeparator']
										);
		return $numberFormated;
	}

	/**
	 *	Get currency attributes from countries array passing country code saved on pricing settings
	 * @param string $return	Possibles values 'code' | 'symbol'
	 * @return mixed	Array with currrency values, String with specific currency value or null
	 */
	public static function getCurrencyData($return = ARRAY_A) {
		$country = CountryManager::instance()->get( MavenRegistry::instance()->getCurrencyCountry() );
		$currency = ( isset( $country['currency'] ) ) ? $country['currency'] : null;
		if( !$currency ){
			return null;
		}
		
		if($return == ARRAY_A){
			return $currency;
		}elseif(isset($currency[$return])){
			return $currency[$return];
		}else{
			return null;
		}
	}

	/**
	 * Get Currency display options or specific option
	 * @param string	$key	Possible values ARRAY_A | 'format' | 'example'
	 * @return mixed	Array with the array of options, String for specific option or null
	 */
	public static function getPricingDisplay( $key = ARRAY_A, $currencyDisplay = '' ) {
		$currencyDisplay = ( !empty($currencyDisplay ) ? $currencyDisplay : MavenRegistry::instance()->getCurrencyDisplayFormat() );
		$displayOptions = self::getPricingDisplaySetting();

		if( $displayOptions ) {
			if($key == ARRAY_A){
				return $displayOptions[$currencyDisplay];
			}elseif( isset($displayOptions[$currencyDisplay][$key]) ){
				return $displayOptions[$currencyDisplay][$key];
			}else{
				return null;
			}
		}
	}

	/**
	 * Format values to show as currency value
	 * @param mixed		$value	Number or String
	 * @param array		$args Array of arguments
	 * @return string	Value formated 
	 */
	public static function formatNumberToCurrency( $value, $args = array() ) {
		$defaults = array(
							'showZeroValues' => true,
							'decimalDigits' => '',
							'decimalSeparator' => '',
							'thousandSeparator' => '',
							'currencyDisplay' => '',
							'currencySymbol' => '',
							'currencyCode' => '',
							);
		$args = wp_parse_args($args, $defaults);
		extract($args);

		if( empty($value) && ( ! $showZeroValues && $value == '0') ){
			return '';
		}

		$options = array();
		if( !empty($decimalDigits) ){
			$options['decimalDigits'] = $decimalDigits;
		}

		if( !empty($decimalSeparator) ){
			$options['decimalSeparator'] = $decimalSeparator;
		}

		if( !empty($thousandSeparator) ){
			$options['thousandSeparator'] = $thousandSeparator;
		}

		$value = self::formatNumber($value, $options);
		$output = $value;
		$currencyDisplay = ($currencyDisplay) ? self::getPricingDisplaySetting('format', $currencyDisplay) : self::getPricingDisplay('format');

		if( $currencyDisplay && is_string($currencyDisplay) ) {
			$currencySymbol = ($currencySymbol) ? $currencySymbol : self::getCurrencyData('symbol');
			$currencyCode =  ($currencyCode) ? $currencyCode: self::getCurrencyData('code');
			$output = str_replace( array('%code%', '%symbol%', '%number%'), array($currencyCode, $currencySymbol, $value), $currencyDisplay );
		}

		return $output;
	}
}