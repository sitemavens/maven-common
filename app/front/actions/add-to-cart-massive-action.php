<?php

namespace Maven\Front\Actions;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

/**
 * 
 */
class AddToCartMassiveAction extends Action {

	public function __construct ( \Maven\Front\Step $step, \Maven\Core\Cart $cart, $data ) {
		parent::__construct( $step, $cart, $data );
	}

	/**
	 * 
	 * @return \Maven\Core\Message
	 * @throws \Maven\Exceptions\InvalidObjectTypeException
	 */
	public function execute () {

		// We have to fire only the plugin specific hook
		//$groupKey = $this->getStep()->getThing()->getGroupKey();
		 
		$things = $this->readThingData();
		
		foreach( $things as $thing ){
			
			if ( ! $thing->getPluginKey() ){
				throw new \Maven\Exceptions\RequiredException('You are trying to add an item with out a Plugin Key');
			}


			// Since a thing could have variations, each variation will be transformed as an item itself.
			// So we could have an array of items, instead of just one item
			$items = apply_filters( "maven/cart/addItem/{$thing->getPluginKey()}", $thing );

			if ( ! is_array( $items ) && !( $items instanceof \Maven\Core\Domain\OrderItem ) ) {
				throw new \Maven\Exceptions\InvalidObjectTypeException( "Return filter: maven/cart/addItem, must be \Maven\Core\Domain\OrderItem type" );
			} elseif ( is_array( $items ) ) {

				// Verify that all the items are 
				array_filter( $items, function( $item ) {

					if  (  ! ( $item instanceof \Maven\Core\Domain\OrderItem ) ) {
						throw new \Maven\Exceptions\InvalidObjectTypeException( "Return filter: maven/cart/addItem, must be \Maven\Core\Domain\OrderItem type" );
					}
				} );

				foreach ( $items as $item ) {
					$result = $this->getCart()->addToCart( $item );
				}

				return $result;
			}


			$result = $this->getCart()->addToCart( $items );
			$this->getStep()->setThing( $thing );

		}
		
		//TODO: It will return the last result, but we need to work on smomething that validate all the items.
		return $result;
		
	}

	/**
	 * 
	 * @return \Maven\Front\Thing
	 * @throws \Maven\Exceptions\InvalidObjectTypeException
	 * @throws \Maven\Exceptions\RequiredException
	 */
	private function readThingData () {

		$data = $this->getDataValue( Consts::Thing );

		if ( !$data ) {
			throw new \Maven\Exceptions\InvalidObjectTypeException( "No data available: remember you need to use <thing>" );
		}
		
		if ( ! is_array( $data ) ){
			throw new \Maven\Exceptions\InvalidObjectTypeException( "Data must be an array: remember you need to use <thing>" );
		}

		$things = array();
		$defaults = array(
			Consts::Id => '',
			Consts::Name => '',
			Consts::Quantity => '',
			Consts::PluginKey => '',
			Consts::Price => '',
			Consts::Variation => array()
		);

		$defaultVariation = array(
			Consts::Id => '',
			Consts::Quantity => '',
			Consts::Name => '',
			Consts::Price => '',
			Consts::OptionId => ''
		);
		
		//Since we are now reading several things we need to iterate the "thign" collection
		foreach( $data as $dataThing ){
			
			$dataThing = wp_parse_args( $dataThing, $defaults );

			if ( !$dataThing [ Consts::Id ] ) {
				throw new \Maven\Exceptions\RequiredException( "Thing id is required" );
			}

			if ( !$dataThing [ Consts::PluginKey ] ) {
				throw new \Maven\Exceptions\RequiredException( "Plugin Key id is required" );
			}

			$thing = new \Maven\Front\Thing( $dataThing [ Consts::PluginKey ] );
			$thing->setId( $dataThing[ Consts::Id] );
			$thing->setQuantity( $dataThing[ Consts::Quantity ] );
			$thing->setPrice( $dataThing[ Consts::Price ] );
			$thing->setName( $dataThing[ Consts::Name] );

			foreach ( $dataThing[ Consts::Variation ] as $key => $value ) {

				$value = wp_parse_args( $value, $defaultVariation );
				$variation = new \Maven\Front\ThingVariation();
				$variation->setId( $value[ Consts::Id ] );
				$variation->setQuantity( $value[ Consts::Quantity ] );
				$variation->setPrice( $value[ Consts::Price ] );
				$variation->setName( $value[ Consts::Name ] );
				$variation->setOptionId( $value[ Consts::OptionId ] );

				$thing->addVariation( $variation );
			}
			
			$things[] = $thing;

		}
		
		$this->setDataValue( Consts::Things, $things );

		return $things;
	}

}
