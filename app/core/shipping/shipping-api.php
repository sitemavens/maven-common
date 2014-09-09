<?php

namespace Maven\Core\Shipping;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class ShippingApi{
	
	private $shippingMethodManager;
	
	private static $instance; 
	
	public function __construct () {
		$this->shippingMethodManager = new \Maven\Core\ShippingMethodManager();
	}
	
	/**
	 * 
	 * @return \Maven\Core\Shipping\ShippingApi
	 */
	public static function current(){
		
		if ( ! self::$instance ){
			self::$instance = new ShippingApi();
		}
		
		return self::$instance;
	}
	
	/**
	 * Get enabled methods
	 * @param string $country
	 * @param string $state
	 * @return \Maven\Core\Domain\ShippingMethod[]
	 */
	public function getEnabledMethods( $country = "*", $state = "*" ){
		
		return $this->shippingMethodManager->getEnabledMethods($country, $state);
		
	}
	
	public function getEnabledMethodById( $id ){
		
		return $this->shippingMethodManager->getEnabledMethodById( $id );
		
	}
	
	public function findShippingAmount( $total, $country = "*", $state = "*", Domain\ShippingMethod $shippingMethod = null ){
		
		return $this->shippingMethodManager->findShippingAmount($total, $country, $state, $shippingMethod);
				
	}
	
	
	
}