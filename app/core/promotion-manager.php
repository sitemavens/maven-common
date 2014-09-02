<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class PromotionManager {

	private $pluginKey;

	/**
	 * Construct
	 */
	public function __construct( $pluginKey = "" ) {
		$this->pluginKey = $pluginKey;
	}

	/**
	 * Re calculate promotions
	 * @param \Maven\Core\Domain\Order $order
	 */
	public function reCalculatePromotions( \Maven\Core\Domain\Order $order ) {

		$promotions = $order->getPromotions();

		foreach ( $promotions as $promotion ) {
			$this->applyPromotion( $promotion, $order );
		}
	}

	public function addPromotion( \Maven\Core\Domain\Promotion $promotion ) {

		if ( ! ($promotion->getCode()) )
			throw new \Maven\Exceptions\MissingParameterException( "Promotion Code  is required" );
		//TODO: Add other validations

		$mapper = new Mappers\PromotionMapper();

		return $mapper->save( $promotion );
	}

	public function addMultiplePromotions( Domain\Promotion $promotion, $quantity ) {
		if ( ! $quantity ) {
			throw new \Maven\Exceptions\MissingParameterException( "Quantity is required" );
		}
		if ( $quantity <= 0 ) {
			throw new \Maven\Exceptions\MavenException( "Quantity must be bigger than 0" );
		}

		$i = 1;
		$errors = 0;
		while ( $i <= $quantity ) {
			try {
				//remove the promotion id, so we can create a new promotion on every save
				$promotion->setId( NULL );
				//update code
				$promotion->setCode( Utils::getToken( 16 ) );

				$this->addPromotion( $promotion );

				$i ++;
			} catch ( \Exception $e ) {
				//there was some problem, increase error count
				$errors ++;
			}
			if ( $errors > $quantity ) {
				//something happens, abort
				return false;
			}
		}
		return true;
	}

	public function get( $promotionId ) {

		if ( ! $promotionId )
			throw new \Maven\Exceptions\MissingParameterException( "Promotion id is required" );

		$mapper = new Mappers\PromotionMapper();

		return $mapper->get( $promotionId );
	}

	public function getByPlugin( $pluginKey ) {

		if ( ! $pluginKey )
			throw new \Maven\Exceptions\MissingParameterException( "Plugin Key is required" );

		$mapper = new Mappers\PromotionMapper();

		return $mapper->getByPlugin( $pluginKey );
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\PromotionFilter  $filter
	 * @return \Maven\Core\Domain\Promotion[]
	 */
	public function getPromotions( \Maven\Core\Domain\PromotionFilter $filter, $orderBy = 'name', $orderType = 'desc', $start = 0, $limit = 1000 ) {

		/* if ( !$filter->getPluginKey() ) {
		  throw new \Maven\Exceptions\MissingParameterException( "Plugin Key is required" );
		  } */

		$mapper = new Mappers\PromotionMapper();

		return $mapper->getPromotions( $filter, $orderBy, $orderType, $start, $limit );

		//if ( $filter->getNumber() )
		//	return $this->getEventsByNumber( $filter->getNumber(), $filter->getPluginKey() );
	}

	public function getPromotionsCount( \Maven\Core\Domain\PromotionFilter $filter ) {

		/* if ( ! $filter->getPluginKey() )
		  throw new \Maven\Exceptions\MissingParameterException( "Plugin Key is required" );
		 */
		$mapper = new Mappers\PromotionMapper();

		return $mapper->getPromotionsCount( $filter );
	}

	public function delete( $promotionId ) {
		$mapper = new Mappers\PromotionMapper();

		return $mapper->delete( $promotionId );
	}

	public static function getTypes() {
		return Mappers\PromotionMapper::getTypes();
	}

	public static function getSections() {
		return Mappers\PromotionMapper::getSections();
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Promotion $promotion
	 * @param \Maven\Core\Domain\Order $order
	 * @return type
	 */
	private function executeCartPromo( Domain\Promotion $promotion, Domain\Order $order, $apply = true ) {

		$price = $order->getSubtotal() + $order->getShippingAmount();

		$type = $promotion->getType();
		$value = $promotion->getValue();
		$discount = 0;

		switch ( $type ) {

			case 'percentage': //percentage discount
				$discount = (($price * $value) / 100.00);
				break;

			case 'amount': //amount discount
				$discount = $value;
				break;
			default: //If type not recognize, do nothing
				break;
		}

		if ( $discount > $price ) {
			$discount = $price;
		}

		$promotion->setDiscountAmount( $discount );
//
//		if ( $apply ){
//			//Apply the discount to total 
//			$order->setTotal( $order->getTotal() - $discount );
//		}
//		else
//			$order->setTotal( $order->getTotal() + $discount );

		return $promotion;
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Promotion $promotion
	 * @param \Maven\Core\Domain\Order $order
	 * @return type
	 */
	private function executeShippingPromo( Domain\Promotion $promotion, Domain\Order $order, $apply ) {
		$price = $order->getShippingAmount();

		$type = $promotion->getType();
		$value = $promotion->getValue();
		$discount = 0;
		switch ( $type ) {
			case 'percentage': //percentage discount
				$discount = (($price * $value) / 100.00);
				break;
			case 'amount': //amount discount
				$discount = $value;
				break;
			default: //If type not recognize, do nothing
				break;
		}

		if ( $discount > $price ) {
			$discount = $price;
		}

		$promotion->setDiscountAmount( $discount );

		return $promotion;
	}

	private function executeItemPromo( Domain\Promotion $promotion, Domain\Order $order, $apply = true ) {

		$price = $order->getSubtotal();

		$type = $promotion->getType();
		$value = $promotion->getValue();
		$discount = 0;
		switch ( $type ) {
			case 'percentage': //percentage discount
				$discount = (($price * $value) / 100.00);
				break;
			case 'amount': //amount discount
				$discount = $value;
				break;
			default: //If type not recognize, do nothing
				break;
		}

		if ( $discount > $price ) {
			$discount = $price;
		}

		$promotion->setDiscountAmount( $discount );

		return $promotion;
	}

	/**
	 * 
	 * @param string | object $promotion
	 * @param \Maven\Core\Domain\Order $order
	 * @return void
	 */
	public function removePromotion( $promoCode, Domain\Order $order ) {

		return $this->executePromo( $promoCode, $order, false );
	}

	/**
	 * 
	 * @param string | object $promotion
	 * @param \Maven\Core\Domain\Order $order
	 * @return void
	 */
	public function applyPromotion( $promotion, Domain\Order $order ) {

		return $this->executePromo( $promotion, $order, true );
	}

	/**
	 * 
	 * @param string $promoCode
	 * @param \Maven\Core\Domain\Order $order
	 * @param boolean $apply Determine if the promo have to be added or removed
	 * @return type
	 */
	private function executePromo( $promoCode, Domain\Order $order, $apply ) {

		//Promotion invalid or not aplicable
		$result = $this->isValid( $promoCode );

		if ( $result->isError() )
			return $result;

		$promotion = $result->getData();

		$section = $promotion->getSection();

		switch ( $section ) {
			case 'cart':
				$discount = $this->executeCartPromo( $promotion, $order, $apply );
				break;
			case 'item':
				$discount = $this->executeItemPromo( $promotion, $order, $apply );
				break;
			case 'shipping':
				$discount = $this->executeShippingPromo( $promotion, $order, $apply );
				break;
			default: //do nothing
				break;
		}



		if ( $apply )
		//Save the promotion
			$order->addPromotion( $promotion );
		else
			$order->removePromotion( $promotion );

		/* $price = $order->getTotal();
		  //Everithing seems ok
		  $type = $promotion->getType();
		  $value = $promotion->getValue();
		  $finalPrice = 0;
		  switch ( $type ) {
		  case 'percentage': //percentage discount
		  $finalPrice = $price - (($price * $value) / 100.00);
		  break;
		  case 'amount': //amount discount
		  $finalPrice = $price - $value;
		  break;
		  default: //If type not recognize
		  $finalPrice = $price;
		  } */

		//TODO: what happend if final price is negative?, for now, we return 0
		//return $discount;
		
		return $order;
	}

	/**
	 * Return promotion code, by code and key
	 * @param string $promotionCode
	 * @param string $key 
	 * @return \Maven\Core\Domain\Promotion
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	private function getPromotionByCode( $promotionCode, $key = "" ) {
		if ( ! $promotionCode )
			throw new \Maven\Exceptions\MissingParameterException( "Promotion Code is required" );

		$mapper = new Mappers\PromotionMapper();

		return $mapper->getPromotionByCode( $promotionCode, $key );
	}

	/**
	 * Verify if a promotion exists
	 * @param type $promotionCode
	 * @return boolean
	 */
	public function exists( $promotionCode ) {

		$promotion = $this->getPromotionByCode( $promotionCode );

		if ( $promotion )
			return true;

		return false;
	}

	/**
	 * 
	 * @param string | object $promotion
	 * @return \Maven\Core\Message\Message
	 */
	public function isValid( $promotion ) {

		if ( ! is_object( $promotion ) )
			$promotion = $this->getPromotionByCode( $promotion );

		if ( ! $promotion )
			return Message\MessageManager::createErrorMessage( 'Promotion doesn\'t exists' );

		if ( ! $promotion->isEnabled() ) {
			return Message\MessageManager::createErrorMessage( 'Promotion Code Disabled' );
		}

		if ( $this->outOfDate( $promotion ) ) {
			return Message\MessageManager::createErrorMessage( 'Promotion Code Out of Date' );
		}

		if ( ! $this->hasLimitAvailable( $promotion ) ) {
			return Message\MessageManager::createErrorMessage( 'Promotion Code Out of Limit of Use' );
		}

		return Message\MessageManager::createRegularMessage( 'Valid promotion', $promotion );
	}

	private function outOfDate( Domain\Promotion $promotion ) {
		// if both are 0 it is unlimited date
		if ( $promotion->getFrom() == 0 && $promotion->getTo() == 0 )
			return false;
		// Remember: strtotime return -1 if it is an error
		$dateFrom = strtotime( $promotion->getFrom() );
		$dateTo = strtotime( $promotion->getTo() );
		$current_time = current_time( 'timestamp' );

		if ( ($dateFrom == 0 || ( $dateFrom > 0 && $dateFrom < $current_time ) ) && ( $dateTo == 0 || ($dateTo > 0 && $dateTo > $current_time ) ) ) {
			return false;
		}

		return true;
	}

	private function hasLimitAvailable( Domain\Promotion $promotion ) {
		// 0 or empty represents unlimited uses
		$limit = $promotion->getLimitOfUse();
		$uses = $promotion->getUses();
		if ( empty( $limit ) ) {
			return true;
		}

		// If there are limit of use
		if ( $uses < $limit ) {
			return true;
		}
		return false;
	}

	public function usePromotion( $promotionCode ) {

		$promotion = $this->getPromotionByCode( $promotionCode );

		if ( $promotion ) {

			$uses = $promotion->getUses();

			$promotion->setUses( $uses + 1 );

			return $this->addPromotion($promotion);
		}

		return false;
	}

}
