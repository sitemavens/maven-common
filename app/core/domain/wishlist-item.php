<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class WishlistItem extends \Maven\Core\DomainObject {

	protected $name;
	protected $quantity;
	protected $profileId;
	protected $price;
	protected $thingVariationId;
	protected $sku;
	protected $thingId;
	protected $attributes;
	protected $timestamp;

	/**
	 *
	 * @var string 
	 */
	protected $pluginKey;

	/**
	 *
	 * @var string 
	 */
	protected $identifier;

	/**
	 * Instantiate wishlist item
	 * @param string $groupKey
	 * @param int $id
	 */
	public function __construct ( $pluginKey = '' ) {

		parent::__construct( false );

		$this->pluginKey = $pluginKey;

		$rules = array(
			'name' => \Maven\Core\SanitizationRule::Text,
			'profileId' => \Maven\Core\SanitizationRule::Integer,
			'price' => \Maven\Core\SanitizationRule::Float,
			'sku' => \Maven\Core\SanitizationRule::Text,
			'pluginKey' => \Maven\Core\SanitizationRule::Key,
			'variationId' => \Maven\Core\SanitizationRule::Integer
		);

		$this->setSanitizationRules( $rules );
	}

	public function getSku () {
		return $this->sku;
	}

	public function setSku ( $sku ) {
		$this->sku = $sku;
	}

	public function getPrice () {
		return $this->price;
	}

	public function setPrice ( $price ) {
		$this->price = $price;
	}

	public function getOrderId () {
		return $this->orderId;
	}

	public function setOrderId ( $orderId ) {
		$this->orderId = $orderId;
	}

	public function getName () {
		return $this->name;
	}

	public function setName ( $name ) {
		$this->name = $name;
	}

	public function getHash () {
		
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
	public function getIdentifier () {
		return $this->getPluginKey() . "-" . $this->getThingId() . "-" . $this->getThingVariationId();
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
	 * @collectionType: \Maven\Core\Domain\OrderItemAttribute
	 * @serialized
	 * @return \Maven\Core\Domain\OrderItemAttribute[]
	 */
	public function getAttributes () {
		return $this->attributes;
	}

	public function setAttributes ( $attributes ) {
		$this->attributes = $attributes;
	}

	public function getTimestamp () {

		return date( 'M j, Y', strtotime( $this->timestamp ) );
	}

	public function setTimestamp ( $timestamp ) {
		$this->timestamp = $timestamp;
	}

	/**
	 * 
	 * @param int $id
	 * @param string $name
	 * @param float $price
	 */
	public function addAttribute ( $id, $name, $price ) {

		$attribute = new OrderItemAttribute();
		$attribute->setName( $name );
		$attribute->setId( $id );
		$attribute->setPrice( $price );

		$this->attributes[] = $attribute;
	}

	public function sanitize () {
		parent::sanitize();

		if ( \Maven\Core\Utils::isEmpty( $this->attributes ) ) {
			return;
		}

		foreach ( $this->attributes as $attribute ) {
			$attribute->sanitize();
		}
	}

	public function hasAttributes () {
		return !\Maven\Core\Utils::isEmpty( $this->attributes );
	}

	public function getAttributesNames () {

		$names = "";
		if ( $this->hasAttributes() ) {
			foreach ( $this->attributes as $attribute ) {
				if ( !$names ) {
					$names.=$attribute->getName();
				} else {
					$names.= ", " . $attribute->getName();
				}
			}
		}

		return $names;
	}

}
