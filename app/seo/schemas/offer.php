<?php

namespace Maven\Seo\Schemas;


// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;



class Offer extends Thing{
	
	/**
	 *
	 * @var \Maven\Seo\Schemas\PriceSpecification 
	 */
	private $priceSpecification;
	
	public function __construct() {
		
		$this->priceSpecification = new \Maven\Seo\Schemas\PriceSpecification ();
		
	}

	public function getPriceSpecification() {
		return $this->priceSpecification;
	}

	public function setPriceSpecification( \Maven\Seo\Schemas\PriceSpecification $priceSpecification ) {
		$this->priceSpecification = $priceSpecification;
	}

	
	
}