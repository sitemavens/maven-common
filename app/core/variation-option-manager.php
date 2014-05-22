<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class VariationOptionManager {

	private $mapper;

	public function __construct(  ) {

		$this->mapper = new Mappers\VariationOptionMapper();
	}
	
	
	/**
	 * 
	 * @param int $id
	 * @return \Maven\Core\Domain\VariationOption
	 */
	public function get( $id ){
		
		return $this->mapper->get($id);
		
	}
	
	/**
	 * 
	 * @param \Maven\Core\Domain\VariationOption $variationOption
	 * @return \Maven\Core\Domain\VariationOption
	 */
	public function save( \Maven\Core\Domain\VariationOption $variationOption ){
		
		return $this->mapper->save( $variationOption );
	}
	
	/**
	 * 
	 * @param int $adddressId
	 * @return type
	 */
	public function delete( $variationOptionId ){
		return $this->mapper->delete( $variationOptionId );
	}
	
	
	

}

