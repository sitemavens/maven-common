<?php

namespace Maven\Core\Mappers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class AttributeMapper extends \Maven\Core\Db\WordpressMapper {

	public function __construct() {

		parent::__construct( "mvn_attributes" );
	}

	public function getCount( \Maven\Core\Domain\AttributeFilter $filter ) {

		$where = '';
		$values = array();

		$name = $filter->getName();
		if ( $name ) {
			$values[] = "%{$name}%";
			$where.=" AND name LIKE %s";
		}

		$query = "select count(*)
				from {$this->tableName} 
				where 1=1
				{$where}";

		$query = $this->prepare( $query, $values );

		return $this->getVar( $query );
	}
	
	public function getAll( \Maven\Core\Domain\AttributeFilter $filter, $orderBy = "name", $orderType = 'desc', $start = 0, $limit = 1000 ) {
		$where = '';
		$values = array();

		$name = $filter->getName();
		if ( $name ) {
			$values[] = "%{$name}%";
			$where.=" AND name LIKE %s";
		}
		
		if ( !$orderBy ) {
			$orderBy = 'id';
		}

		$values[] = $start;
		$values[] = $limit;

		$query = "select	{$this->tableName}.*
					from {$this->tableName} 
					where 1=1 
					{$where} order by {$orderBy} {$orderType}
					LIMIT %d , %d;";

		$query = $this->prepare( $query, $values );

		$results = $this->getQuery( $query );

		$attributes = array();

		foreach ( $results as $row ) {
			$attribute = new \Maven\Core\Domain\Attribute();
			$this->fillObject( $attribute, $row );

			$attributes[] = $attribute;
		}

		return $attributes;
	}
	
	
	/**
	 * Return an Attribute object
	 * @param int $id
	 * @return \Maven\Core\Domain\Attribute
	 */
	public function get( $id ) {

		$instance = new \Maven\Core\Domain\Attribute();

		if ( !$id ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );
		}

		$row = $this->getRowById( $id );

		if ( !$row ) {
			throw new \Maven\Exceptions\NotFoundException();
		}


		$this->fillObject( $instance, $row );

		return $instance;
	}

	/**
	 * Get attributes by plugin key
	 * @return \Maven\Core\Domain\Attribute[]
	 */
	public function getAttributesByPluginKey( $pluginKey ) {

		$attributes = array();

		if ( !$pluginKey ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Plugin Key: is required' );
		}

		$rows = $this->getResultsBy( 'plugin_key', $pluginKey );

		foreach ( $rows as $row ) {
			$instance = new \Maven\Core\Domain\Attribute();

			$this->fillObject( $instance, $row );

			$attributes[] = $instance;
		}

		return $attributes;
	}
	
	
	/**
	 * Return the profile's address
	 * @param int $id
	 * @return \Maven\Core\Domain\Attribute[]
	 */
	public function getAttributesByThingId( $thingId, $pluginKey ) {

		throw new \Maven\Exceptions\MavenException('Not implemented yet');
	}
	
	
	/**
	 * Save address
	 * @param \Maven\Core\Domain\Attribute $attribute
	 * @return \Maven\Core\Domain\Attribute
	 * @throws \Maven\Exceptions\RequiredException
	 */
	public function save( \Maven\Core\Domain\Attribute $attribute ) {

		$attribute->sanitize();

		$data = array(
		    'name' => $attribute->getName(),
		    'description' => $attribute->getDescription(),
		    'plugin_key' => $attribute->getPluginKey(),
			'default_amount' => $attribute->getDefaultAmount(),
			'default_wholesale_amount' => $attribute->getDefaultWholesaleAmount()
		);

		$format = array(
		    '%s', // name
		    '%s', // description
		    '%s', // plugin_key
			'%d', // default_amount
			'%d'  // default_wholesale_amount
		);

		if ( ! $attribute->getId() ) {
			$id = $this->insert( $data, $format );
			$attribute->setId( $id );
		} else {
			$this->updateById( $attribute->getId(), $data, $format );
		}
		
		return 	$attribute;	 

	}

	/**
	 * 
	 * @param int $id
	 * @return void
	 */
	public function delete( $id ) {
		//delete the address
		return parent::deleteRow( $id );
	}

}
