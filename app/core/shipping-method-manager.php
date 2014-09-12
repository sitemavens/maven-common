<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class ShippingMethodManager extends Manager{

	private $mapper;

	public function __construct () {

		parent::__construct();
		
		$this->mapper = new Mappers\ShippingMethodMapper();
	}
	
	public function getAll( Domain\ShippingMethodFilter $filter, $orderBy = "name", $orderType = 'desc', $start = 0, $limit = 1000 ) {

		return $this->mapper->getAll( $filter, $orderBy, $orderType, $start, $limit );
	}
	
	
	public function getCount(Domain\ShippingMethodFilter $filter ) {
		
		return $this->mapper->getCount( $filter );
	}
	

	public function get ( $shippingMethodId ) {

		if ( !$shippingMethodId ) {
			throw new \Maven\Exceptions\MissingParameterException( "Shipping method id is required" );
		}

		return $this->mapper->get( $shippingMethodId );
	}

	/**
	 * Return enabled methods
	 * @param string $country
	 * @param string $state
	 * @return \Maven\Core\Domain\ShippingMethod[]
	 */
	public function getEnabledMethods ( $country = "*", $state = "*" ) {
		
		$enabledMethods = $this->mapper->getEnabledMethods();

		$enabledMethods = $this->getHookManager()->applyFilters('maven/shippingMethod/enabled',  $enabledMethods );
		
		$methods = array();

		if ( $enabledMethods ) {
			foreach ( $enabledMethods as $method ) {
				if ( $method->getMethod()->existsDestination( $country, $state ) ) {
					$methods[] = $method;
				}
			}
		}

		return $methods;
	}
	
	public function getEnabledMethodById( $id ){
	
		$methods =  $this->getEnabledMethods();
		foreach( $methods as $method ) {
			if ( $method->getId() == $id ) 
				return $method;
		}
		
		return false;
		
	}

	public function delete ( $id ) {

		return $this->mapper->delete( $id );
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\ShippingMethod $shippingMethod
	 * @return \Maven\Core\Domain\ShippingMethod
	 * @throws \Maven\Exceptions\RequiredException
	 */
	public function addShippingMethod ( Domain\ShippingMethod $shippingMethod ) {

		if ( !$shippingMethod->getMethod() ) {
			throw new \Maven\Exceptions\RequiredException( 'Shipping Method is required' );
		}

		return $this->mapper->save( $shippingMethod );
	}

	/**
	 * Find the amount of shipping to be applied
	 *
	 * @param float $total
	 * @param string $country
	 * @param string $state
	 * @param \Maven\Core\Domain\ShippingMethod $shippingMethod
	 * @return int
	 * @throws \Maven\Exceptions\RequiredException
	 */
	public function findShippingAmount ( $total, $country = "*", $state = "*", $shippingMethod = null ) {

		
		if ( !$shippingMethod  ) {
			
			// Lets find the default method
			$shippingMethod = $this->getHookManager()->applyFilters('maven/shippingMethod/default', $shippingMethod  );
		
		}else if ( ! $shippingMethod instanceOf Domain\ShippingMethod ) {
			$shippingMethod = $this->getEnabledMethodById( $shippingMethod );
		}
			
		
		\Maven\Loggers\Logger::log()->message( 'Maven/Core/ShippingMethodManager/findShippingAmount'. print_r($shippingMethod,true) );
		
		//TODO @emiliano: What if there's no default shipping method
		if ( !$shippingMethod || ! method_exists($shippingMethod, 'getMethod') ) {
			//There isnt a default shipping method, just return 0
			return 0;
		}
		
		$destinations = array();
		if ( $shippingMethod->getMethod()->hasDestinations() ) {
			$destinations = $shippingMethod->getMethod()->getDestinations();
		}

		$destinations = $this->getHookManager()->applyFilters('maven/shippingMethod/findShippingAmount',  $destinations, $shippingMethod->getMethod()->getKey() );
		
		
		if ( $destinations ) {

			// First we need to ensure they are sorted by minValue
			$this->sortMethodTypeDestinations( $shippingMethod->getMethod() );

			foreach ( $destinations as $destination ) {

				//Since -1 is max, we need to check if MaxValue has that value, and convert it into something bigger
				$maxValue = $destination->getMaxValue() == -1 ? 999999999 : $destination->getMaxValue();

				if ( ($destination->getCountry() == $country || $destination->getCountry() == "*") && ( $destination->getState() == $state || $destination->getState() == "*") && ($total >= $destination->getMinValue() && $total <= $maxValue)
				) {
					return $destination->getAmount();
				}
			}

			return 0;
		}

		return 0;
	}

	/**
	 * Sort the Shipping Method Type Destinations
	 * @param \Maven\Core\Domain\ShippingMethodType $methodType
	 * @return void
	 */
	private function sortMethodTypeDestinations ( \Maven\Core\Domain\ShippingMethodType $methodType ) {
		$destinations = $methodType->getDestinations();
		usort( $destinations, function($a, $b) {
			return $a->getMinValue() > $b->getMinValue();
		}
		);
	}

}
