<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class VariationGroup extends \Maven\Core\DomainObject {

	private $name;
	private $price;
	private $wholesalePrice;
	private $image;
	private $groupKey;
	private $identifier;
	private $quantity;
	private $priceOperator;
	private $pluginKey;
	private $thingId;
	private $salePrice;
	
	/**
	 *
	 * @var VariationOption[] 
	 */
	private $options = array();
	
	public function __construct( $id = false ) {
		 
		parent::__construct( $id );
		
		$rules = array(
			
			'name'				=> \Maven\Core\SanitizationRule::Text,
			'price'				=> \Maven\Core\SanitizationRule::Float,
			'priceOperator'		=> \Maven\Core\SanitizationRule::Text,
			'image'				=> \Maven\Core\SanitizationRule::Text,
			'identifier'		=> \Maven\Core\SanitizationRule::Text,
			'quantity'			=> \Maven\Core\SanitizationRule::Integer,
			'key'				=> \Maven\Core\SanitizationRule::Key,
			'pluginKey'			=> \Maven\Core\SanitizationRule::Key,
			'thingId'			=> \Maven\Core\SanitizationRule::Integer,
			'salePrice'			=> \Maven\Core\SanitizationRule::Float
			
		);
	
		$this->setSanitizationRules( $rules );
	}
	
	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}

	public function getPrice() {
		return $this->price;
	}

	public function setPrice( $price ) {
		$this->price = $price;
	}
	
	public function getWholesalePrice() {
		return $this->wholesalePrice;
	}

	public function setWholesalePrice( $wholesalePrice ) {
		$this->wholesalePrice = $wholesalePrice;
	}
	
	public function getImage() {
		return $this->image;
	}

	public function setImage( $image ) {
		$this->image = $image;
	}

	public function getGroupKey () {
		return $this->groupKey;
	}

	public function setGroupKey ( $groupKey ) {
		$this->groupKey = $groupKey;
	}

	

	public function getIdentifier() {
		return $this->identifier;
	}

	public function setIdentifier( $identifier ) {
		$this->identifier = $identifier;
	}

	public function getQuantity() {
		return $this->quantity;
	}

	public function setQuantity( $quantity ) {
		$this->quantity = $quantity;
	}

	public function getPriceOperator() {
		return $this->priceOperator;
	}

	public function setPriceOperator( $priceOperator ) {
		
		if ( ! $priceOperator )
			return;
		
		if ( !VariationOptionPriceOperator::isValid( $priceOperator ) ) {
			throw new \Maven\Exceptions\MavenException( 'Invalid operator' );
		}

		$this->priceOperator = $priceOperator;
	}


	/**
	 * 
	 * @param \Maven\Core\Domain\VariationOption $option
	 */
	public function addOptions( VariationOption $option ){
		$this->options[ $option->getId() ] = $option;
		
		// We need to sort the array
		asort( $this->options );
	}
	
	/**
	 * 
	 * @return VariationOption[]
	 */
	public function getOptions(){
		return $this->options;
	}
	
	
	public function buildKey(){
		
		if ( $this->getGroupKey() ) {
			$groupKeys = explode( "-", $this->getGroupKey() );
		} else {
			$groupKeys = array();
			foreach ( $this->options as $option ) {
				$groupKeys[] = $option->getId();
			}
		}

		// We need to ensure that the key is sorted
		asort( $groupKeys ); 
		
		$groupKeys = implode( "-", $groupKeys );
		
		return $groupKeys;
		
	}
	
	public function hasOptions(){
		return $this->options && count( $this->options ) > 0;
	}
	
	public function getPluginKey() {
		return $this->pluginKey;
	}

	public function setPluginKey( $pluginKey ) {
		$this->pluginKey = $pluginKey;
	}

	public function getThingId() {
		return $this->thingId;
	}

	public function setThingId( $thingId ) {
		$this->thingId = $thingId;
	}

	public function getSalePrice() {
		return $this->salePrice;
	}

	public function setSalePrice( $salePrice ) {
		$this->salePrice = $salePrice;
	}



}
 