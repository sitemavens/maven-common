<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class VariationManager {

	private $mapper;
	private $registry;

	public function __construct( \Maven\Settings\Registry $registry ) {

		$this->mapper = new Mappers\VariationMapper( $registry );
		
		$this->registry = $registry;
	}

	/**
	 * 
	 * @param int $id
	 * @return \Maven\Core\Domain\Variation
	 */
	public function get( $id ) {

		$instance = $this->mapper->get( $id );

		$variationOptionsMapper = new Mappers\VariationOptionMapper();

		$options = $variationOptionsMapper->getOptions( $id );

		$instance->setOptions( $options );

		return $instance;
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Variation $variation
	 * @return \Maven\Core\Domain\Variation
	 */
	public function save( \Maven\Core\Domain\Variation $variation ) {

		$variation = $this->mapper->save( $variation );
		
		
		if ( $variation->hasOptions() ) {

			$variationOptionsMapper = new Mappers\VariationOptionMapper();

			$options = $variation->getOptions();

			$variationOptionsMapper->saveMultiple ( $variation, $options );
		}
		
		return $variation;
	}

	public function getThingVariations( $thingId ) {

		$variations = $this->mapper->getThingVariations( $thingId );

		foreach ( $variations as $variation ) {

			$variationOptionsMapper = new Mappers\VariationOptionMapper();

			$options = $variationOptionsMapper->getOptions( $variation->getId() );

			$variation->setOptions( $options );
			
			//Check if it has saved values
			$variationGroupManager = new VariationGroupManager( $this->registry );
			$variationGroups = $variationGroupManager->getVariationGroups( $thingId );

			$variation->setGroups( $variationGroups );
		}
		
		
		
		return $variations;
	}
	
	public function saveMultiple( $variations, $thingId ){
		return $this->mapper->saveMultiple( $variations, $thingId );
	}
	
	public function deleteThingVariations( $thingId ){
		
		return $this->mapper->deleteThingVariations( $thingId );
	}

	public function getMatrix( $thingId ) {

		$variations = $this->getThingVariations( $thingId );

		if ( ! $variations )
			return array( "header" => array(), "rows" => 0, "columns" => 0, "matrix" => array() );
		
		
		$matrix = array( );

		// Count the numbers of options of all the variations
		$countRows = 1;

		// Count the number of variations
		$countVariations = count( $variations );

		$headers = array( );

		// We don't have options yet.
		foreach ( $variations as $variation ) {
			$countRows *= count( $variation->getOptions() );

			$headers[ $variation->getId() ] = $variation;
		}

		$variationIndex = 0;

		$variation = $variations[ $variationIndex ];

		// Count how many times an option must be repeated
		$countOptions = $countRows / count( $variation->getOptions() );
 
		
		$matrix = $this->buildMatrix( $matrix, $variationIndex, $variations, $countRows );

		return array( "header" => $headers, "rows" => $countRows, "columns" => $countVariations, "matrix" => $matrix );
	}

	private function buildMatrix( $matrix, $variationIndex, $variations, $countRows ) {

		if ( $variationIndex <= count( $variations ) - 1 ) {

			$variation = $variations[ $variationIndex ];
					
			$matrix[ $variation->getId() ] = array( );

			$countOptions = count( $variation->getOptions() );
			
			if ( $countOptions )
				// Count how many times an option must be repeated
				$countOptions = $countRows / $countOptions;
			
			// Does it have prev options? 
			$prevOptions = isset( $variations[ $variationIndex - 1 ] ) ? $matrix[ $variations[ $variationIndex - 1 ]->getId() ] : array( );

			$options = $variation->getOptions();
			
			if ( $prevOptions ) {
				$optionIndex=0;
				while ( $optionIndex<=count( $prevOptions )-1) {

					$options = $variation->getOptions();

					foreach ( $options as $option ) {

						for ( $i = 0; $i <= $countOptions - 1; $i++ ) {
							$matrix[ $variation->getId() ][ ] = $option->toArray();
							$optionIndex++;
						}
					}
				}
			} else {

				foreach ( $options as $option ) {

					for ( $i = 0; $i <= $countOptions - 1; $i++ ) {
						$matrix[ $variation->getId() ][ ] = $option->toArray();
					}
				}
			};

			return $this->buildMatrix( $matrix, $variationIndex + 1, $variations, $countOptions );
			
		} else {
			return $matrix;
		}
	}

	/**
	 * 
	 * @param int $adddressId
	 * @return type
	 */
	public function delete( $variationId ) {
		return $this->mapper->delete( $variationId );
	}
}

