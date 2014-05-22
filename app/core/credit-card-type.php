<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class CreditCardType{
	
	const VISA				= "visa";
	const MasterCard		= "mc";
	const AmericanExpress	= "amex";
	
	public static function getTypes(){
		return array(
			"visa" => "Visa",
			"mc" => "Master Card",
			"amex" => "American Express"
		);
	}
}