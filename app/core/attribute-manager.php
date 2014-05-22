<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class AttributeManager {

	private $mapper;

	public function __construct(  ) {

		$this->mapper = new Mappers\AttributeMapper( );
		
	}

	public function getAll( Domain\AttributeFilter $filter, $orderBy = "name", $orderType = 'desc', $start = 0, $limit = 1000 ) {

		return $this->mapper->getAll( $filter, $orderBy, $orderType, $start, $limit );
	}
	
	public function addAttribute( \Maven\Core\Domain\Attribute $attribute ){
		return $this->mapper->save($attribute);
	}
	
	
	public function getCount(Domain\AttributeFilter $filter ) {
		

		return $this->mapper->getCount( $filter );
	}
	
	
	/**
	 * 
	 * @param int $id
	 * @return \Maven\Core\Domain\Attribute
	 */
	public function get( $id ) {

		$instance = $this->mapper->get( $id );

		return $instance;
	}
  

	/**
	 * 
	 * @param int attributeId
	 * @return type
	 */
	public function delete( $attributeId ) {
		return $this->mapper->delete( $variationId );
	}

}

