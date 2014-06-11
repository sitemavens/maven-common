<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class VariationGroupManager {

	private $mapper;

	public function __construct( \Maven\Settings\Registry $registry ) {

		$this->mapper = new Mappers\VariationGroupMapper( $registry );
	}

	/**
	 * 
	 * @param int $id
	 * @return \Maven\Core\Domain\Variation
	 */
	public function get( $id ) {

		$instance = $this->mapper->get( $id );

		return $instance;
	}

	/**
	 * Return a VariationGroup object
	 * @param mixed $key
	 * @return \Maven\Core\Domain\VariationGroup
	 */
	public function getByKey( $key ) {

		$instance = $this->mapper->getByKey( $key );

		return $instance;
	}

	/**
	 * 
	 * @param int $thingId
	 * @return Domain\VariationGroup[]
	 */
	public function getVariationGroups( $thingId ) {
		return $this->mapper->getVariationGroups( $thingId );
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Variation $variation
	 * @return \Maven\Core\Domain\Variation
	 */
	public function save( \Maven\Core\Domain\VariationGroup $variationGroup ) {

		$variationGroup = $this->mapper->save( $variationGroup );


		return $variationGroup;
	}

	/**
	 * 
	 * @param int $variationGroupId
	 * @return type
	 */
	public function delete( $variationGroupId ) {
		return $this->mapper->delete( $variationGroupId );
	}

	public function applyVariationPrice( $productPrice, $variationGroup, $isWholesale = FALSE ) {
		$variationPrice = $isWholesale ? $variationGroup->getWholesalePrice() : $variationGroup->getPrice();

		\Maven\Loggers\Logger::log()->message( 'MavenShop/VariationGroupManager/applyVariationPrice: Wholesale price: '.var_export($variationGroup->getWholesalePrice(),true) );
		\Maven\Loggers\Logger::log()->message( 'MavenShop/VariationGroupManager/applyVariationPrice: Variation price: '.var_export($variationPrice,true) );
		
		$operator = $variationGroup->getPriceOperator();
		switch ( $operator ) {
			case \Maven\Core\Domain\VariationOptionPriceOperator::Add:

				$productPrice = $productPrice + $variationPrice;

				break;
			case \Maven\Core\Domain\VariationOptionPriceOperator::Fixed:

				$productPrice = $variationPrice;

				break;
			case \Maven\Core\Domain\VariationOptionPriceOperator::NoChange:
				//TODO: ???? What is this?

				break;
			case \Maven\Core\Domain\VariationOptionPriceOperator::Substract:

				$productPrice = $productPrice - $variationPrice;

				break;
		}
		return $productPrice;
	}

}
