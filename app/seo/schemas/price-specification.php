<?php

namespace Maven\Seo\Schemas;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class PriceSpecification extends Thing{

	/**
	 * Number The highest price if the price is a range.
	 * @var string 
	 */
	private $maxPrice;
	
	/**
	 * Number The lowest price if the price is a range.
	 * @var string 
	 */
	private $minPrice;
	
	/**
	 * Number or Text The offer price of a product, or of a price component when attached to PriceSpecification and its subtypes.
	 * @var string 
	 */
	private $price;
	
	
	public function getMaxPrice() {
		return $this->maxPrice;
	}

	public function setMaxPrice( $maxPrice ) {
		$this->maxPrice = $maxPrice;
	}

	public function getMinPrice() {
		return $this->minPrice;
	}

	public function setMinPrice( $minPrice ) {
		$this->minPrice = $minPrice;
	}

	public function getPrice() {
		return $this->price;
	}

	public function setPrice( $price ) {
		$this->price = $price;
	}

	public function __construct() {
		parent::__construct();
	}
	
	
}