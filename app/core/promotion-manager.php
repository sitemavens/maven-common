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

		$discount = self::applyItemsRules($promotion, $order);
		$promotion->setDiscountAmount( $discount );

		return $discount;
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



		if ( $apply ) {
            if ($discount > 0) {
		        $order->addPromotion( $promotion );
            }
		} else {
			$order->removePromotion( $promotion );
        }

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

		return Message\MessageManager::createSuccessfulMessage( 'Valid promotion', $promotion );
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

	static function applyItemsRules( Domain\Promotion $promotion, Domain\Order $order ) {
        $discount = 0;
        $type = $promotion->getType();
		$items = $order->getItems();
		$rules = $promotion->getRules();
		foreach ($items as $key => $item) {
            $match = false;
			foreach ($rules as $rule) {
				switch ( $rule['rule'] ) {
					case 'item_name':
						$match = self::matchRuleCondition( $item->getName(), $rule['value'], $rule['condition'] );
                        $price = $item->getPrice();
						break;
					case 'item_id':
						$match = self::matchRuleCondition( $item->getThingId(), $rule['value'], $rule['condition'] );
                        $price = $item->getPrice();
						break;
					case 'item_quantity':
						$match = self::matchRuleCondition( $item->getQuantity(), $rule['value'], $rule['condition'], 'float' );
                        $price = $item->getPrice();
						break;
					case 'item_amount':
						$match = self::matchRuleCondition( $item->getTotal(), $rule['value'], $rule['condition'], 'float' );
                        $price = $item->getPrice();
						break;
					case 'item_category':
						$match = self::matchRuleCondition( $item->getThingId(), $rule['value'], $rule['condition'] );
                        $price = $item->getPrice();
						break;

					default:
                        $match = apply_filters('maven_apply_item_promotions', $match, $rule, $item);
                        $price = $item->getPrice();
						continue;
						break;
				}
				if($match==false){
					break;
				}
			}
		}
        if ($match) {
            switch ( $type ) {
                case 'percentage':
                    $discount = (($price * $promotion->getValue()) / 100.00);
                    break;
                case 'amount':
                    $discount = $promotion->getValue();
                    break;
                default:
                    break;
            }

            if ( $discount > $price ) {
                $discount = $price;
            }
        }
        return $discount;
	}

	static function matchRuleCondition( $current, $value_to_compare, $operator, $type = 'string' ) {
		switch($operator) {
			// String or Numeric operations
			case "is_equal_to":
			 	if($type == 'float') {
					return ( floatval($current) != 0
					&& floatval($value_to_compare) != 0
					&& floatval($current) == floatval($value_to_compare));
				} else {
					if (is_array($current)) 
						return (in_array($value_to_compare, $current));
					
					return ("$current" === "$value_to_compare");
				}
				break;
			case "is_not_equal_to":
				if($type == 'float') {
					return ( floatval($current) != 0
					&& floatval($value_to_compare) != 0
					&& floatval($current) != floatval($value_to_compare) );
				}else {
					if (is_array($current)) 
						return ( ! in_array($value_to_compare,$current) );
					
					return ( "$current" !== "$value_to_compare" );
				}
				break;

			// String operations
			case "contain":
				if (is_array($current)) {
					foreach ($current as $s){
						// IF multibyte functions are installed use it to compare, if not use common string methods
						if( function_exists('mb_stripos') && mb_stripos($s, $value_to_compare) !== false ){
							return true;
						}elseif (stripos($s, $value_to_compare) !== false) {
							return true;
						}
					}
					return false;
				}
				// IF multibyte functions are installed use it to compare, if not use common string methods
				if( function_exists('mb_stripos') ){
					return (mb_stripos($current, $value_to_compare) !== false);
				}
				
				return (stripos($current,$value_to_compare) !== false);
				break;
			case "not_contain":
				if (is_array($current)) {
					foreach ($current as $s) {
						// IF multibyte functions are installed use it to compare, if not use common string methods
						if( function_exists('mb_stripos') && mb_stripos($s, $value_to_compare) !== false ){
							return false;
						}elseif (stripos($s, $value_to_compare) !== false) {
							return false;
						}
					}
					return true;
				}
				// IF multibyte functions are installed use it to compare, if not use common string methods
				if( function_exists('mb_stripos') ){
					return (mb_stripos($current, $value_to_compare) === false);
				}
				
				return (stripos($current,$value_to_compare) === false);
				break;
			case "begins_with":
				if (is_array($current)) {
					foreach ($current as $s) {
						// IF multibyte functions are installed use it to compare, if not use common string methods
						if( function_exists('mb_stripos') && mb_stripos($s, $value_to_compare) === 0 ){
							return true;
						}elseif (stripos($s, $value_to_compare) === 0) {
							return true;
						}
					}
					return false;
				}
				// IF multibyte functions are installed use it to compare, if not use common string methods
				if( function_exists('mb_stripos') ){
					return (mb_stripos($current, $value_to_compare) === 0);
				}
				return (stripos($current,$value_to_compare) === 0); 
				break;
			case "ends_with":
				if (is_array($current)) {
					foreach ($current as $s) {
						// IF multibyte functions are installed use it to compare, if not use common string methods
						if( function_exists('mb_stripos') && function_exists('mb_strlen') &&  mb_stripos($s,$value_to_compare) === mb_strlen($s) - mb_strlen($value_to_compare) ){
							return true;
						}elseif (stripos($s,$value_to_compare) === strlen($s) - strlen($value_to_compare)) {
							return true;
						}
					}
					return false;
				}
				// IF multibyte functions are installed use it to compare, if not use common string methods
				if( function_exists('mb_stripos') && function_exists('mb_strlen') ){
					return (mb_stripos($current,$value_to_compare) === mb_strlen($current) - mb_strlen($value_to_compare));
				}
				
				return  (stripos($current,$value_to_compare) === strlen($current) - strlen($value_to_compare)); 
				break;

			// Numeric operations
			case "is_greater_than":
				return (floatval($current) > floatval($value_to_compare));
				break;
			case "is_greater_or_equal_than":
				return (floatval($current) >= floatval($value_to_compare));
				break;
			case "is_less_than":
				return (floatval($current) < floatval($value_to_compare));
				break;
			case "is_less_or_equal_than":
				return (floatval($current) <= floatval($value_to_compare));
				break;
			case "in_category":
                if (taxonomy_exists('mvne_category')) {
                    $taxonomy = 'mvne_category';
                } else if (taxonomy_exists('mvnb_category')) {
                    $taxonomy = 'mvnb_category';
                } else {
                    return false;
                }
				if ( strpos($value_to_compare, ':') !== FALSE ) {
					list($taxonomy, $value_to_compare) = explode( ':', $value_to_compare );
				}
				// If there is a comma in the value check if it is a list of terms ids or just a comma in the category name
				if ( strpos($value_to_compare, ',') !== FALSE ) {
					$all_posibilities = explode( ',', $value_to_compare );
					if ( $ints = array_filter( $all_posibilities, 'is_int' ) ){
						$value_to_compare = $ints;
					}
				}
				
				$r = is_object_in_term( $current, $taxonomy, $value_to_compare );
				if ( is_wp_error( $r ) )
					return FALSE;
				return $r;
				break;
			case "not_in_category":
				if (taxonomy_exists('mvne_category')) {
                    $taxonomy = 'mvne_category';
                } else if (taxonomy_exists('mvnb_category')) {
                    $taxonomy = 'mvnb_category';
                } else {
                    return false;
                }
				if ( strpos($value_to_compare, ':') !== FALSE ) {
					list($taxonomy, $value_to_compare) = explode( ':', $value_to_compare );
				}
				// If there is a comma in the value check if it is a list of terms ids or just a comma in the category name
				if ( strpos($value_to_compare, ',') !== FALSE ) {
					$all_posibilities = explode( ',', $value_to_compare );
					if ( $ints = array_filter( $all_posibilities, 'is_int' ) ){
						$value_to_compare = $ints;
					}
				}
				
				$r = is_object_in_term( $current, $taxonomy, $value_to_compare );
				// if it is an error return true tro say that it is not in this category
				if ( is_wp_error( $r ) )
					return FALSE;
				
				return ! $r;
				break;
		}

		return false;
	}

}
