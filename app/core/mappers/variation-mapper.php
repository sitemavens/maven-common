<?php

namespace Maven\Core\Mappers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class VariationMapper extends \Maven\Core\Db\WordpressMapper {

	private $registry = null;
	private $pluginKey = "";

	public function __construct( \Maven\Settings\Registry $registry ) {

		parent::__construct( "mvn_variation" );

		$this->registry = $registry;
		$this->pluginKey = $registry->getPluginKey();
	}

	/**
	 * Return an Promotion object
	 * @param int $id
	 * @return \Maven\Core\Domain\Promotion
	 */
	public function get( $id ) {

		$instance = new \Maven\Core\Domain\Variation();

		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );

		$row = $this->getRowById( $id );

		if ( ! $row )
			throw new \Maven\Exceptions\NotFoundException();


		$this->fillObject( $instance, $row );

		return $instance;
	}

	public function getThingVariations( $thingId ) {

		if ( ! $thingId )
			throw new \Maven\Exceptions\RequiredException( 'Thing ID is required' );

		$query = $this->prepare( "SELECT * FROM {$this->tableName} WHERE thing_id = %d and plugin_key = %s", $thingId, $this->pluginKey );

		$rows = $this->getQuery( $query );

		$variations = array();

		if ( $rows ) {

			foreach ( $rows as $row ) {

				$instance = new \Maven\Core\Domain\Variation();

				$this->fillObject( $instance, $row );

				$variations[] = $instance;
			}
		}


		return $variations;
	}

	public function deleteThingVariations( $thingId ) {

		//TODO: We have to improve this method
		// First we need to remove the options
		$query = $this->prepare( "delete from mvn_variation_option 
									where mvn_variation_option.variation_id in 
									(select id from {$this->tableName} where thing_id = %d and plugin_key = %s )", $thingId, $this->pluginKey );

		$this->executeQuery( $query );

		// Delete the groups
		$query = $this->prepare( "delete from mvn_variation_group 
									where  thing_id = %d and plugin_key = %s", $thingId, $this->pluginKey );

		$this->executeQuery( $query );


		// Delete the variations
		$query = $this->prepare( "delete from {$this->tableName} 
									where thing_id = %d and plugin_key = %s", $thingId, $this->pluginKey );


		$this->executeQuery( $query );

		return true;
	}

	public function getVariationsCount( $thingId ) {

		$query = $this->prepare( "SELECT COUNT('e') FROM {$this->tableName} WHERE thing_id = %d", $thingId );

		return $this->getVar( $query );
	}

	public function save( \Maven\Core\Domain\Variation $instance ) {

		if ( ! $this->pluginKey )
			throw new \Maven\Exceptions\MapperException( 'Plugin Key is required' );

		if ( ! $instance->getThingId() )
			throw new \Maven\Exceptions\MapperException( 'You need to associate the variation to a thing.' );


		$instance->sanitize();


		$data = array(
		    'name' => $instance->getName(),
		    'plugin_key' => $this->pluginKey,
		    'thing_id' => $instance->getThingId(),
		);

		$format = array(
		    '%s', // name
		    '%s', // plugin_key
		    '%d', // thing_id
		);

		if ( ! $instance->getId() ) {
			$id = $this->insert( $data, $format );
			$instance->setId( $id );
		} else {
			$this->updateById( $instance->getId(), $data, $format );
		}

		return $instance;
	}

	public function saveMultiple( $variations, $thingId ) {
		$variationGroupMapper = new VariationGroupMapper();

		//we need to know how many variations we have
		$query = $this->prepare( "SELECT COUNT(*) FROM {$this->tableName} WHERE thing_id=%d AND plugin_key=%s", $thingId, $this->pluginKey );
		$count = $this->getVar( $query );

		$existingId = array();
		$variationOptionsMapper = new VariationOptionMapper();
		$thingId = 0;

		if ( $variations ) {

			foreach ( $variations as $variation ) {
				$variation->setPluginKey( $this->pluginKey );

				$variation = $this->save( $variation );

				$variationOptionsMapper->saveMultiple( $variation, $variation->getOptions() );

				$existingId[] = $variation->getId();

				$thingId = $variation->getThingId();
			}
		}

		if ( count( $variations ) == 0 ) {
			$query = $this->prepare( "DELETE FROM {$this->tableName} WHERE thing_id = %d and plugin_key=%s", $thingId, $this->pluginKey );
			$result = $this->executeQuery( $query );

			if ( $result > 0 ) {
				//we have deleted all variations, remove groups
				$variationGroupMapper->deleteByThingId( $thingId, $this->pluginKey );
			}
			return;
		}

		$items = implode( ',', $existingId );

		//Delete the removed items.
		//get the removed ids
		$query = $this->prepare( "delete FROM {$this->tableName} WHERE id NOT IN ({$items}) AND  thing_id=%d and plugin_key=%s", $thingId, $this->pluginKey );

		$deletedVariations = $this->executeQuery( $query );

		$variationOptionsMapper->deleteOptions( $thingId, $items );

		if ( $deletedVariations > 0 || $count != count( $variations ) ) {
			//we have deleted something, or we have added something (or both)
			//delete the groups
			$variationGroupMapper->deleteByThingId( $thingId, $this->pluginKey );
		}

		return $variations;
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

}
