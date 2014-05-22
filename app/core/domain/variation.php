<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class Variation extends \Maven\Core\DomainObject {

	private $name;
	private $pluginKey;
	private $thingId;
	
	/**
	 *
	 * @var \Maven\Core\Domain\VariationGroup[] 
	 */
	private $groups = array();
	
	
	/**
	 *
	 * @var \Maven\Core\Domain\VariationOption[] 
	 */
	private $options = array();
	
	public function __construct( $id = false ) {
		 
		parent::__construct( $id );
		
		$rules = array(
			
			'name'			=> \Maven\Core\SanitizationRule::Text,
			'pluginKey'		=> \Maven\Core\SanitizationRule::Key,
			'thingId'		=> \Maven\Core\SanitizationRule::Integer
			
		);
	
		$this->setSanitizationRules( $rules );
	}
	
	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}


	/**
	 * @collectionType: \Maven\Core\Domain\VariationOption
	 * @return \Maven\Core\Domain\VariationOption[]
	 */
	public function getOptions() {
		return $this->options;
	}
	
	/**
	 * 
	 * @param int $id
	 * @return \Maven\Core\Domain\VariationOption
	 */
	public function getOption( $id ) {
		
		foreach ( $this->options as $option ) {
			if ( $option->getId() == $id )
				return $option;
		}

		return false;
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\VariationOption[] $options
	 */
	public function setOptions( $options ) {
		$this->options = $options;
	}
	
	public function hasOptions(){
		return $this->options && count( $this->options ) > 0;
	}
	
	public function addOption( $name){
		
		$option = new \Maven\Core\Domain\VariationOption();
		$option->setName($name);
		
		$this->options[] = $option;
		
	}
	
	public function getPluginKey() {
		return $this->pluginKey;
	}

	public function setPluginKey( $pluginKey ) {
		$this->pluginKey = $pluginKey;
	}

	public function getThingId() {
		return $this->thingId;
	}

	public function setThingId( $thingId ) {
		$this->thingId = $thingId;
	}

	/**
	 * @collectionType: \Maven\Core\Domain\VariationGroup
	 * @return \Maven\Core\Domain\VariationGroup[]
	 */
	public function getGroups() {
		return $this->groups;
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\VariationGroup[] $groups
	 */
	public function setGroups(  $groups ) {
		
		foreach ( $groups as $group ) {
			$this->groups[ $group->getGroupKey() ] = $group;
		}
	}
	
	
	public function hasGroups(){
		return $this->groups && count($this->groups)> 0;
	}

	/**
	 * 
	 * @param string $key
	 * @return \Maven\Core\Domain\VariationGroup
	 */
	public function getGroup( $key ){
		
		// We need to ensure that the key is sorted
		$keyIds = explode( "-", $key); 
		asort( $keyIds ); 
		$keyIds = implode( "-", $keyIds );
		
		
		if ( isset( $this->groups[$keyIds] ) )
			return $this->groups[$keyIds];
		
		
		return new VariationGroup();
		
	}


}
