<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class CountryManager {

	private $countries = array();

	public function __construct () {
		$countries[ '*' ] = array( 'name' => __( 'Worldwide', 'maven' ), 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );

		$countries[ 'CA' ] = array( 'name' => __( 'Canada', 'maven' ), 'currency' => array( 'code' => 'CAD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'US' ] = array( 'name' => __( 'USA', 'maven' ), 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'imperial' );

		// Specialized countries for US Armed Forces and US Territories
		//$countries[ 'USAF' ] = array( 'name' => __( 'US Armed Forces', 'maven' ), 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'imperial' );
		//$countries[ 'USAT' ] = array( 'name' => __( 'US Territories', 'maven' ), 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'imperial' );

		$countries[ 'GB' ] = array( 'name' => __( 'United Kingdom', 'maven' ), 'currency' => array( 'code' => 'GBP', 'symbol' => '£' ), 'units' => 'metric' );
		$countries[ 'AF' ] = array( 'name' => __( 'Afghanistan', 'maven' ), 'currency' => array( 'code' => 'AFN', 'symbol' => 'AFN' ), 'units' => 'metric' );
		$countries[ 'AX' ] = array( 'name' => __( 'Åland Islands', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'AL' ] = array( 'name' => __( 'Albania', 'maven' ), 'currency' => array( 'code' => 'ALL', 'symbol' => 'Lek' ), 'units' => 'metric' );
		$countries[ 'DZ' ] = array( 'name' => __( 'Algeria', 'maven' ), 'currency' => array( 'code' => 'DZD', 'symbol' => 'د.ج' ), 'units' => 'metric' );
		$countries[ 'AS' ] = array( 'name' => __( 'American Samoa', 'maven' ), 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'AD' ] = array( 'name' => __( 'Andorra', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'AO' ] = array( 'name' => __( 'Angola', 'maven' ), 'currency' => array( 'code' => 'AOA', 'symbol' => 'Kz' ), 'units' => 'metric' );
		$countries[ 'AI' ] = array( 'name' => __( 'Anguilla', 'maven' ), 'currency' => array( 'code' => 'XCD', 'symbol' => 'EC$' ), 'units' => 'metric' );
		$countries[ 'AG' ] = array( 'name' => __( 'Antigua and Barbuda', 'maven' ), 'currency' => array( 'code' => 'XCD', 'symbol' => 'EC$' ), 'units' => 'metric' );
		$countries[ 'AR' ] = array( 'name' => __( 'Argentina', 'maven' ), 'currency' => array( 'code' => 'ARS', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'AM' ] = array( 'name' => __( 'Armenia', 'maven' ), 'currency' => array( 'code' => 'AMD', 'symbol' => '####,## Դրամ' ), 'units' => 'metric' );
		$countries[ 'AW' ] = array( 'name' => __( 'Aruba', 'maven' ), 'currency' => array( 'code' => 'AWG', 'symbol' => 'ƒ' ), 'units' => 'metric' );
		$countries[ 'AU' ] = array( 'name' => __( 'Australia', 'maven' ), 'currency' => array( 'code' => 'AUD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'AT' ] = array( 'name' => __( 'Austria', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'AZ' ] = array( 'name' => __( 'Azerbaijan', 'maven' ), 'currency' => array( 'code' => 'AZN', 'symbol' => 'man.' ), 'units' => 'metric' );
		$countries[ 'BD' ] = array( 'name' => __( 'Bangladesh', 'maven' ), 'currency' => array( 'code' => 'BDT', 'symbol' => '&#2547;' ), 'units' => 'metric' );
		$countries[ 'BB' ] = array( 'name' => __( 'Barbados', 'maven' ), 'currency' => array( 'code' => 'BBD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'BS' ] = array( 'name' => __( 'Bahamas', 'maven' ), 'currency' => array( 'code' => 'BSD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'BH' ] = array( 'name' => __( 'Bahrain', 'maven' ), 'currency' => array( 'code' => 'BHD', 'symbol' => 'ب.د ' ), 'units' => 'metric' );
		$countries[ 'BY' ] = array( 'name' => __( 'Belarus', 'maven' ), 'currency' => array( 'code' => 'BYR', 'symbol' => 'BYR' ), 'units' => 'metric' );
		$countries[ 'BE' ] = array( 'name' => __( 'Belgium', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'BZ' ] = array( 'name' => __( 'Belize', 'maven' ), 'currency' => array( 'code' => 'BZD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'BJ' ] = array( 'name' => __( 'Benin', 'maven' ), 'currency' => array( 'code' => 'XOF', 'symbol' => 'CFA' ), 'units' => 'metric' );
		$countries[ 'BM' ] = array( 'name' => __( 'Bermuda', 'maven' ), 'currency' => array( 'code' => 'BMD', 'symbol' => 'BD$' ), 'units' => 'metric' );
		$countries[ 'BT' ] = array( 'name' => __( 'Bhutan', 'maven' ), 'currency' => array( 'code' => 'BTN', 'symbol' => 'Nu.' ), 'units' => 'metric' );
		$countries[ 'BO' ] = array( 'name' => __( 'Bolivia', 'maven' ), 'currency' => array( 'code' => 'BOB', 'symbol' => 'Bs' ), 'units' => 'metric' );
		$countries[ 'BA' ] = array( 'name' => __( 'Bosnia and Herzegovina', 'maven' ), 'currency' => array( 'code' => 'BAM', 'symbol' => 'KM ' ), 'units' => 'metric' );
		$countries[ 'BW' ] = array( 'name' => __( 'Botswana', 'maven' ), 'currency' => array( 'code' => 'BWP', 'symbol' => 'P' ), 'units' => 'metric' );
		$countries[ 'BR' ] = array( 'name' => __( 'Brazil', 'maven' ), 'currency' => array( 'code' => 'BRL', 'symbol' => 'R$' ), 'units' => 'metric' );
		$countries[ 'IO' ] = array( 'name' => __( 'British Indian Ocean Territory', 'maven' ), 'currency' => array( 'code' => 'GBP', 'symbol' => '£' ), 'units' => 'metric' );
		$countries[ 'VG' ] = array( 'name' => __( 'British Virgin Islands', 'maven' ), 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'BN' ] = array( 'name' => __( 'Brunei Darussalam', 'maven' ), 'currency' => array( 'code' => 'BND', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'BG' ] = array( 'name' => __( 'Bulgaria', 'maven' ), 'currency' => array( 'code' => 'BGN', 'symbol' => 'лв.' ), 'units' => 'metric' );
		$countries[ 'BF' ] = array( 'name' => __( 'Burkina Faso', 'maven' ), 'currency' => array( 'code' => 'XOF', 'symbol' => 'CFA' ), 'units' => 'metric' );
		$countries[ 'MM' ] = array( 'name' => __( 'Burma', 'maven' ), 'currency' => array( 'code' => 'MMK', 'symbol' => 'K' ), 'units' => 'metric' );
		$countries[ 'BI' ] = array( 'name' => __( 'Burundi', 'maven' ), 'currency' => array( 'code' => 'BIF', 'symbol' => 'FBu' ), 'units' => 'metric' );
		$countries[ 'KH' ] = array( 'name' => __( 'Cambodia', 'maven' ), 'currency' => array( 'code' => 'KHR', 'symbol' => '&#6107;' ), 'units' => 'metric' );
		$countries[ 'CM' ] = array( 'name' => __( 'Cameroon', 'maven' ), 'currency' => array( 'code' => 'XAF', 'symbol' => 'FCFA' ), 'units' => 'metric' );
		$countries[ 'CV' ] = array( 'name' => __( 'Cape Verde', 'maven' ), 'currency' => array( 'code' => 'CVE', 'symbol' => 'CV$' ), 'units' => 'metric' );
		$countries[ 'KY' ] = array( 'name' => __( 'Cayman Islands', 'maven' ), 'currency' => array( 'code' => 'KYD', 'symbol' => 'CI$' ), 'units' => 'metric' );
		$countries[ 'CF' ] = array( 'name' => __( 'Central African Republic', 'maven' ), 'currency' => array( 'code' => 'XAF', 'symbol' => 'FCFA' ), 'units' => 'metric' );
		$countries[ 'TD' ] = array( 'name' => __( 'Chad', 'maven' ), 'currency' => array( 'code' => 'XAF', 'symbol' => 'FCFA' ), 'units' => 'metric' );
		$countries[ 'CL' ] = array( 'name' => __( 'Chile', 'maven' ), 'currency' => array( 'code' => 'CLP', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'CN' ] = array( 'name' => __( 'China', 'maven' ), 'currency' => array( 'code' => 'CNY', 'symbol' => '¥' ), 'units' => 'metric' );
		$countries[ 'CX' ] = array( 'name' => __( 'Christmas Island', 'maven' ), 'currency' => array( 'code' => 'AUD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'CC' ] = array( 'name' => __( 'Cocos (Keeling) Islands', 'maven' ), 'currency' => array( 'code' => 'AUD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'CO' ] = array( 'name' => __( 'Colombia', 'maven' ), 'currency' => array( 'code' => 'COP', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'KM' ] = array( 'name' => __( 'Comoros', 'maven' ), 'currency' => array( 'code' => 'KMF', 'symbol' => 'FC' ), 'units' => 'metric' );
		$countries[ 'CG' ] = array( 'name' => __( 'Congo-Brazzaville', 'maven' ), 'currency' => array( 'code' => 'XAF', 'symbol' => 'FCFA' ), 'units' => 'metric' );
		$countries[ 'CD' ] = array( 'name' => __( 'Congo-Kinshasa', 'maven' ), 'currency' => array( 'code' => 'CDF', 'symbol' => 'FrCD' ), 'units' => 'metric' );
		$countries[ 'CK' ] = array( 'name' => __( 'Cook Islands', 'maven' ), 'currency' => array( 'code' => 'NZD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'CR' ] = array( 'name' => __( 'Costa Rica', 'maven' ), 'currency' => array( 'code' => 'CRC', 'symbol' => '₡' ), 'units' => 'metric' );
		$countries[ 'CI' ] = array( 'name' => __( "Côte d'Ivoire", 'maven' ), 'currency' => array( 'code' => 'XOF', 'symbol' => 'CFA' ), 'units' => 'metric' );
		$countries[ 'HR' ] = array( 'name' => __( 'Croatia', 'maven' ), 'currency' => array( 'code' => 'HRK', 'symbol' => ' kn' ), 'units' => 'metric' );
		$countries[ 'CY' ] = array( 'name' => __( 'Cyprus', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'CZ' ] = array( 'name' => __( 'Czech Republic', 'maven' ), 'currency' => array( 'code' => 'CZK', 'symbol' => 'Kč' ), 'units' => 'metric' );
		$countries[ 'DK' ] = array( 'name' => __( 'Denmark', 'maven' ), 'currency' => array( 'code' => 'DKK', 'symbol' => ' kr' ), 'units' => 'metric' );
		$countries[ 'DJ' ] = array( 'name' => __( 'Djibouti', 'maven' ), 'currency' => array( 'code' => 'DJF', 'symbol' => 'Fdj' ), 'units' => 'metric' );
		$countries[ 'DM' ] = array( 'name' => __( 'Dominica', 'maven' ), 'currency' => array( 'code' => 'XCD', 'symbol' => 'EC$' ), 'units' => 'metric' );
		$countries[ 'DO' ] = array( 'name' => __( 'Dominican Republic', 'maven' ), 'currency' => array( 'code' => 'DOP', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'TL' ] = array( 'name' => __( 'East Timor', 'maven' ), 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'EC' ] = array( 'name' => __( 'Ecuador', 'maven' ), 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'SV' ] = array( 'name' => __( 'El Salvador', 'maven' ), 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'EG' ] = array( 'name' => __( 'Egypt', 'maven' ), 'currency' => array( 'code' => 'EGP', 'symbol' => '£' ), 'units' => 'metric' );
		$countries[ 'GQ' ] = array( 'name' => __( 'Equatorial Guinea', 'maven' ), 'currency' => array( 'code' => 'XAF', 'symbol' => 'FCFA' ), 'units' => 'metric' );
		$countries[ 'ER' ] = array( 'name' => __( 'Eritrea', 'maven' ), 'currency' => array( 'code' => 'ERN', 'symbol' => 'Nfk,' ), 'units' => 'metric' );
		$countries[ 'EE' ] = array( 'name' => __( 'Estonia', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'ET' ] = array( 'name' => __( 'Ethiopia', 'maven' ), 'currency' => array( 'code' => 'ETB', 'symbol' => 'Br' ), 'units' => 'metric' );
		$countries[ 'FK' ] = array( 'name' => __( 'Falkland Islands', 'maven' ), 'currency' => array( 'code' => 'FKP', 'symbol' => 'FK£' ), 'units' => 'metric' );
		$countries[ 'FO' ] = array( 'name' => __( 'Faroe Islands', 'maven' ), 'currency' => array( 'code' => 'DKK', 'symbol' => 'kr' ), 'units' => 'metric' );
		$countries[ 'FM' ] = array( 'name' => __( 'Federated States of Micronesia', 'maven' ), 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'FJ' ] = array( 'name' => __( 'Fiji', 'maven' ), 'currency' => array( 'code' => 'FJD', 'symbol' => 'FJ$' ), 'units' => 'metric' );
		$countries[ 'FI' ] = array( 'name' => __( 'Finland', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'FR' ] = array( 'name' => __( 'France', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'GF' ] = array( 'name' => __( 'French Guiana', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'PF' ] = array( 'name' => __( 'French Polynesia', 'maven' ), 'currency' => array( 'code' => 'XPF', 'symbol' => 'F' ), 'units' => 'metric' );
		$countries[ 'TF' ] = array( 'name' => __( 'French Southern Lands', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'GA' ] = array( 'name' => __( 'Gabon', 'maven' ), 'currency' => array( 'code' => 'XAF', 'symbol' => 'FCFA' ), 'units' => 'metric' );
		$countries[ 'GM' ] = array( 'name' => __( 'Gambia', 'maven' ), 'currency' => array( 'code' => 'GMD', 'symbol' => 'GMD' ), 'units' => 'metric' );
		$countries[ 'GE' ] = array( 'name' => __( 'Georgia', 'maven' ), 'currency' => array( 'code' => 'GEL', 'symbol' => 'GEL' ), 'units' => 'metric' );
		$countries[ 'DE' ] = array( 'name' => __( 'Germany', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'GH' ] = array( 'name' => __( 'Ghana', 'maven' ), 'currency' => array( 'code' => 'GHS', 'symbol' => '₵' ), 'units' => 'metric' );
		$countries[ 'GI' ] = array( 'name' => __( 'Gibraltar', 'maven' ), 'currency' => array( 'code' => 'GBP', 'symbol' => '£' ), 'units' => 'metric' );
		$countries[ 'GR' ] = array( 'name' => __( 'Greece', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'GL' ] = array( 'name' => __( 'Greenland', 'maven' ), 'currency' => array( 'code' => 'DKK', 'symbol' => 'kr' ), 'units' => 'metric' );
		$countries[ 'GD' ] = array( 'name' => __( 'Grenada', 'maven' ), 'currency' => array( 'code' => 'XCD', 'symbol' => 'EC$' ), 'units' => 'metric' );
		$countries[ 'GP' ] = array( 'name' => __( 'Guadeloupe', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'GU' ] = array( 'name' => __( 'Guam', 'maven' ), 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'GT' ] = array( 'name' => __( 'Guatemala', 'maven' ), 'currency' => array( 'code' => 'GTQ', 'symbol' => 'Q' ), 'units' => 'metric' );
		//$countries[ 'GG' ] = array( 'name' => __( 'Guernsey', 'maven' ), 'currency' => array( 'code' => 'GBP', 'symbol' => '£' ), 'units' => 'metric' );
		$countries[ 'GN' ] = array( 'name' => __( 'Guinea', 'maven' ), 'currency' => array( 'code' => 'GNF', 'symbol' => 'FG' ), 'units' => 'metric' );
		$countries[ 'GW' ] = array( 'name' => __( 'Guinea-Bissau', 'maven' ), 'currency' => array( 'code' => 'XOF', 'symbol' => 'CFA' ), 'units' => 'metric' );
		$countries[ 'GY' ] = array( 'name' => __( 'Guyana', 'maven' ), 'currency' => array( 'code' => 'GYD', 'symbol' => 'G$' ), 'units' => 'metric' );
		$countries[ 'HT' ] = array( 'name' => __( 'Haiti', 'maven' ), 'currency' => array( 'code' => 'HTG', 'symbol' => 'HTG' ), 'units' => 'metric' );
		$countries[ 'HM' ] = array( 'name' => __( 'Heard and McDonald Islands', 'maven' ), 'currency' => array( 'code' => 'AUD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'HN' ] = array( 'name' => __( 'Honduras', 'maven' ), 'currency' => array( 'code' => 'HNL', 'symbol' => 'L' ), 'units' => 'metric' );
		$countries[ 'HK' ] = array( 'name' => __( 'Hong Kong', 'maven' ), 'currency' => array( 'code' => 'HKD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'HU' ] = array( 'name' => __( 'Hungary', 'maven' ), 'currency' => array( 'code' => 'HUF', 'symbol' => 'Ft' ), 'units' => 'metric' );
		$countries[ 'IS' ] = array( 'name' => __( 'Iceland', 'maven' ), 'currency' => array( 'code' => 'ISK', 'symbol' => 'kr.' ), 'units' => 'metric' );
		$countries[ 'IN' ] = array( 'name' => __( 'India', 'maven' ), 'currency' => array( 'code' => 'INR', 'symbol' => '₨' ), 'units' => 'metric' );
		$countries[ 'ID' ] = array( 'name' => __( 'Indonesia', 'maven' ), 'currency' => array( 'code' => 'IDR', 'symbol' => 'Rp' ), 'units' => 'metric' );
		$countries[ 'IQ' ] = array( 'name' => __( 'Iraq', 'maven' ), 'currency' => array( 'code' => 'IQD', 'symbol' => 'ع.د' ), 'units' => 'metric' );
		$countries[ 'IE' ] = array( 'name' => __( 'Ireland', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		//$countries[ 'IM' ] = array( 'name' => __( 'Isle of Man', 'maven' ), 'currency' => array( 'code' => 'GBP', 'symbol' => '£' ), 'units' => 'metric' );
		$countries[ 'IL' ] = array( 'name' => __( 'Israel', 'maven' ), 'currency' => array( 'code' => 'ILS', 'symbol' => '₪' ), 'units' => 'metric' );
		$countries[ 'IT' ] = array( 'name' => __( 'Italy', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'JM' ] = array( 'name' => __( 'Jamaica', 'maven' ), 'currency' => array( 'code' => 'JMD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'JP' ] = array( 'name' => __( 'Japan', 'maven' ), 'currency' => array( 'code' => 'JPY', 'symbol' => '¥' ), 'units' => 'metric' );
		//$countries[ 'JE' ] = array( 'name' => __( 'Jersey', 'maven' ), 'currency' => array( 'code' => 'GBP', 'symbol' => '£' ), 'units' => 'metric' );
		$countries[ 'JO' ] = array( 'name' => __( 'Jordan', 'maven' ), 'currency' => array( 'code' => 'JOD', 'symbol' => 'JD' ), 'units' => 'metric' );
		$countries[ 'KZ' ] = array( 'name' => __( 'Kazakhstan', 'maven' ), 'currency' => array( 'code' => 'KZT', 'symbol' => '〒' ), 'units' => 'metric' );
		$countries[ 'KE' ] = array( 'name' => __( 'Kenya', 'maven' ), 'currency' => array( 'code' => 'KES', 'symbol' => 'Ksh' ), 'units' => 'metric' );
		$countries[ 'KI' ] = array( 'name' => __( 'Kiribati', 'maven' ), 'currency' => array( 'code' => 'AUD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'KW' ] = array( 'name' => __( 'Kuwait', 'maven' ), 'currency' => array( 'code' => 'KWD', 'symbol' => 'د.ك' ), 'units' => 'metric' );
		$countries[ 'KG' ] = array( 'name' => __( 'Kyrgyzstan', 'maven' ), 'currency' => array( 'code' => 'KGS', 'symbol' => 'som' ), 'units' => 'metric' );
		$countries[ 'LA' ] = array( 'name' => __( 'Laos', 'maven' ), 'currency' => array( 'code' => 'LAK', 'symbol' => '₭' ), 'units' => 'metric' );
		$countries[ 'LV' ] = array( 'name' => __( 'Latvia', 'maven' ), 'currency' => array( 'code' => 'LVL', 'symbol' => 'Ls' ), 'units' => 'metric' );
		$countries[ 'LB' ] = array( 'name' => __( 'Lebanon', 'maven' ), 'currency' => array( 'code' => 'LBP', 'symbol' => 'ل.ل' ), 'units' => 'metric' );
		$countries[ 'LS' ] = array( 'name' => __( 'Lesotho', 'maven' ), 'currency' => array( 'code' => 'LSL', 'symbol' => 'M' ), 'units' => 'metric' );
		$countries[ 'LR' ] = array( 'name' => __( 'Liberia', 'maven' ), 'currency' => array( 'code' => 'LRD', 'symbol' => 'LD$' ), 'units' => 'metric' );
		$countries[ 'LY' ] = array( 'name' => __( 'Libya', 'maven' ), 'currency' => array( 'code' => 'LYD', 'symbol' => 'ل.د' ), 'units' => 'metric' );
		$countries[ 'LI' ] = array( 'name' => __( 'Liechtenstein', 'maven' ), 'currency' => array( 'code' => 'CHF', 'symbol' => "CHF'" ), 'units' => 'metric' );
		$countries[ 'LT' ] = array( 'name' => __( 'Lithuania', 'maven' ), 'currency' => array( 'code' => 'LTL', 'symbol' => 'Lt' ), 'units' => 'metric' );
		$countries[ 'LU' ] = array( 'name' => __( 'Luxembourg', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'MO' ] = array( 'name' => __( 'Macau', 'maven' ), 'currency' => array( 'code' => 'MOP', 'symbol' => 'MOP$' ), 'units' => 'metric' );
		$countries[ 'MK' ] = array( 'name' => __( 'Macedonia', 'maven' ), 'currency' => array( 'code' => 'MKD', 'symbol' => 'MKD' ), 'units' => 'metric' );
		$countries[ 'MG' ] = array( 'name' => __( 'Madagascar', 'maven' ), 'currency' => array( 'code' => 'MGA', 'symbol' => 'MGA' ), 'units' => 'metric' );
		$countries[ 'MW' ] = array( 'name' => __( 'Malawi', 'maven' ), 'currency' => array( 'code' => 'MWK', 'symbol' => 'MK' ), 'units' => 'metric' );
		$countries[ 'MY' ] = array( 'name' => __( 'Malaysia', 'maven' ), 'currency' => array( 'code' => 'MYR', 'symbol' => 'RM' ), 'units' => 'metric' );
		$countries[ 'MV' ] = array( 'name' => __( 'Maldives', 'maven' ), 'currency' => array( 'code' => 'MVR', 'symbol' => 'Rf' ), 'units' => 'metric' );
		$countries[ 'ML' ] = array( 'name' => __( 'Mali', 'maven' ), 'currency' => array( 'code' => 'XOF', 'symbol' => 'CFA' ), 'units' => 'metric' );
		$countries[ 'MT' ] = array( 'name' => __( 'Malta', 'maven' ), 'currency' => array( 'code' => 'MTL', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'MH' ] = array( 'name' => __( 'Marshall Islands', 'maven' ), 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'MQ' ] = array( 'name' => __( 'Martinique', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'MR' ] = array( 'name' => __( 'Mauritania', 'maven' ), 'currency' => array( 'code' => 'MRO', 'symbol' => 'UM' ), 'units' => 'metric' );
		$countries[ 'MU' ] = array( 'name' => __( 'Mauritius', 'maven' ), 'currency' => array( 'code' => 'MUR', 'symbol' => 'MU₨' ), 'units' => 'metric' );
		$countries[ 'YT' ] = array( 'name' => __( 'Mayotte', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'MX' ] = array( 'name' => __( 'Mexico', 'maven' ), 'currency' => array( 'code' => 'MXN', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'MD' ] = array( 'name' => __( 'Moldova', 'maven' ), 'currency' => array( 'code' => 'MDL', 'symbol' => 'MDL' ), 'units' => 'metric' );
		$countries[ 'MC' ] = array( 'name' => __( 'Monaco', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'MN' ] = array( 'name' => __( 'Mongolia', 'maven' ), 'currency' => array( 'code' => 'MNT', 'symbol' => '₮' ), 'units' => 'metric' );
		$countries[ 'ME' ] = array( 'name' => __( 'Montenegro', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'MS' ] = array( 'name' => __( 'Montserrat', 'maven' ), 'currency' => array( 'code' => 'XCD', 'symbol' => 'EC$' ), 'units' => 'metric' );
		$countries[ 'MA' ] = array( 'name' => __( 'Morocco', 'maven' ), 'currency' => array( 'code' => 'MAD', 'symbol' => 'د.م.' ), 'units' => 'metric' );
		$countries[ 'MZ' ] = array( 'name' => __( 'Mozambique', 'maven' ), 'currency' => array( 'code' => 'MZN', 'symbol' => 'MTn' ), 'units' => 'metric' );
		$countries[ 'NA' ] = array( 'name' => __( 'Namibia', 'maven' ), 'currency' => array( 'code' => 'NAD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'NR' ] = array( 'name' => __( 'Nauru', 'maven' ), 'currency' => array( 'code' => 'AUD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'NP' ] = array( 'name' => __( 'Nepal', 'maven' ), 'currency' => array( 'code' => 'NPR', 'symbol' => 'रू.' ), 'units' => 'metric' );
		$countries[ 'NL' ] = array( 'name' => __( 'Netherlands', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'NC' ] = array( 'name' => __( 'New Caledonia', 'maven' ), 'currency' => array( 'code' => 'XPF', 'symbol' => 'F' ), 'units' => 'metric' );
		$countries[ 'NZ' ] = array( 'name' => __( 'New Zealand', 'maven' ), 'currency' => array( 'code' => 'NZD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'NI' ] = array( 'name' => __( 'Nicaragua', 'maven' ), 'currency' => array( 'code' => 'NIO', 'symbol' => 'C$' ), 'units' => 'metric' );
		$countries[ 'NE' ] = array( 'name' => __( 'Niger', 'maven' ), 'currency' => array( 'code' => 'XOF', 'symbol' => 'CFA' ), 'units' => 'metric' );
		$countries[ 'NG' ] = array( 'name' => __( 'Nigeria', 'maven' ), 'currency' => array( 'code' => 'NGN', 'symbol' => '₦' ), 'units' => 'metric' );
		$countries[ 'NU' ] = array( 'name' => __( 'Niue', 'maven' ), 'currency' => array( 'code' => 'NZD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'NF' ] = array( 'name' => __( 'Norfolk Island', 'maven' ), 'currency' => array( 'code' => 'AUD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'MP' ] = array( 'name' => __( 'Northern Mariana Islands', 'maven' ), 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'NO' ] = array( 'name' => __( 'Norway', 'maven' ), 'currency' => array( 'code' => 'NOK', 'symbol' => 'kr' ), 'units' => 'metric' );
		$countries[ 'OM' ] = array( 'name' => __( 'Oman', 'maven' ), 'currency' => array( 'code' => 'OMR', 'symbol' => 'ر.ع' ), 'units' => 'metric' );
		$countries[ 'PK' ] = array( 'name' => __( 'Pakistan', 'maven' ), 'currency' => array( 'code' => 'PKR', 'symbol' => '₨' ), 'units' => 'metric' );
		$countries[ 'PW' ] = array( 'name' => __( 'Palau', 'maven' ), 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'PA' ] = array( 'name' => __( 'Panama', 'maven' ), 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'PG' ] = array( 'name' => __( 'Papua New Guinea', 'maven' ), 'currency' => array( 'code' => 'PGK', 'symbol' => 'K' ), 'units' => 'metric' );
		$countries[ 'PY' ] = array( 'name' => __( 'Paraguay', 'maven' ), 'currency' => array( 'code' => 'PYG', 'symbol' => '₲' ), 'units' => 'metric' );
		$countries[ 'PE' ] = array( 'name' => __( 'Peru', 'maven' ), 'currency' => array( 'code' => 'PEN', 'symbol' => 'S/.' ), 'units' => 'metric' );
		$countries[ 'PH' ] = array( 'name' => __( 'Philippines', 'maven' ), 'currency' => array( 'code' => 'PHP', 'symbol' => 'Php' ), 'units' => 'metric' );
		$countries[ 'PN' ] = array( 'name' => __( 'Pitcairn Islands', 'maven' ), 'currency' => array( 'code' => 'NZD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'PL' ] = array( 'name' => __( 'Poland', 'maven' ), 'currency' => array( 'code' => 'PLN', 'symbol' => 'zł' ), 'units' => 'metric' );
		$countries[ 'PT' ] = array( 'name' => __( 'Portugal', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'PR' ] = array( 'name' => __( 'Puerto Rico', 'maven' ), 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'imperial' );
		$countries[ 'QA' ] = array( 'name' => __( 'Qatar', 'maven' ), 'currency' => array( 'code' => 'QAR', 'symbol' => 'ر.ق' ), 'units' => 'metric' );
		$countries[ 'RE' ] = array( 'name' => __( 'Réunion', 'maven' ), 'currency' => array( 'code' => '', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'RO' ] = array( 'name' => __( 'Romania', 'maven' ), 'currency' => array( 'code' => 'ROL', 'symbol' => 'lei' ), 'units' => 'metric' );
		$countries[ 'RU' ] = array( 'name' => __( 'Russia', 'maven' ), 'currency' => array( 'code' => 'RUB', 'symbol' => 'руб' ), 'units' => 'metric' );
		$countries[ 'RW' ] = array( 'name' => __( 'Rwanda', 'maven' ), 'currency' => array( 'code' => 'RWF', 'symbol' => 'RF' ), 'units' => 'metric' );
		//$countries[ 'BL' ] = array( 'name' => __( 'Saint Barthélemy', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'SH' ] = array( 'name' => __( 'Saint Helena', 'maven' ), 'currency' => array( 'code' => 'SHP', 'symbol' => '£' ), 'units' => 'metric' );
		$countries[ 'KN' ] = array( 'name' => __( 'Saint Kitts and Nevis', 'maven' ), 'currency' => array( 'code' => 'XCD', 'symbol' => 'EC$' ), 'units' => 'metric' );
		$countries[ 'LC' ] = array( 'name' => __( 'Saint Lucia', 'maven' ), 'currency' => array( 'code' => 'XCD', 'symbol' => 'EC$' ), 'units' => 'metric' );
		//$countries[ 'MF' ] = array( 'name' => __( 'Saint Martin', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'PM' ] = array( 'name' => __( 'Saint Pierre and Miquelon', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'VC' ] = array( 'name' => __( 'Saint Vincent', 'maven' ), 'currency' => array( 'code' => 'XCD', 'symbol' => 'EC$' ), 'units' => 'metric' );
		$countries[ 'WS' ] = array( 'name' => __( 'Samoa', 'maven' ), 'currency' => array( 'code' => 'WST', 'symbol' => 'WS$' ), 'units' => 'metric' );
		$countries[ 'SM' ] = array( 'name' => __( 'San Marino', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'ST' ] = array( 'name' => __( 'São Tomé and Príncipe', 'maven' ), 'currency' => array( 'code' => 'STD', 'symbol' => 'Db ' ), 'units' => 'metric' );
		$countries[ 'SA' ] = array( 'name' => __( 'Saudi Arabia', 'maven' ), 'currency' => array( 'code' => 'SAR', 'symbol' => 'ر.س' ), 'units' => 'metric' );
		$countries[ 'SN' ] = array( 'name' => __( 'Senegal', 'maven' ), 'currency' => array( 'code' => 'XOF', 'symbol' => 'CFA' ), 'units' => 'metric' );
		$countries[ 'RS' ] = array( 'name' => __( 'Serbia', 'maven' ), 'currency' => array( 'code' => 'RSD', 'symbol' => 'din.' ), 'units' => 'metric' );
		$countries[ 'SC' ] = array( 'name' => __( 'Seychelles', 'maven' ), 'currency' => array( 'code' => 'SCR', 'symbol' => '₨' ), 'units' => 'metric' );
		$countries[ 'SL' ] = array( 'name' => __( 'Sierra Leone', 'maven' ), 'currency' => array( 'code' => 'SLL', 'symbol' => 'Le' ), 'units' => 'metric' );
		$countries[ 'SG' ] = array( 'name' => __( 'Singapore', 'maven' ), 'currency' => array( 'code' => 'SGD', 'symbol' => '$' ), 'units' => 'metric' );
		//$countries[ 'SX' ] = array( 'name' => __( 'Sint Maarten', 'maven' ), 'currency' => array( 'code' => 'ANG', 'symbol' => 'ƒ' ), 'units' => 'metric' );
		$countries[ 'SK' ] = array( 'name' => __( 'Slovakia', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'SI' ] = array( 'name' => __( 'Slovenia', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'SB' ] = array( 'name' => __( 'Solomon Islands', 'maven' ), 'currency' => array( 'code' => 'SBD', 'symbol' => 'SI$' ), 'units' => 'metric' );
		$countries[ 'SO' ] = array( 'name' => __( 'Somalia', 'maven' ), 'currency' => array( 'code' => 'SOS', 'symbol' => 'Ssh' ), 'units' => 'metric' );
		$countries[ 'ZA' ] = array( 'name' => __( 'South Africa', 'maven' ), 'currency' => array( 'code' => 'ZAR', 'symbol' => 'R' ), 'units' => 'metric' );
		$countries[ 'GS' ] = array( 'name' => __( 'South Georgia', 'maven' ), 'currency' => array( 'code' => 'GBP', 'symbol' => '£' ), 'units' => 'metric' );
		$countries[ 'KR' ] = array( 'name' => __( 'South Korea', 'maven' ), 'currency' => array( 'code' => 'KRW', 'symbol' => '₩' ), 'units' => 'metric' );
		$countries[ 'ES' ] = array( 'name' => __( 'Spain', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'LK' ] = array( 'name' => __( 'Sri Lanka', 'maven' ), 'currency' => array( 'code' => 'LKR', 'symbol' => 'SL₨' ), 'units' => 'metric' );
		$countries[ 'SD' ] = array( 'name' => __( 'Sudan', 'maven' ), 'currency' => array( 'code' => 'SDG', 'symbol' => 'SDG ' ), 'units' => 'metric' );
		$countries[ 'SR' ] = array( 'name' => __( 'Suriname', 'maven' ), 'currency' => array( 'code' => 'SRD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'SJ' ] = array( 'name' => __( 'Svalbard and Jan Mayen', 'maven' ), 'currency' => array( 'code' => 'NOK', 'symbol' => 'kr' ), 'units' => 'metric' );
		$countries[ 'SE' ] = array( 'name' => __( 'Sweden', 'maven' ), 'currency' => array( 'code' => 'SEK', 'symbol' => 'kr' ), 'units' => 'metric' );
		$countries[ 'SZ' ] = array( 'name' => __( 'Swaziland', 'maven' ), 'currency' => array( 'code' => 'SZL', 'symbol' => 'E' ), 'units' => 'metric' );
		$countries[ 'CH' ] = array( 'name' => __( 'Switzerland', 'maven' ), 'currency' => array( 'code' => 'CHF', 'symbol' => "CHF" ), 'units' => 'metric' );
		$countries[ 'SY' ] = array( 'name' => __( 'Syria', 'maven' ), 'currency' => array( 'code' => 'SYP', 'symbol' => '£S' ), 'units' => 'metric' );
		$countries[ 'TW' ] = array( 'name' => __( 'Taiwan', 'maven' ), 'currency' => array( 'code' => 'TWD', 'symbol' => 'NT$' ), 'units' => 'metric' );
		$countries[ 'TJ' ] = array( 'name' => __( 'Tajikistan', 'maven' ), 'currency' => array( 'code' => 'TJS', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'TZ' ] = array( 'name' => __( 'Tanzania', 'maven' ), 'currency' => array( 'code' => 'TZS', 'symbol' => 'TSh' ), 'units' => 'metric' );
		$countries[ 'TH' ] = array( 'name' => __( 'Thailand', 'maven' ), 'currency' => array( 'code' => 'THB', 'symbol' => '฿' ), 'units' => 'metric' );
		$countries[ 'TG' ] = array( 'name' => __( 'Togo', 'maven' ), 'currency' => array( 'code' => 'XOF', 'symbol' => 'CFA' ), 'units' => 'metric' );
		$countries[ 'TK' ] = array( 'name' => __( 'Tokelau', 'maven' ), 'currency' => array( 'code' => 'NZD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'TO' ] = array( 'name' => __( 'Tonga', 'maven' ), 'currency' => array( 'code' => 'TOP', 'symbol' => 'T$' ), 'units' => 'metric' );
		$countries[ 'TT' ] = array( 'name' => __( 'Trinidad and Tobago', 'maven' ), 'currency' => array( 'code' => 'TTD', 'symbol' => 'TT$' ), 'units' => 'metric' );
		$countries[ 'TN' ] = array( 'name' => __( 'Tunisia', 'maven' ), 'currency' => array( 'code' => 'TND', 'symbol' => 'د.ت' ), 'units' => 'metric' );
		$countries[ 'TR' ] = array( 'name' => __( 'Turkey', 'maven' ), 'currency' => array( 'code' => 'TRL', 'symbol' => ' TL' ), 'units' => 'metric' );
		$countries[ 'TM' ] = array( 'name' => __( 'Turkmenistan', 'maven' ), 'currency' => array( 'code' => 'TMT', 'symbol' => 'm' ), 'units' => 'metric' );
		$countries[ 'TC' ] = array( 'name' => __( 'Turks and Caicos Islands', 'maven' ), 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'TV' ] = array( 'name' => __( 'Tuvalu', 'maven' ), 'currency' => array( 'code' => 'AUD', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'UG' ] = array( 'name' => __( 'Uganda', 'maven' ), 'currency' => array( 'code' => 'UGX', 'symbol' => 'USh' ), 'units' => 'metric' );
		$countries[ 'UA' ] = array( 'name' => __( 'Ukraine', 'maven' ), 'currency' => array( 'code' => 'UAH', 'symbol' => '₴' ), 'units' => 'metric' );
		$countries[ 'AE' ] = array( 'name' => __( 'United Arab Emirates', 'maven' ), 'currency' => array( 'code' => 'AED', 'symbol' => 'Dhs.' ), 'units' => 'metric' );
		$countries[ 'UY' ] = array( 'name' => __( 'Uruguay', 'maven' ), 'currency' => array( 'code' => 'UYP', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'UZ' ] = array( 'name' => __( 'Uzbekistan', 'maven' ), 'currency' => array( 'code' => 'UZS', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'VU' ] = array( 'name' => __( 'Vanuatu', 'maven' ), 'currency' => array( 'code' => 'VUV', 'symbol' => '$' ), 'units' => 'metric' );
		$countries[ 'VA' ] = array( 'name' => __( 'Vatican City', 'maven' ), 'currency' => array( 'code' => 'EUR', 'symbol' => '€' ), 'units' => 'metric' );
		$countries[ 'VN' ] = array( 'name' => __( 'Vietnam', 'maven' ), 'currency' => array( 'code' => 'VND', 'symbol' => '₫' ), 'units' => 'metric' );
		$countries[ 'VE' ] = array( 'name' => __( 'Venezuela', 'maven' ), 'currency' => array( 'code' => 'VUB', 'symbol' => 'Bs.' ), 'units' => 'metric' );
		$countries[ 'WF' ] = array( 'name' => __( 'Wallis and Futuna', 'maven' ), 'currency' => array( 'code' => 'XPF', 'symbol' => 'F' ), 'units' => 'metric' );
		$countries[ 'EH' ] = array( 'name' => __( 'Western Sahara', 'maven' ), 'currency' => array( 'code' => 'MAD', 'symbol' => 'درهم' ), 'units' => 'metric' );
		$countries[ 'YE' ] = array( 'name' => __( 'Yemen', 'maven' ), 'currency' => array( 'code' => 'YER', 'symbol' => '.ر.ي' ), 'units' => 'metric' );
		$countries[ 'ZM' ] = array( 'name' => __( 'Zambia', 'maven' ), 'currency' => array( 'code' => 'ZMK', 'symbol' => 'ZK' ), 'units' => 'metric' );
		$countries[ 'ZW' ] = array( 'name' => __( 'Zimbabwe', 'maven' ), 'currency' => array( 'code' => 'USD', 'symbol' => '$' ), 'units' => 'metric' );

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
