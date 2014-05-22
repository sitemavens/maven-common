<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class OrderItem extends \Maven\Core\DomainObject {

	private $name; 
	private $quantity;
	private $orderId;
	private $price;
	private $thingVariationId;
	private $sku;
	private $thingId;
	private $attributes;
	
	/**
	 * It will save the order item status
	 * @var \Maven\Core\Message\Message
	 */
	private $status;
	
	/**
	 *
	 * @var string 
	 */
    private $pluginKey;
	
	/**
	 * Instantiate order item
	 * @param string $groupKey
	 * @param int $id
	 */
	public function __construct( $pluginKey='' ) {
		
		parent::__construct( false );
        
		$this->pluginKey = $pluginKey;
		
		$rules = array(
			
			'name'		 => \Maven\Core\SanitizationRule::Text,
			'quantity'   => \Maven\Core\SanitizationRule::Integer,
			'orderId'	 => \Maven\Core\SanitizationRule::Integer,
			'price'		 => \Maven\Core\SanitizationRule::Float,
			'sku'		 => \Maven\Core\SanitizationRule::Text,
			'pluginKey'		=> \Maven\Core\SanitizationRule::Key,
			'variationId'	=> \Maven\Core\SanitizationRule::Integer
		);
		
		$this->setSanitizationRules( $rules );
		
		$this->status = \Maven\Core\Message\MessageManager::createEmptyMessage();
		
	}
	
	public function getSku() {
		return $this->sku;
	}

	public function setSku( $sku ) {
		$this->sku = $sku;
	}

		public function getPrice() {
		return $this->price;
	}

	public function setPrice( $price ) {
		$this->price = $price;
	}


	public function getOrderId() {
		return $this->orderId;
	}

	public function setOrderId( $orderId ) {
		$this->orderId = $orderId;
	}

		public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}

	public function getQuantity() {
		return $this->quantity;
	}

	public function setQuantity( $quantity ) {
		$this->quantity = (float)$quantity;
	}

	public function getTotal(){
		return $this->quantity * $this->price;
	}

	public function getHash(){
		
	}
    
	/**
	 * Return the item Plugin Key
	 * @return string
	 */
	public function getPluginKey () {
		return $this->pluginKey;
	}

	/**
	 * Set the Item Plugin Key
	 * @param string $pluginKey
	 */
	public function setPluginKey ( $pluginKey ) {
		$this->pluginKey = $pluginKey;
	}

	/**
	 * Return the Item identifier. It will be unique among the groups
	 * @return string
	 */
	public function getIdentifier(){
		return $this->getPluginKey()."-".$this->getThingId()."-".$this->getThingVariationId();
	}

	public function getThingVariationId () {
		return $this->thingVariationId;
	}

	public function setThingVariationId ( $thingVariationId ) {
		$this->thingVariationId = $thingVariationId;
	}
	
	public function getThingId () {
		return $this->thingId;
	}

	public function setThingId ( $thingId ) {
		$this->thingId = $thingId;
	}

	/**
	 * Return the order item status. It's useful when you want to send information from one plugin to another.
	 * @return \Maven\Core\Message\Message
	 */
	public function getStatus () {
		return $this->status;
	}

	/**
	 * Save status
	 * @param \Maven\Core\Message\Message $status
	 */
	public function setStatus ( \Maven\Core\Message\Message $status ) {
		$this->status = $status;
	}
	
	/**
	 * 
	 * @return \Maven\Core\Domain\OrderItemAttribute[]
	 */
	public function getAttributes () {
		return $this->attributes;
	}

	public function setAttributes ( $attributes ) {
		$this->attributes = $attributes;
	}
	
	/**
	 * 
	 * @param int $id
	 * @param string $name
	 * @param float $price
	 */
	public function addAttribute( $id, $name, $price ){
		
		$attribute = new OrderItemAttribute();
		$attribute->setName( $name );
		$attribute->setId( $id );
		$attribute->setPrice( $price );
		
		$this->attributes[] = $attribute;
	}


	public function sanitize(){
		parent::sanitize();
		
		if ( \Maven\Core\Utils::isEmpty( $this->attributes ) ) {
			return;
		}

		foreach( $this->attributes as $attribute ){
			$attribute->sanitize();
		}
	}
	
	public function hasAttributes(){
		return !\Maven\Core\Utils::isEmpty( $this->attributes );
	}
	
	public function getAttributesNames(){
		
		$names = "";
		if ( $this->hasAttributes() ){
			foreach( $this->attributes as $attribute ){
				if  (! $names){
					$names.=$attribute->getName();
				}
				else{
					$names.= ", ".$attribute->getName();
				}
					 
			}
		}
		
		return $names;
	}
	
	

}
