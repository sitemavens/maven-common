<?php
namespace Maven\Front;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class Thing extends \Maven\Core\DomainObject {
	
	private $quantity;
	private $variations;
	private $attributes;
	private $price;
	private $name;
	
	private $pluginKey;
	
	/**
	 * 
	 * @param string $pluginKey
	 * @param int $id
	 */
	public function __construct( $pluginKey, $id = false ) {
		parent::__construct( $id );
		
		$this->pluginKey = $pluginKey;
	
	}
	
	public function getQuantity() {
		return $this->quantity;
	}

	public function setQuantity( $quantity ) {
		$this->quantity = $quantity;
	}
	
	/**
	 * Variations
	 * @return \Maven\Front\ThingVariation[]
	 */
	public function getVariations() {
		return $this->variations;
	}

	/**
	 * Set variations
	 * @param \Maven\Front\ThingVariation[] $variations
	 */
	public function setVariations( $variations ) {
		$this->variations = $variations;
		
		if ( ! $this->hasVariations() ) {
			return;
		}

		foreach( $this->variations as $variation ){
			$this->variations[$variation->getId() ] = $variation;
		}
	}
	
	public function hasAttributes(){
		return ! \Maven\Core\Utils::isEmpty( $this->attributes) ;
	}
	
	public function addAttribute(\Maven\Front\ThingAttribute $attribute){
		$this->attributes[$attribute->getId() ] = $attribute;
		
	}
	
	public function addVariation( \Maven\Front\ThingVariation $variation ){
		$this->variations[$variation->getId() ] = $variation;
	}

	/**
	 * Check if the thing has variations
	 * @return boolean
	 */
	public function hasVariations(){
		return !\Maven\Core\Utils::isEmpty( $this->variations );
	}

	public function getPluginKey () {
		return $this->pluginKey;
	}

	public function setPluginKey ( $pluginKey ) {
		$this->pluginKey = $pluginKey;
	}

	public function getPrice () {
		return $this->price;
	}

	public function setPrice ( $price ) {
		$this->price = $price;
	}
	
	public function getName () {
		return $this->name;
	}

	public function setName ( $name ) {
		$this->name = $name;
	}

	/**
	 * Return attributes
	 * @return \Maven\Front\ThingAttribute[]
	 */
	public function getAttributes () {
		return $this->attributes;
	}



}
