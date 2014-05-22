<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class VariationOptionPriceOperator{
	
	const Fixed = 'fixed';
	const Add = 'add';
	const Substract = 'substract';
	const NoChange = 'no-change';
	
	public static function isValid( $operator ){
		
		$operators = self::getOperators();
		foreach($operators as $key=>$value ) 
			if ( $key == $operator )
				return true;
		 
		return false;
	}
	
	public static function getOperators(){
		return array( self::Fixed=>"Fixed", self::Add => "Add", self::Substract => "Substract", self::NoChange => "No Change");
	}
	
}