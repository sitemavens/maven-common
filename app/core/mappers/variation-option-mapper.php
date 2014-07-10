<?php

namespace Maven\Core\Mappers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class VariationOptionMapper extends \Maven\Core\Db\WordpressMapper {

	public function __construct() {

		parent::__construct( "mvn_variation_option" );
	}

	/**
	 * Return an Promotion object
	 * @param int $id
	 * @return \Maven\Core\Domain\Promotion
	 */
	public function get( $id ) {

		$instance = new \Maven\Core\Domain\VariationOption();

		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );

		$row = $this->getRowById( $id );

		if ( ! $row )
			throw new \Maven\Exceptions\NotFoundException();


		$this->fillObject( $instance, $row );

		return $instance;
	}

	public function getOptions( $variationId ) {

		$rows = $this->getResultsBy( "variation_id", $variationId );

		$options = array();

		if ( ! $rows )
			return $options;

		foreach ( $rows as $row ) {

			$instance = new \Maven\Core\Domain\VariationOption();

			$this->fillObject( $instance, $row );

			$options[] = $instance;
		}

		return $options;
	}

	public function save( \Maven\Core\Domain\VariationOption $instance ) {

		$instance->sanitize();

		$data = array(
		    'name' => $instance->getName(),
		    'variation_id' => $instance->getVariationId(),
		);


		$format = array(
		    '%s', // name
		    '%d' // variationId
		);

		if ( ! $instance->getId() ) {
			$id = $this->insert( $data, $format );
			$instance->setId( $id );
		} else {
			$this->updateById( $instance->getId(), $data, $format );
		}

		return $instance;
	}

	public function saveMultiple( $variation, $options ) {


		$existingId = array();

		if ( $options ) {

			foreach ( $options as $option ) {

				$option->setVariationId( $variation->getId() );
				$option = $this->save( $option );

				$existingId[] = $option->getId();
			}
		}

		if ( count( $options ) == 0 ) {
			$query = $this->prepare( "DELETE FROM {$this->tableName} WHERE variation_id = %d", $variation->getId() );
			return;
		}

		$items = implode( ',', $existingId );

		//Delete the removed items.
		$query = $this->prepare( "DELETE FROM {$this->tableName} WHERE id NOT IN ({$items}) AND variation_id = %d", $variation->getId() );


		$this->executeQuery( $query );
	}

	public function deleteOptions( $thingId, $variationsIds ) {

		//Delete the removed items.
		$query = $this->prepare( "DELETE  {$this->tableName} FROM {$this->tableName} inner join mvn_variation on mvn_variation.id = mvn_variation_option.variation_id WHERE variation_id NOT IN ({$variationsIds}) AND thing_id = %d", $thingId );
		//var_dump($query);
		//die();
		$this->executeQuery( $query );
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

	/**
	 * Delete options "NOT" in the options Id array
	 * 
	 * @param int $thingId
	 * @param string $pluginKey
	 * @param array $optionsId
	 * @return int
	 */
	public function deleteMissingOptions( $thingId, $pluginKey, $optionsId = array() ) {

		if ( ! $thingId ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Thing ID: is required' );
		}

		if ( ! $pluginKey ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Plugin Key: is required' );
		}

		if ( empty( $optionsId ) ) {
			//Use -1 id to delete everithing
			$optionsId[] = -1;
		}

		$escOpt = array();
		foreach ( $optionsId as $key ) {
			$escOpt[] = $this->prepare( '%d', $key );
		}

		$options = implode( ',', $escOpt );

		//TODO: Prepare query to delete the correct rows
		$query = $this->prepare( "DELETE opt 
			FROM  {$this->tableName} opt
			INNER JOIN mvn_variation var ON opt.variation_id = var.id
			WHERE plugin_key =  %s
				AND thing_id =%d
				AND opt.id NOT IN ( {$options} ) "
			, $pluginKey, $thingId );

		return $this->executeQuery( $query );
	}

}
