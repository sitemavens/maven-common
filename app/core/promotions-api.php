<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class PromotionsApi {

	private $pluginKey = "";
	
	public function __construct( $key = "" ) {
		$this->pluginKey = $key;
	}

	/**
	 * Get promotions
	 * @return type
	 */
	public function getAllPromotions() {

		$manager = new PromotionManager( );

		$filter = new Domain\PromotionFilter();

		return $manager->getPromotions( $filter );
	}

	/**
	 * Get promotions by filter
	 * @param \Maven\Core\Domain\PromotionFilter $filter
	 * @return type
	 */
	public function getPromotions( \Maven\Core\Domain\PromotionFilter $filter, $orderBy = 'name', $orderType = 'desc', $start = 0, $limit = 1000 ) {

		$manager = new PromotionManager( $this->pluginKey );

		return $manager->getPromotions( $filter, $orderBy, $orderType, $start, $limit );
	}
	
	public function getPromotionsCount( \Maven\Core\Domain\PromotionFilter $filter ) {

		$manager = new PromotionManager( $this->pluginKey );

		$filter->setPluginKey( $this->pluginKey );

		return $manager->getPromotionsCount( $filter );
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\Promotion
	 */
	public function newPromotion() {

		return new \Maven\Core\Domain\Promotion();
	}

	/**
	 * Add a new promotion
	 * @param \Maven\Core\Domain\Promotion $promotion
	 * @return type
	 */
	public function addPromotion( \Maven\Core\Domain\Promotion $promotion ) {

		$manager = new PromotionManager( $this->pluginKey );

		return $manager->addPromotion( $promotion );
	}

	/**
	 * Get the promotion
	 * @param int/object $promotionId
	 */
	public function getPromotion( $promotionId ) {

		if ( !$promotionId ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Promotion ID is required.' );
		}

		$manager = new \Maven\Core\PromotionManager( $this->pluginKey );

		$promotion = $manager->get( $promotionId );

		return $promotion;
	}

	/**
	 * 
	 * @param int/object $promotionId
	 */
	public function delete( $promotionId ) {

		$manager = new \Maven\Core\PromotionManager( $this->pluginKey );

		return $manager->delete( $promotionId );
	}

	/**
	 * 
	 * @return type
	 */
	public static function getTypes() {
		return \Maven\Core\PromotionManager::getTypes();
	}

	public static function getSections() {

		return \Maven\Core\PromotionManager::getSections();
	}

	/**
	 * 
	 * @param string $promotionCode
	 * @param \Maven\Core\Domain\Order $order
	 * @return \Maven\Core\Domain\Order
	 */
	public function applyPromotion( $promotionCode, \Maven\Core\Domain\Order $order ) {

		$manager = new \Maven\Core\PromotionManager( $this->pluginKey );

		return $manager->applyPromotion( $promotionCode, $order );
	}

	/**
	 * 
	 * @param string $promotionCode
	 * @param \Maven\Core\Domain\Order $order
	 * @return type
	 */
	public function removePromotion( $promotionCode, \Maven\Core\Domain\Order $order ) {

		$manager = new \Maven\Core\PromotionManager( $this->pluginKey );

		return $manager->removePromotion( $promotionCode, $order );
	}

	/**
	 * 
	 * @param type $promotionCode
	 * @return type
	 */
	public function isValid( $promotionCode ) {

		$manager = new \Maven\Core\PromotionManager( );

		return $manager->isValid( $promotionCode );
	}
	
	/**
	 * Increase number of uses of promotion
	 * 
	 * @param type $promotionCode
	 * @return type
	 */
	public function usePromotion($promotionCode){
		$manager = new \Maven\Core\PromotionManager( );

		return $manager->usePromotion( $promotionCode );
	}

}