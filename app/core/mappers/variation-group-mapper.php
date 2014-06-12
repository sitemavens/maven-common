<?php

namespace Maven\Core\Mappers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class VariationGroupMapper extends \Maven\Core\Db\WordpressMapper {

	private $variationGroupOptionTable = "mvn_variation_group_option";

	public function __construct() {

		parent::__construct( "mvn_variation_group" );
	}

	/**
	 * Return a VariationGroup object
	 * @param int $id
	 * @return \Maven\Core\Domain\VariationGroup
	 */
	public function get( $id ) {

		$instance = new \Maven\Core\Domain\VariationGroup();

		if ( ! $id ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );
		}

		$row = $this->getRowById( $id );

		if ( ! $row ) {
			throw new \Maven\Exceptions\NotFoundException();
		}


		$this->fillObject( $instance, $row );

		return $instance;
	}

	/**
	 * Return a VariationGroup object
	 * @param mixed $groupKey
	 * @return \Maven\Core\Domain\VariationGroup
	 */
	public function getByKey( $groupKey ) {
		$instance = new \Maven\Core\Domain\VariationGroup();

		if ( ! $groupKey ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Group key: is required' );
		}

		$row = $this->getRowBy( 'group_key', $groupKey, '%s' );

		if ( $row ) {
			$this->fillObject( $instance, $row );
		}

		return $instance;
	}

	/**
	 * Return an VariationGroup object
	 * @param int $id
	 * @return \Maven\Core\Domain\VariationGroup[]
	 */
	public function getVariationGroups( $thingId ) {

		if ( ! $thingId ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Thing ID: is required' );
		}

		$rows = $this->getResultsBy( 'thing_id', $thingId );

		$variationGroups = array();

		if ( $rows ) {

			foreach ( $rows as $row ) {

				$instance = new \Maven\Core\Domain\VariationGroup();

				$this->fillObject( $instance, $row );

				$variationGroups[] = $instance;
			}
		}

		return $variationGroups;
	}

	public function save( \Maven\Core\Domain\VariationGroup $instance ) {

		$instance->sanitize();

		$data = array(
		    'name' => $instance->getName(),
		    'image' => $instance->getImage(),
		    'group_key' => $instance->buildKey(),
		    'identifier' => $instance->getIdentifier(),
		    'quantity' => $instance->getQuantity(),
		    'price' => $instance->getPrice(),
		    'wholesale_price' => $instance->getWholesalePrice(),
		    'price_operator' => $instance->getPriceOperator(),
		    'plugin_key' => $instance->getPluginKey(),
		    'thing_id' => $instance->getThingId(),
		    'sale_price' => $instance->getSalePrice()
		);


		$format = array(
		    '%s', // name
		    '%s', // image
		    '%s', // key
		    '%s', // identifier
		    '%d', // quantity
		    '%f', // price
		    '%f', // wholesale_price
		    '%s', // price_operator
		    '%s', // plugin_key
		    '%d', // thing_id
		    '%f'  // sale_price
		);

		if ( ! $instance->getId() ) {
			$id = $this->insert( $data, $format );
			$instance->setId( $id );

			$options = $instance->getOptions();

			$values = "";
			foreach ( $options as $option ) {
				if ( $values ) {
					$values .= ", ({$option->getId()}, {$instance->getId()})";
				} else {
					$values = "({$option->getId()}, {$instance->getId()})";
				}
			}

			//Lets save the options
//			$query = "INSERT into {$this->variationGroupOptionTable} (variacion_opcion_id, variation_group_id) values $values";
//			$this->executeQuery($query);
		} else {
			$this->updateById( $instance->getId(), $data, $format );
		}

		return $instance;
	}

	/**
	 * 
	 * @param int $id
	 * @return void
	 */
	public function delete( $id ) {
		//delete the address
		return parent::delete( $id );
	}

	public function deleteByThingId( $thingId, $pluginKey ) {

		if ( ! $thingId ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Thing ID: is required' );
		}

		if ( ! $pluginKey ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Plugin Key: is required' );
		}

		$query = $this->prepare( "DELETE FROM {$this->tableName} where thing_id=%d AND plugin_key=%s", $thingId, $pluginKey );

		return $this->executeQuery( $query );
	}

	public function deleteByGroupKey( $thingId, $groupKey, $pluginKey ) {
		if ( ! $thingId ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Thing ID: is required' );
		}

		if ( ! $groupKey ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Group Key: is required' );
		}

		if ( ! $pluginKey ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Plugin Key: is required' );
		}

		$query = $this->prepare( "DELETE FROM {$this->tableName} where thing_id=%d AND group_key=%s AND plugin_key=%s", $thingId, $groupKey, $pluginKey );

		return $this->executeQuery( $query );
	}

	/**
	 * Delete variation goups "NOT" in the group keys array
	 * 
	 * @param int $thingId
	 * @param string $pluginKey
	 * @param array $groupKeys
	 * @return int
	 */
	public function deleteMissingGroupKeys( $thingId, $pluginKey, $groupKeys = array() ) {

		if ( ! $thingId ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Thing ID: is required' );
		}

		if ( ! $pluginKey ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Plugin Key: is required' );
		}

		if ( empty( $groupKeys ) ) {
			//Use  empty string id to delete everithing
			$groupKeys[] = "";
		}
		
		$escapedKeys = array();
		foreach ( $groupKeys as $key ) {
			$escapedKeys[] = $this->prepare( '%s', $key );
		}

		$items = implode( ',', $escapedKeys );

		$query = $this->prepare( "DELETE FROM {$this->tableName} where thing_id=%d AND group_key NOT IN ({$items}) AND plugin_key=%s", $thingId, $pluginKey );

		return $this->executeQuery( $query );
	}

}
