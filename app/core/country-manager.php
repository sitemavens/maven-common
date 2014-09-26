<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class CountryManager {

	private static $instance;
	
	private $countries = array();
	
	/**
	 * 
	 * @return CountryManager 
	 */
	static function instance () {
		if ( !isset( self::$instance ) ) {
			self::$instance = new self( );
		}

		return self::$instance;
	}

	public function __construct () {
		$countries[ '*' ] = array( 'name' => __( 'Worldwide', 'maven' ),'code'=>'CA', 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );

		$countries[ 'CA' ] = array( 'name' => __( 'Canada', 'maven' ),'code'=>'CA',  'currency' => array( 'code' => 'CAD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'US' ] = array( 'name' => __( 'United States', 'maven' ), 'code'=>'US', 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'imperial' );

		// Specialized countries for US Armed Forces and US Territories
		//$countries[ 'USAF' ] = array( 'name' => __( 'US Armed Forces', 'maven' ),'code'=>'CA', 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'imperial' );
		//$countries[ 'USAT' ] = array( 'name' => __( 'US Territories', 'maven' ),'code'=>'CA', 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'imperial' );

		$countries[ 'GB' ] = array( 'name' => __( 'United Kingdom', 'maven' ), 'code'=>'GB', 'currency' => array( 'code' => 'GBP', 'symbol' => '£' ), 'units' => 'metric' );
		$countries[ 'AF' ] = array( 'name' => __( 'Afghanistan', 'maven' ), 'code'=>'AF', 'currency' => array( 'code' => 'AFN', 'symbol' => 'AFN' ), 'units' => 'metric' );
		$countries[ 'AX' ] = array( 'name' => __( 'Åland Islands', 'maven' ),'code'=>'AX', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'AL' ] = array( 'name' => __( 'Albania', 'maven' ),'code'=>'AL', 'currency' => array( 'code' => 'ALL', 'symbol' => 'Lek' ), 'units' => 'metric' );
		$countries[ 'DZ' ] = array( 'name' => __( 'Algeria', 'maven' ),'code'=>'DZ', 'currency' => array( 'code' => 'DZD', 'symbol' => 'د.ج' ), 'units' => 'metric' );
		$countries[ 'AS' ] = array( 'name' => __( 'American Samoa', 'maven' ),'code'=>'AS', 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'AD' ] = array( 'name' => __( 'Andorra', 'maven' ),'code'=>'AD', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'AO' ] = array( 'name' => __( 'Angola', 'maven' ),'code'=>'AO', 'currency' => array( 'code' => 'AOA', 'symbol' => 'Kz' ), 'units' => 'metric' );
		$countries[ 'AI' ] = array( 'name' => __( 'Anguilla', 'maven' ),'code'=>'AI', 'currency' => array( 'code' => 'XCD', 'symbol' => 'EC$' ), 'units' => 'metric' );
		$countries[ 'AG' ] = array( 'name' => __( 'Antigua and Barbuda', 'maven' ),'code'=>'AG', 'currency' => array( 'code' => 'XCD', 'symbol' => 'EC$' ), 'units' => 'metric' );
		$countries[ 'AR' ] = array( 'name' => __( 'Argentina', 'maven' ),'code'=>'AR', 'currency' => array( 'code' => 'ARS', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'AM' ] = array( 'name' => __( 'Armenia', 'maven' ),'code'=>'AM', 'currency' => array( 'code' => 'AMD', 'symbol' => '####,## Դրամ' ), 'units' => 'metric' );
		$countries[ 'AW' ] = array( 'name' => __( 'Aruba', 'maven' ),'code'=>'AW', 'currency' => array( 'code' => 'AWG', 'symbol' => 'ƒ' ), 'units' => 'metric' );
		$countries[ 'AU' ] = array( 'name' => __( 'Australia', 'maven' ),'code'=>'AU', 'currency' => array( 'code' => 'AUD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'AT' ] = array( 'name' => __( 'Austria', 'maven' ),'code'=>'AT', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'AZ' ] = array( 'name' => __( 'Azerbaijan', 'maven' ),'code'=>'AZ', 'currency' => array( 'code' => 'AZN', 'symbol' => 'man.' ), 'units' => 'metric' );
		$countries[ 'BD' ] = array( 'name' => __( 'Bangladesh', 'maven' ),'code'=>'BD', 'currency' => array( 'code' => 'BDT', 'symbol' => '&#2547;' ), 'units' => 'metric' );
		$countries[ 'BB' ] = array( 'name' => __( 'Barbados', 'maven' ),'code'=>'BB', 'currency' => array( 'code' => 'BBD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'BS' ] = array( 'name' => __( 'Bahamas', 'maven' ),'code'=>'BS', 'currency' => array( 'code' => 'BSD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'BH' ] = array( 'name' => __( 'Bahrain', 'maven' ),'code'=>'BH', 'currency' => array( 'code' => 'BHD', 'symbol' => 'ب.د ' ), 'units' => 'metric' );
		$countries[ 'BY' ] = array( 'name' => __( 'Belarus', 'maven' ),'code'=>'BY', 'currency' => array( 'code' => 'BYR', 'symbol' => 'BYR' ), 'units' => 'metric' );
		$countries[ 'BE' ] = array( 'name' => __( 'Belgium', 'maven' ),'code'=>'BE', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'BZ' ] = array( 'name' => __( 'Belize', 'maven' ),'code'=>'BZ', 'currency' => array( 'code' => 'BZD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'BJ' ] = array( 'name' => __( 'Benin', 'maven' ),'code'=>'BJ', 'currency' => array( 'code' => 'XOF', 'symbol' => 'CFA' ), 'units' => 'metric' );
		$countries[ 'BM' ] = array( 'name' => __( 'Bermuda', 'maven' ),'code'=>'BM', 'currency' => array( 'code' => 'BMD', 'symbol' => 'BD$' ), 'units' => 'metric' );
		$countries[ 'BT' ] = array( 'name' => __( 'Bhutan', 'maven' ),'code'=>'BT', 'currency' => array( 'code' => 'BTN', 'symbol' => 'Nu.' ), 'units' => 'metric' );
		$countries[ 'BO' ] = array( 'name' => __( 'Bolivia', 'maven' ),'code'=>'BO', 'currency' => array( 'code' => 'BOB', 'symbol' => 'Bs' ), 'units' => 'metric' );
		$countries[ 'BA' ] = array( 'name' => __( 'Bosnia and Herzegovina', 'maven' ),'code'=>'BA', 'currency' => array( 'code' => 'BAM', 'symbol' => 'KM ' ), 'units' => 'metric' );
		$countries[ 'BW' ] = array( 'name' => __( 'Botswana', 'maven' ),'code'=>'BW', 'currency' => array( 'code' => 'BWP', 'symbol' => 'P' ), 'units' => 'metric' );
		$countries[ 'BR' ] = array( 'name' => __( 'Brazil', 'maven' ),'code'=>'BR', 'currency' => array( 'code' => 'BRL', 'symbol' => 'R$' ), 'units' => 'metric' );
		$countries[ 'IO' ] = array( 'name' => __( 'British Indian Ocean Territory', 'maven' ),'code'=>'IO', 'currency' => array( 'code' => 'GBP', 'symbol' => '£' ), 'units' => 'metric' );
		$countries[ 'VG' ] = array( 'name' => __( 'British Virgin Islands', 'maven' ),'code'=>'VG', 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'BN' ] = array( 'name' => __( 'Brunei Darussalam', 'maven' ),'code'=>'BN', 'currency' => array( 'code' => 'BND', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'BG' ] = array( 'name' => __( 'Bulgaria', 'maven' ),'code'=>'BG', 'currency' => array( 'code' => 'BGN', 'symbol' => 'лв.' ), 'units' => 'metric' );
		$countries[ 'BF' ] = array( 'name' => __( 'Burkina Faso', 'maven' ),'code'=>'BF', 'currency' => array( 'code' => 'XOF', 'symbol' => 'CFA' ), 'units' => 'metric' );
		$countries[ 'MM' ] = array( 'name' => __( 'Burma', 'maven' ),'code'=>'MM', 'currency' => array( 'code' => 'MMK', 'symbol' => 'K' ), 'units' => 'metric' );
		$countries[ 'BI' ] = array( 'name' => __( 'Burundi', 'maven' ),'code'=>'BI', 'currency' => array( 'code' => 'BIF', 'symbol' => 'FBu' ), 'units' => 'metric' );
		$countries[ 'KH' ] = array( 'name' => __( 'Cambodia', 'maven' ),'code'=>'KH', 'currency' => array( 'code' => 'KHR', 'symbol' => '&#6107;' ), 'units' => 'metric' );
		$countries[ 'CM' ] = array( 'name' => __( 'Cameroon', 'maven' ),'code'=>'CM', 'currency' => array( 'code' => 'XAF', 'symbol' => 'FCFA' ), 'units' => 'metric' );
		$countries[ 'CV' ] = array( 'name' => __( 'Cape Verde', 'maven' ),'code'=>'CV', 'currency' => array( 'code' => 'CVE', 'symbol' => 'CV$' ), 'units' => 'metric' );
		$countries[ 'KY' ] = array( 'name' => __( 'Cayman Islands', 'maven' ),'code'=>'KY', 'currency' => array( 'code' => 'KYD', 'symbol' => 'CI$' ), 'units' => 'metric' );
		$countries[ 'CF' ] = array( 'name' => __( 'Central African Republic', 'maven' ),'code'=>'CF', 'currency' => array( 'code' => 'XAF', 'symbol' => 'FCFA' ), 'units' => 'metric' );
		$countries[ 'TD' ] = array( 'name' => __( 'Chad', 'maven' ),'code'=>'TD', 'currency' => array( 'code' => 'XAF', 'symbol' => 'FCFA' ), 'units' => 'metric' );
		$countries[ 'CL' ] = array( 'name' => __( 'Chile', 'maven' ),'code'=>'CL', 'currency' => array( 'code' => 'CLP', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'CN' ] = array( 'name' => __( 'China', 'maven' ),'code'=>'CN', 'currency' => array( 'code' => 'CNY', 'symbol' => '¥' ), 'units' => 'metric' );
		$countries[ 'CX' ] = array( 'name' => __( 'Christmas Island', 'maven' ),'code'=>'CX', 'currency' => array( 'code' => 'AUD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'CC' ] = array( 'name' => __( 'Cocos (Keeling) Islands', 'maven' ),'code'=>'CC', 'currency' => array( 'code' => 'AUD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'CO' ] = array( 'name' => __( 'Colombia', 'maven' ),'code'=>'CO', 'currency' => array( 'code' => 'COP', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'KM' ] = array( 'name' => __( 'Comoros', 'maven' ),'code'=>'KM', 'currency' => array( 'code' => 'KMF', 'symbol' => 'FC' ), 'units' => 'metric' );
		$countries[ 'CG' ] = array( 'name' => __( 'Congo-Brazzaville', 'maven' ),'code'=>'CG', 'currency' => array( 'code' => 'XAF', 'symbol' => 'FCFA' ), 'units' => 'metric' );
		$countries[ 'CD' ] = array( 'name' => __( 'Congo-Kinshasa', 'maven' ),'code'=>'CD', 'currency' => array( 'code' => 'CDF', 'symbol' => 'FrCD' ), 'units' => 'metric' );
		$countries[ 'CK' ] = array( 'name' => __( 'Cook Islands', 'maven' ),'code'=>'CK', 'currency' => array( 'code' => 'NZD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'CR' ] = array( 'name' => __( 'Costa Rica', 'maven' ),'code'=>'CR', 'currency' => array( 'code' => 'CRC', 'symbol' => '₡' ), 'units' => 'metric' );
		$countries[ 'CI' ] = array( 'name' => __( "Côte d'Ivoire", 'maven' ),'code'=>'CI', 'currency' => array( 'code' => 'XOF', 'symbol' => 'CFA' ), 'units' => 'metric' );
		$countries[ 'HR' ] = array( 'name' => __( 'Croatia', 'maven' ),'code'=>'HR', 'currency' => array( 'code' => 'HRK', 'symbol' => ' kn' ), 'units' => 'metric' );
		$countries[ 'CY' ] = array( 'name' => __( 'Cyprus', 'maven' ),'code'=>'CY', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'CZ' ] = array( 'name' => __( 'Czech Republic', 'maven' ),'code'=>'CZ', 'currency' => array( 'code' => 'CZK', 'symbol' => 'Kč' ), 'units' => 'metric' );
		$countries[ 'DK' ] = array( 'name' => __( 'Denmark', 'maven' ),'code'=>'DK', 'currency' => array( 'code' => 'DKK', 'symbol' => ' kr' ), 'units' => 'metric' );
		$countries[ 'DJ' ] = array( 'name' => __( 'Djibouti', 'maven' ),'code'=>'DJ', 'currency' => array( 'code' => 'DJF', 'symbol' => 'Fdj' ), 'units' => 'metric' );
		$countries[ 'DM' ] = array( 'name' => __( 'Dominica', 'maven' ),'code'=>'DM', 'currency' => array( 'code' => 'XCD', 'symbol' => 'EC$' ), 'units' => 'metric' );
		$countries[ 'DO' ] = array( 'name' => __( 'Dominican Republic', 'maven' ),'code'=>'DO', 'currency' => array( 'code' => 'DOP', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'TL' ] = array( 'name' => __( 'East Timor', 'maven' ),'code'=>'TL', 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'EC' ] = array( 'name' => __( 'Ecuador', 'maven' ),'code'=>'EC', 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'SV' ] = array( 'name' => __( 'El Salvador', 'maven' ),'code'=>'SV', 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'EG' ] = array( 'name' => __( 'Egypt', 'maven' ),'code'=>'EG', 'currency' => array( 'code' => 'EGP', 'symbol' => '£' ), 'units' => 'metric' );
		$countries[ 'GQ' ] = array( 'name' => __( 'Equatorial Guinea', 'maven' ),'code'=>'GQ', 'currency' => array( 'code' => 'XAF', 'symbol' => 'FCFA' ), 'units' => 'metric' );
		$countries[ 'ER' ] = array( 'name' => __( 'Eritrea', 'maven' ),'code'=>'ER', 'currency' => array( 'code' => 'ERN', 'symbol' => 'Nfk,' ), 'units' => 'metric' );
		$countries[ 'EE' ] = array( 'name' => __( 'Estonia', 'maven' ),'code'=>'EE', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'ET' ] = array( 'name' => __( 'Ethiopia', 'maven' ),'code'=>'ET', 'currency' => array( 'code' => 'ETB', 'symbol' => 'Br' ), 'units' => 'metric' );
		$countries[ 'FK' ] = array( 'name' => __( 'Falkland Islands', 'maven' ),'code'=>'FK', 'currency' => array( 'code' => 'FKP', 'symbol' => 'FK£' ), 'units' => 'metric' );
		$countries[ 'FO' ] = array( 'name' => __( 'Faroe Islands', 'maven' ),'code'=>'FO', 'currency' => array( 'code' => 'DKK', 'symbol' => 'kr' ), 'units' => 'metric' );
		$countries[ 'FM' ] = array( 'name' => __( 'Federated States of Micronesia', 'maven' ),'code'=>'FM', 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'FJ' ] = array( 'name' => __( 'Fiji', 'maven' ),'code'=>'FJ', 'currency' => array( 'code' => 'FJD', 'symbol' => 'FJ$' ), 'units' => 'metric' );
		$countries[ 'FI' ] = array( 'name' => __( 'Finland', 'maven' ),'code'=>'FI', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'FR' ] = array( 'name' => __( 'France', 'maven' ),'code'=>'FR', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'GF' ] = array( 'name' => __( 'French Guiana', 'maven' ),'code'=>'GF', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'PF' ] = array( 'name' => __( 'French Polynesia', 'maven' ),'code'=>'PF', 'currency' => array( 'code' => 'XPF', 'symbol' => 'F' ), 'units' => 'metric' );
		$countries[ 'TF' ] = array( 'name' => __( 'French Southern Lands', 'maven' ),'code'=>'TF', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'GA' ] = array( 'name' => __( 'Gabon', 'maven' ),'code'=>'GA', 'currency' => array( 'code' => 'XAF', 'symbol' => 'FCFA' ), 'units' => 'metric' );
		$countries[ 'GM' ] = array( 'name' => __( 'Gambia', 'maven' ),'code'=>'GM', 'currency' => array( 'code' => 'GMD', 'symbol' => 'GMD' ), 'units' => 'metric' );
		$countries[ 'GE' ] = array( 'name' => __( 'Georgia', 'maven' ),'code'=>'GE', 'currency' => array( 'code' => 'GEL', 'symbol' => 'GEL' ), 'units' => 'metric' );
		$countries[ 'DE' ] = array( 'name' => __( 'Germany', 'maven' ),'code'=>'DE', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'GH' ] = array( 'name' => __( 'Ghana', 'maven' ),'code'=>'GH', 'currency' => array( 'code' => 'GHS', 'symbol' => '₵' ), 'units' => 'metric' );
		$countries[ 'GI' ] = array( 'name' => __( 'Gibraltar', 'maven' ),'code'=>'GI', 'currency' => array( 'code' => 'GBP', 'symbol' => '£' ), 'units' => 'metric' );
		$countries[ 'GR' ] = array( 'name' => __( 'Greece', 'maven' ),'code'=>'GR', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'GL' ] = array( 'name' => __( 'Greenland', 'maven' ),'code'=>'GL', 'currency' => array( 'code' => 'DKK', 'symbol' => 'kr' ), 'units' => 'metric' );
		$countries[ 'GD' ] = array( 'name' => __( 'Grenada', 'maven' ),'code'=>'GD', 'currency' => array( 'code' => 'XCD', 'symbol' => 'EC$' ), 'units' => 'metric' );
		$countries[ 'GP' ] = array( 'name' => __( 'Guadeloupe', 'maven' ),'code'=>'GP', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'GU' ] = array( 'name' => __( 'Guam', 'maven' ),'code'=>'GU', 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'GT' ] = array( 'name' => __( 'Guatemala', 'maven' ),'code'=>'GT', 'currency' => array( 'code' => 'GTQ', 'symbol' => 'Q' ), 'units' => 'metric' );
		//$countries[ 'GG' ] = array( 'name' => __( 'Guernsey', 'maven' ),'code'=>'CA', 'currency' => array( 'code' => 'GBP', 'symbol' => '£' ), 'units' => 'metric' );
		$countries[ 'GN' ] = array( 'name' => __( 'Guinea', 'maven' ),'code'=>'GN', 'currency' => array( 'code' => 'GNF', 'symbol' => 'FG' ), 'units' => 'metric' );
		$countries[ 'GW' ] = array( 'name' => __( 'Guinea-Bissau', 'maven' ),'code'=>'GW', 'currency' => array( 'code' => 'XOF', 'symbol' => 'CFA' ), 'units' => 'metric' );
		$countries[ 'GY' ] = array( 'name' => __( 'Guyana', 'maven' ),'code'=>'GY', 'currency' => array( 'code' => 'GYD', 'symbol' => 'G$' ), 'units' => 'metric' );
		$countries[ 'HT' ] = array( 'name' => __( 'Haiti', 'maven' ),'code'=>'HT', 'currency' => array( 'code' => 'HTG', 'symbol' => 'HTG' ), 'units' => 'metric' );
		$countries[ 'HM' ] = array( 'name' => __( 'Heard and McDonald Islands', 'maven' ),'code'=>'HM', 'currency' => array( 'code' => 'AUD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'HN' ] = array( 'name' => __( 'Honduras', 'maven' ),'code'=>'HN', 'currency' => array( 'code' => 'HNL', 'symbol' => 'L' ), 'units' => 'metric' );
		$countries[ 'HK' ] = array( 'name' => __( 'Hong Kong', 'maven' ),'code'=>'HK', 'currency' => array( 'code' => 'HKD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'HU' ] = array( 'name' => __( 'Hungary', 'maven' ),'code'=>'HU', 'currency' => array( 'code' => 'HUF', 'symbol' => 'Ft' ), 'units' => 'metric' );
		$countries[ 'IS' ] = array( 'name' => __( 'Iceland', 'maven' ),'code'=>'IS', 'currency' => array( 'code' => 'ISK', 'symbol' => 'kr.' ), 'units' => 'metric' );
		$countries[ 'IN' ] = array( 'name' => __( 'India', 'maven' ),'code'=>'IN', 'currency' => array( 'code' => 'INR', 'symbol' => '₨' ), 'units' => 'metric' );
		$countries[ 'ID' ] = array( 'name' => __( 'Indonesia', 'maven' ),'code'=>'ID', 'currency' => array( 'code' => 'IDR', 'symbol' => 'Rp' ), 'units' => 'metric' );
		$countries[ 'IQ' ] = array( 'name' => __( 'Iraq', 'maven' ),'code'=>'IQ', 'currency' => array( 'code' => 'IQD', 'symbol' => 'ع.د' ), 'units' => 'metric' );
		$countries[ 'IE' ] = array( 'name' => __( 'Ireland', 'maven' ),'code'=>'IE', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		//$countries[ 'IM' ] = array( 'name' => __( 'Isle of Man', 'maven' ),'code'=>'CA', 'currency' => array( 'code' => 'GBP', 'symbol' => '£' ), 'units' => 'metric' );
		$countries[ 'IL' ] = array( 'name' => __( 'Israel', 'maven' ),'code'=>'IL', 'currency' => array( 'code' => 'ILS', 'symbol' => '₪' ), 'units' => 'metric' );
		$countries[ 'IT' ] = array( 'name' => __( 'Italy', 'maven' ),'code'=>'IT', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'JM' ] = array( 'name' => __( 'Jamaica', 'maven' ),'code'=>'JM', 'currency' => array( 'code' => 'JMD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'JP' ] = array( 'name' => __( 'Japan', 'maven' ),'code'=>'JP', 'currency' => array( 'code' => 'JPY', 'symbol' => '¥' ), 'units' => 'metric' );
		//$countries[ 'JE' ] = array( 'name' => __( 'Jersey', 'maven' ),'code'=>'CA', 'currency' => array( 'code' => 'GBP', 'symbol' => '£' ), 'units' => 'metric' );
		$countries[ 'JO' ] = array( 'name' => __( 'Jordan', 'maven' ),'code'=>'JO', 'currency' => array( 'code' => 'JOD', 'symbol' => 'JD' ), 'units' => 'metric' );
		$countries[ 'KZ' ] = array( 'name' => __( 'Kazakhstan', 'maven' ),'code'=>'KZ', 'currency' => array( 'code' => 'KZT', 'symbol' => '〒' ), 'units' => 'metric' );
		$countries[ 'KE' ] = array( 'name' => __( 'Kenya', 'maven' ),'code'=>'KE', 'currency' => array( 'code' => 'KES', 'symbol' => 'Ksh' ), 'units' => 'metric' );
		$countries[ 'KI' ] = array( 'name' => __( 'Kiribati', 'maven' ),'code'=>'KI', 'currency' => array( 'code' => 'AUD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'KW' ] = array( 'name' => __( 'Kuwait', 'maven' ),'code'=>'KW', 'currency' => array( 'code' => 'KWD', 'symbol' => 'د.ك' ), 'units' => 'metric' );
		$countries[ 'KG' ] = array( 'name' => __( 'Kyrgyzstan', 'maven' ),'code'=>'KG', 'currency' => array( 'code' => 'KGS', 'symbol' => 'som' ), 'units' => 'metric' );
		$countries[ 'LA' ] = array( 'name' => __( 'Laos', 'maven' ),'code'=>'LA', 'currency' => array( 'code' => 'LAK', 'symbol' => '₭' ), 'units' => 'metric' );
		$countries[ 'LV' ] = array( 'name' => __( 'Latvia', 'maven' ),'code'=>'LV', 'currency' => array( 'code' => 'LVL', 'symbol' => 'Ls' ), 'units' => 'metric' );
		$countries[ 'LB' ] = array( 'name' => __( 'Lebanon', 'maven' ),'code'=>'LB', 'currency' => array( 'code' => 'LBP', 'symbol' => 'ل.ل' ), 'units' => 'metric' );
		$countries[ 'LS' ] = array( 'name' => __( 'Lesotho', 'maven' ),'code'=>'LS', 'currency' => array( 'code' => 'LSL', 'symbol' => 'M' ), 'units' => 'metric' );
		$countries[ 'LR' ] = array( 'name' => __( 'Liberia', 'maven' ),'code'=>'LR', 'currency' => array( 'code' => 'LRD', 'symbol' => 'LD$' ), 'units' => 'metric' );
		$countries[ 'LY' ] = array( 'name' => __( 'Libya', 'maven' ),'code'=>'LY', 'currency' => array( 'code' => 'LYD', 'symbol' => 'ل.د' ), 'units' => 'metric' );
		$countries[ 'LI' ] = array( 'name' => __( 'Liechtenstein', 'maven' ),'code'=>'LI', 'currency' => array( 'code' => 'CHF', 'symbol' => "CHF'" ), 'units' => 'metric' );
		$countries[ 'LT' ] = array( 'name' => __( 'Lithuania', 'maven' ),'code'=>'LT', 'currency' => array( 'code' => 'LTL', 'symbol' => 'Lt' ), 'units' => 'metric' );
		$countries[ 'LU' ] = array( 'name' => __( 'Luxembourg', 'maven' ),'code'=>'LU', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'MO' ] = array( 'name' => __( 'Macau', 'maven' ),'code'=>'MO', 'currency' => array( 'code' => 'MOP', 'symbol' => 'MOP$' ), 'units' => 'metric' );
		$countries[ 'MK' ] = array( 'name' => __( 'Macedonia', 'maven' ),'code'=>'MK', 'currency' => array( 'code' => 'MKD', 'symbol' => 'MKD' ), 'units' => 'metric' );
		$countries[ 'MG' ] = array( 'name' => __( 'Madagascar', 'maven' ),'code'=>'MG', 'currency' => array( 'code' => 'MGA', 'symbol' => 'MGA' ), 'units' => 'metric' );
		$countries[ 'MW' ] = array( 'name' => __( 'Malawi', 'maven' ),'code'=>'MW', 'currency' => array( 'code' => 'MWK', 'symbol' => 'MK' ), 'units' => 'metric' );
		$countries[ 'MY' ] = array( 'name' => __( 'Malaysia', 'maven' ),'code'=>'MY', 'currency' => array( 'code' => 'MYR', 'symbol' => 'RM' ), 'units' => 'metric' );
		$countries[ 'MV' ] = array( 'name' => __( 'Maldives', 'maven' ),'code'=>'MV', 'currency' => array( 'code' => 'MVR', 'symbol' => 'Rf' ), 'units' => 'metric' );
		$countries[ 'ML' ] = array( 'name' => __( 'Mali', 'maven' ),'code'=>'ML', 'currency' => array( 'code' => 'XOF', 'symbol' => 'CFA' ), 'units' => 'metric' );
		$countries[ 'MT' ] = array( 'name' => __( 'Malta', 'maven' ),'code'=>'MT', 'currency' => array( 'code' => 'MTL', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'MH' ] = array( 'name' => __( 'Marshall Islands', 'maven' ),'code'=>'MH', 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'MQ' ] = array( 'name' => __( 'Martinique', 'maven' ),'code'=>'MQ', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'MR' ] = array( 'name' => __( 'Mauritania', 'maven' ),'code'=>'MR', 'currency' => array( 'code' => 'MRO', 'symbol' => 'UM' ), 'units' => 'metric' );
		$countries[ 'MU' ] = array( 'name' => __( 'Mauritius', 'maven' ),'code'=>'MU', 'currency' => array( 'code' => 'MUR', 'symbol' => 'MU₨' ), 'units' => 'metric' );
		$countries[ 'YT' ] = array( 'name' => __( 'Mayotte', 'maven' ),'code'=>'YT', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'MX' ] = array( 'name' => __( 'Mexico', 'maven' ),'code'=>'MX', 'currency' => array( 'code' => 'MXN', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'MD' ] = array( 'name' => __( 'Moldova', 'maven' ),'code'=>'MD', 'currency' => array( 'code' => 'MDL', 'symbol' => 'MDL' ), 'units' => 'metric' );
		$countries[ 'MC' ] = array( 'name' => __( 'Monaco', 'maven' ),'code'=>'MC', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'MN' ] = array( 'name' => __( 'Mongolia', 'maven' ),'code'=>'MN', 'currency' => array( 'code' => 'MNT', 'symbol' => '₮' ), 'units' => 'metric' );
		$countries[ 'ME' ] = array( 'name' => __( 'Montenegro', 'maven' ),'code'=>'ME', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'MS' ] = array( 'name' => __( 'Montserrat', 'maven' ),'code'=>'MS', 'currency' => array( 'code' => 'XCD', 'symbol' => 'EC$' ), 'units' => 'metric' );
		$countries[ 'MA' ] = array( 'name' => __( 'Morocco', 'maven' ),'code'=>'MA', 'currency' => array( 'code' => 'MAD', 'symbol' => 'د.م.' ), 'units' => 'metric' );
		$countries[ 'MZ' ] = array( 'name' => __( 'Mozambique', 'maven' ),'code'=>'MZ', 'currency' => array( 'code' => 'MZN', 'symbol' => 'MTn' ), 'units' => 'metric' );
		$countries[ 'NA' ] = array( 'name' => __( 'Namibia', 'maven' ),'code'=>'NA', 'currency' => array( 'code' => 'NAD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'NR' ] = array( 'name' => __( 'Nauru', 'maven' ),'code'=>'NR', 'currency' => array( 'code' => 'AUD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'NP' ] = array( 'name' => __( 'Nepal', 'maven' ),'code'=>'NP', 'currency' => array( 'code' => 'NPR', 'symbol' => 'रू.' ), 'units' => 'metric' );
		$countries[ 'NL' ] = array( 'name' => __( 'Netherlands', 'maven' ),'code'=>'NL', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'NC' ] = array( 'name' => __( 'New Caledonia', 'maven' ),'code'=>'NC', 'currency' => array( 'code' => 'XPF', 'symbol' => 'F' ), 'units' => 'metric' );
		$countries[ 'NZ' ] = array( 'name' => __( 'New Zealand', 'maven' ),'code'=>'NZ', 'currency' => array( 'code' => 'NZD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'NI' ] = array( 'name' => __( 'Nicaragua', 'maven' ),'code'=>'NI', 'currency' => array( 'code' => 'NIO', 'symbol' => 'C$' ), 'units' => 'metric' );
		$countries[ 'NE' ] = array( 'name' => __( 'Niger', 'maven' ),'code'=>'NE', 'currency' => array( 'code' => 'XOF', 'symbol' => 'CFA' ), 'units' => 'metric' );
		$countries[ 'NG' ] = array( 'name' => __( 'Nigeria', 'maven' ),'code'=>'NG', 'currency' => array( 'code' => 'NGN', 'symbol' => '₦' ), 'units' => 'metric' );
		$countries[ 'NU' ] = array( 'name' => __( 'Niue', 'maven' ),'code'=>'NU', 'currency' => array( 'code' => 'NZD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'NF' ] = array( 'name' => __( 'Norfolk Island', 'maven' ),'code'=>'NF', 'currency' => array( 'code' => 'AUD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'MP' ] = array( 'name' => __( 'Northern Mariana Islands', 'maven' ),'code'=>'MP', 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'NO' ] = array( 'name' => __( 'Norway', 'maven' ),'code'=>'NO', 'currency' => array( 'code' => 'NOK', 'symbol' => 'kr' ), 'units' => 'metric' );
		$countries[ 'OM' ] = array( 'name' => __( 'Oman', 'maven' ),'code'=>'OM', 'currency' => array( 'code' => 'OMR', 'symbol' => 'ر.ع' ), 'units' => 'metric' );
		$countries[ 'PK' ] = array( 'name' => __( 'Pakistan', 'maven' ),'code'=>'PK', 'currency' => array( 'code' => 'PKR', 'symbol' => '₨' ), 'units' => 'metric' );
		$countries[ 'PW' ] = array( 'name' => __( 'Palau', 'maven' ),'code'=>'PW', 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'PA' ] = array( 'name' => __( 'Panama', 'maven' ),'code'=>'PA', 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'PG' ] = array( 'name' => __( 'Papua New Guinea', 'maven' ),'code'=>'PG', 'currency' => array( 'code' => 'PGK', 'symbol' => 'K' ), 'units' => 'metric' );
		$countries[ 'PY' ] = array( 'name' => __( 'Paraguay', 'maven' ),'code'=>'PY', 'currency' => array( 'code' => 'PYG', 'symbol' => '₲' ), 'units' => 'metric' );
		$countries[ 'PE' ] = array( 'name' => __( 'Peru', 'maven' ),'code'=>'PE', 'currency' => array( 'code' => 'PEN', 'symbol' => 'S/.' ), 'units' => 'metric' );
		$countries[ 'PH' ] = array( 'name' => __( 'Philippines', 'maven' ),'code'=>'PH', 'currency' => array( 'code' => 'PHP', 'symbol' => 'Php' ), 'units' => 'metric' );
		$countries[ 'PN' ] = array( 'name' => __( 'Pitcairn Islands', 'maven' ),'code'=>'PN', 'currency' => array( 'code' => 'NZD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'PL' ] = array( 'name' => __( 'Poland', 'maven' ),'code'=>'PL', 'currency' => array( 'code' => 'PLN', 'symbol' => 'zł' ), 'units' => 'metric' );
		$countries[ 'PT' ] = array( 'name' => __( 'Portugal', 'maven' ),'code'=>'PT', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'PR' ] = array( 'name' => __( 'Puerto Rico', 'maven' ),'code'=>'PR', 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'imperial' );
		$countries[ 'QA' ] = array( 'name' => __( 'Qatar', 'maven' ),'code'=>'QA', 'currency' => array( 'code' => 'QAR', 'symbol' => 'ر.ق' ), 'units' => 'metric' );
		$countries[ 'RE' ] = array( 'name' => __( 'Réunion', 'maven' ),'code'=>'RE', 'currency' => array( 'code' => '', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'RO' ] = array( 'name' => __( 'Romania', 'maven' ),'code'=>'RO', 'currency' => array( 'code' => 'ROL', 'symbol' => 'lei' ), 'units' => 'metric' );
		$countries[ 'RU' ] = array( 'name' => __( 'Russia', 'maven' ),'code'=>'RU', 'currency' => array( 'code' => 'RUB', 'symbol' => 'руб' ), 'units' => 'metric' );
		$countries[ 'RW' ] = array( 'name' => __( 'Rwanda', 'maven' ),'code'=>'RW', 'currency' => array( 'code' => 'RWF', 'symbol' => 'RF' ), 'units' => 'metric' );
		//$countries[ 'BL' ] = array( 'name' => __( 'Saint Barthélemy', 'maven' ),'code'=>'CA', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'SH' ] = array( 'name' => __( 'Saint Helena', 'maven' ),'code'=>'SH', 'currency' => array( 'code' => 'SHP', 'symbol' => '£' ), 'units' => 'metric' );
		$countries[ 'KN' ] = array( 'name' => __( 'Saint Kitts and Nevis', 'maven' ),'code'=>'KN', 'currency' => array( 'code' => 'XCD', 'symbol' => 'EC$' ), 'units' => 'metric' );
		$countries[ 'LC' ] = array( 'name' => __( 'Saint Lucia', 'maven' ),'code'=>'LC', 'currency' => array( 'code' => 'XCD', 'symbol' => 'EC$' ), 'units' => 'metric' );
		//$countries[ 'MF' ] = array( 'name' => __( 'Saint Martin', 'maven' ),'code'=>'CA', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'PM' ] = array( 'name' => __( 'Saint Pierre and Miquelon', 'maven' ),'code'=>'PM', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'VC' ] = array( 'name' => __( 'Saint Vincent', 'maven' ),'code'=>'VC', 'currency' => array( 'code' => 'XCD', 'symbol' => 'EC$' ), 'units' => 'metric' );
		$countries[ 'WS' ] = array( 'name' => __( 'Samoa', 'maven' ),'code'=>'WS', 'currency' => array( 'code' => 'WST', 'symbol' => 'WS$' ), 'units' => 'metric' );
		$countries[ 'SM' ] = array( 'name' => __( 'San Marino', 'maven' ),'code'=>'SM', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'ST' ] = array( 'name' => __( 'São Tomé and Príncipe', 'maven' ),'code'=>'ST', 'currency' => array( 'code' => 'STD', 'symbol' => 'Db ' ), 'units' => 'metric' );
		$countries[ 'SA' ] = array( 'name' => __( 'Saudi Arabia', 'maven' ),'code'=>'SA', 'currency' => array( 'code' => 'SAR', 'symbol' => 'ر.س' ), 'units' => 'metric' );
		$countries[ 'SN' ] = array( 'name' => __( 'Senegal', 'maven' ),'code'=>'SN', 'currency' => array( 'code' => 'XOF', 'symbol' => 'CFA' ), 'units' => 'metric' );
		$countries[ 'RS' ] = array( 'name' => __( 'Serbia', 'maven' ),'code'=>'RS', 'currency' => array( 'code' => 'RSD', 'symbol' => 'din.' ), 'units' => 'metric' );
		$countries[ 'SC' ] = array( 'name' => __( 'Seychelles', 'maven' ),'code'=>'SC', 'currency' => array( 'code' => 'SCR', 'symbol' => '₨' ), 'units' => 'metric' );
		$countries[ 'SL' ] = array( 'name' => __( 'Sierra Leone', 'maven' ),'code'=>'SL', 'currency' => array( 'code' => 'SLL', 'symbol' => 'Le' ), 'units' => 'metric' );
		$countries[ 'SG' ] = array( 'name' => __( 'Singapore', 'maven' ),'code'=>'SG', 'currency' => array( 'code' => 'SGD', 'symbol' => '$' ), 'units' => 'metric' );
		//$countries[ 'SX' ] = array( 'name' => __( 'Sint Maarten', 'maven' ),'code'=>'CA', 'currency' => array( 'code' => 'ANG', 'symbol' => 'ƒ' ), 'units' => 'metric' );
		$countries[ 'SK' ] = array( 'name' => __( 'Slovakia', 'maven' ),'code'=>'SK', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'SI' ] = array( 'name' => __( 'Slovenia', 'maven' ),'code'=>'SI', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'SB' ] = array( 'name' => __( 'Solomon Islands', 'maven' ),'code'=>'SB', 'currency' => array( 'code' => 'SBD', 'symbol' => 'SI$' ), 'units' => 'metric' );
		$countries[ 'SO' ] = array( 'name' => __( 'Somalia', 'maven' ),'code'=>'SO', 'currency' => array( 'code' => 'SOS', 'symbol' => 'Ssh' ), 'units' => 'metric' );
		$countries[ 'ZA' ] = array( 'name' => __( 'South Africa', 'maven' ),'code'=>'ZA', 'currency' => array( 'code' => 'ZAR', 'symbol' => 'R' ), 'units' => 'metric' );
		$countries[ 'GS' ] = array( 'name' => __( 'South Georgia', 'maven' ),'code'=>'GS', 'currency' => array( 'code' => 'GBP', 'symbol' => '£' ), 'units' => 'metric' );
		$countries[ 'KR' ] = array( 'name' => __( 'South Korea', 'maven' ),'code'=>'KR', 'currency' => array( 'code' => 'KRW', 'symbol' => '₩' ), 'units' => 'metric' );
		$countries[ 'ES' ] = array( 'name' => __( 'Spain', 'maven' ),'code'=>'ES', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'LK' ] = array( 'name' => __( 'Sri Lanka', 'maven' ),'code'=>'LK', 'currency' => array( 'code' => 'LKR', 'symbol' => 'SL₨' ), 'units' => 'metric' );
		$countries[ 'SD' ] = array( 'name' => __( 'Sudan', 'maven' ),'code'=>'SD', 'currency' => array( 'code' => 'SDG', 'symbol' => 'SDG ' ), 'units' => 'metric' );
		$countries[ 'SR' ] = array( 'name' => __( 'Suriname', 'maven' ),'code'=>'SR', 'currency' => array( 'code' => 'SRD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'SJ' ] = array( 'name' => __( 'Svalbard and Jan Mayen', 'maven' ),'code'=>'SJ', 'currency' => array( 'code' => 'NOK', 'symbol' => 'kr' ), 'units' => 'metric' );
		$countries[ 'SE' ] = array( 'name' => __( 'Sweden', 'maven' ),'code'=>'SE', 'currency' => array( 'code' => 'SEK', 'symbol' => 'kr' ), 'units' => 'metric' );
		$countries[ 'SZ' ] = array( 'name' => __( 'Swaziland', 'maven' ),'code'=>'SZ', 'currency' => array( 'code' => 'SZL', 'symbol' => 'E' ), 'units' => 'metric' );
		$countries[ 'CH' ] = array( 'name' => __( 'Switzerland', 'maven' ),'code'=>'CH', 'currency' => array( 'code' => 'CHF', 'symbol' => "CHF" ), 'units' => 'metric' );
		$countries[ 'SY' ] = array( 'name' => __( 'Syria', 'maven' ),'code'=>'SY', 'currency' => array( 'code' => 'SYP', 'symbol' => '£S' ), 'units' => 'metric' );
		$countries[ 'TW' ] = array( 'name' => __( 'Taiwan', 'maven' ),'code'=>'TW', 'currency' => array( 'code' => 'TWD', 'symbol' => 'NT$' ), 'units' => 'metric' );
		$countries[ 'TJ' ] = array( 'name' => __( 'Tajikistan', 'maven' ),'code'=>'TJ', 'currency' => array( 'code' => 'TJS', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'TZ' ] = array( 'name' => __( 'Tanzania', 'maven' ),'code'=>'TZ', 'currency' => array( 'code' => 'TZS', 'symbol' => 'TSh' ), 'units' => 'metric' );
		$countries[ 'TH' ] = array( 'name' => __( 'Thailand', 'maven' ),'code'=>'TH', 'currency' => array( 'code' => 'THB', 'symbol' => '฿' ), 'units' => 'metric' );
		$countries[ 'TG' ] = array( 'name' => __( 'Togo', 'maven' ),'code'=>'TG', 'currency' => array( 'code' => 'XOF', 'symbol' => 'CFA' ), 'units' => 'metric' );
		$countries[ 'TK' ] = array( 'name' => __( 'Tokelau', 'maven' ),'code'=>'TK', 'currency' => array( 'code' => 'NZD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'TO' ] = array( 'name' => __( 'Tonga', 'maven' ),'code'=>'TO', 'currency' => array( 'code' => 'TOP', 'symbol' => 'T$' ), 'units' => 'metric' );
		$countries[ 'TT' ] = array( 'name' => __( 'Trinidad and Tobago', 'maven' ),'code'=>'TT', 'currency' => array( 'code' => 'TTD', 'symbol' => 'TT$' ), 'units' => 'metric' );
		$countries[ 'TN' ] = array( 'name' => __( 'Tunisia', 'maven' ),'code'=>'TN', 'currency' => array( 'code' => 'TND', 'symbol' => 'د.ت' ), 'units' => 'metric' );
		$countries[ 'TR' ] = array( 'name' => __( 'Turkey', 'maven' ),'code'=>'TR', 'currency' => array( 'code' => 'TRL', 'symbol' => ' TL' ), 'units' => 'metric' );
		$countries[ 'TM' ] = array( 'name' => __( 'Turkmenistan', 'maven' ),'code'=>'TM', 'currency' => array( 'code' => 'TMT', 'symbol' => 'm' ), 'units' => 'metric' );
		$countries[ 'TC' ] = array( 'name' => __( 'Turks and Caicos Islands', 'maven' ),'code'=>'TC', 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'TV' ] = array( 'name' => __( 'Tuvalu', 'maven' ),'code'=>'TV', 'currency' => array( 'code' => 'AUD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'UG' ] = array( 'name' => __( 'Uganda', 'maven' ),'code'=>'UG', 'currency' => array( 'code' => 'UGX', 'symbol' => 'USh' ), 'units' => 'metric' );
		$countries[ 'UA' ] = array( 'name' => __( 'Ukraine', 'maven' ),'code'=>'UA', 'currency' => array( 'code' => 'UAH', 'symbol' => '₴' ), 'units' => 'metric' );
		$countries[ 'AE' ] = array( 'name' => __( 'United Arab Emirates', 'maven' ),'code'=>'AE', 'currency' => array( 'code' => 'AED', 'symbol' => 'Dhs.' ), 'units' => 'metric' );
		$countries[ 'UY' ] = array( 'name' => __( 'Uruguay', 'maven' ),'code'=>'UY', 'currency' => array( 'code' => 'UYP', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'UZ' ] = array( 'name' => __( 'Uzbekistan', 'maven' ),'code'=>'UZ', 'currency' => array( 'code' => 'UZS', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'VU' ] = array( 'name' => __( 'Vanuatu', 'maven' ),'code'=>'VU', 'currency' => array( 'code' => 'VUV', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'VA' ] = array( 'name' => __( 'Vatican City', 'maven' ),'code'=>'VA', 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'VN' ] = array( 'name' => __( 'Vietnam', 'maven' ),'code'=>'VN', 'currency' => array( 'code' => 'VND', 'symbol' => '₫' ), 'units' => 'metric' );
		$countries[ 'VE' ] = array( 'name' => __( 'Venezuela', 'maven' ),'code'=>'VE', 'currency' => array( 'code' => 'VUB', 'symbol' => 'Bs.' ), 'units' => 'metric' );
		$countries[ 'WF' ] = array( 'name' => __( 'Wallis and Futuna', 'maven' ),'code'=>'WF', 'currency' => array( 'code' => 'XPF', 'symbol' => 'F' ), 'units' => 'metric' );
		$countries[ 'EH' ] = array( 'name' => __( 'Western Sahara', 'maven' ),'code'=>'EH', 'currency' => array( 'code' => 'MAD', 'symbol' => 'درهم' ), 'units' => 'metric' );
		$countries[ 'YE' ] = array( 'name' => __( 'Yemen', 'maven' ),'code'=>'YE', 'currency' => array( 'code' => 'YER', 'symbol' => '.ر.ي' ), 'units' => 'metric' );
		$countries[ 'ZM' ] = array( 'name' => __( 'Zambia', 'maven' ),'code'=>'ZM', 'currency' => array( 'code' => 'ZMK', 'symbol' => 'ZK' ), 'units' => 'metric' );
		$countries[ 'ZW' ] = array( 'name' => __( 'Zimbabwe', 'maven' ),'code'=>'ZW', 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );

		$this->countries = $countries;
	}

	public function get ( $code ) {

		if ( !$code ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Country code is required' );
		}

		return $this->countries[ $code ];
	}

	public function getAll ( $worldwide = true ) {


		$countries = $this->countries;

		if ( !$worldwide ) {
			array_shift( $countries );
		}

		return $countries;
	}

	function getStates ( $country  ) {
		$s[ 'AR' ][ 'MI' ] = 'Misiones';

		$states = array(
			'AL' => array( 'name' => __('Alabama')),
			'AK'=>array( 'name' => __('Alaska')),
			'AS'=>array( 'name' => __('American Samoa')),
			'AZ'=>array( 'name' => __('Arizona')),
			'AR'=>array( 'name' => __('Arkansas')),
			'AE'=>array( 'name' => __('Armed Forces - Europe')),
			'AP'=>array( 'name' => __('Armed Forces - Pacific')),
			'AA'=>array( 'name' => __('Armed Forces - USA/Canada')),
			'CA'=>array( 'name' => __('California')),
			'CO'=>array( 'name' => __('Colorado')),
			'CT'=>array( 'name' => __('Connecticut')),
			'DE'=>array( 'name' => __('Delaware')),
			'DC'=>array( 'name' => __('District of Columbia')),
			'FL'=>array( 'name' => __('Florida')),
			'GA'=>array( 'name' => __('Georgia')),
			'GU'=>array( 'name' => __('Guam')),
			'HI'=>array( 'name' => __('Hawaii')),
			'ID'=>array( 'name' => __('Idaho')),
			'IL'=>array( 'name' => __('Illinois')),
			'IN'=>array( 'name' => __('Indiana')),
			'IA'=>array( 'name' => __('Iowa')),
			'KS'=>array( 'name' => __('Kansas')),
			'KY'=>array( 'name' => __('Kentucky')),
			'LA'=>array( 'name' => __('Louisiana')),
			'ME'=>array( 'name' => __('Maine')),
			'MD'=>array( 'name' => __('Maryland')),
			'MA'=>array( 'name' => __('Massachusetts')),
			'MI'=>array( 'name' => __('Michigan')),
			'MN'=>array( 'name' => __('Minnesota')),
			'MS'=>array( 'name' => __('Mississippi')),
			'MO'=>array( 'name' => __('Missouri')),
			'MT'=>array( 'name' => __('Montana')),
			'NE'=>array( 'name' => __('Nebraska')),
			'NV'=>array( 'name' => __('Nevada')),
			'NH'=>array( 'name' => __('New Hampshire')),
			'NJ'=>array( 'name' => __('New Jersey')),
			'NM'=>array( 'name' => __('New Mexico')),
			'NY'=>array( 'name' => __('New York')),
			'NC'=>array( 'name' => __('North Carolina')),
			'ND'=>array( 'name' => __('North Dakota')),
			'OH'=>array( 'name' => __('Ohio')),
			'OK'=>array( 'name' => __('Oklahoma')),
			'OR'=>array( 'name' => __('Oregon')),
			'PA'=>array( 'name' => __('Pennsylvania')),
			'PR'=>array( 'name' => __('Puerto Rico')),
			'RI'=>array( 'name' => __('Rhode Island')),
			'SC'=>array( 'name' => __('South Carolina')),
			'SD'=>array( 'name' => __('South Dakota')),
			'TN'=>array( 'name' => __('Tennessee')),
			'TX'=>array( 'name' => __('Texas')),
			'UT'=>array( 'name' => __('Utah')),
			'VT'=>array( 'name' => __('Vermont')),
			'VI'=>array( 'name' => __('Virgin Islands')),
			'VA'=>array( 'name' => __('Virginia')),
			'WA'=>array( 'name' => __('Washington')),
			'WV'=>array( 'name' => __('West Virginia')),
			'WI'=>array( 'name' => __('Wisconsin')),
			'WY'=>array( 'name' => __('Wyoming'))
		);

		$s[ 'US' ] = $states;


		$states = array( 'AB'=>array( 'name' => __('Alberta')),
			'BC'=>array( 'name' => __('British Columbia')),
			'MB'=>array( 'name' => __('Manitoba')),
			'NB'=>array( 'name' => __('New Brunswick')),
			'NL'=>array( 'name' => __('Newfoundland and Labrador')),
			'NT'=>array( 'name' => __('Northwest Territories')),
			'NS'=>array( 'name' => __('Newfoundland and Labrador')),
			'NL'=>array( 'name' => __('Nova Scotia')),
			'NU'=>array( 'name' => __('Nunavut')),
			'ON'=>array( 'name' => __('Ontario')),
			'PE'=>array( 'name' => __('Prince Edward Island')),
			'QC'=>array( 'name' => __('Quebec')),
			'SK'=>array( 'name' => __('Saskatchewan')),
			'YT'=>array( 'name' => __('Yukon' )));

		$s[ 'CA' ] = $states;


		$states = array( '26'=>array( 'name' => __('Busan')),
			'43'=>array( 'name' => __('Chungcheongbuk-do')),
			'44'=>array( 'name' => __('Chungcheongnam-do')),
			'27'=>array( 'name' => __('Daegu')),
			'30'=>array( 'name' => __('Daejeon')),
			'42'=>array( 'name' => __('Gangwon-do')),
			'29'=>array( 'name' => __('Gwangju')),
			'41'=>array( 'name' => __('Gyeonggi-do')),
			'47'=>array( 'name' => __('Gyeongsangbuk-do')),
			'28'=>array( 'name' => __('Incheon')),
			'49'=>array( 'name' => __('Jeju-do')),
			'45'=>array( 'name' => __('Jeollabuk-do')),
			'46'=>array( 'name' => __('Jeollanam-do')),
			'11'=>array( 'name' => __('Seoul')),
			'31'=>array( 'name' => __('Ulsan' )));

		$s[ 'KR' ] = $states;

		if ( $country ) {
			if ( isset( $s[ $country ] ) ) {
				return $s[ $country ];
			}
		}  

		return array();
	}

}
