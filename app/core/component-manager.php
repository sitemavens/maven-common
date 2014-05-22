<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Description of ComponentManager
 *
 * @author mustela
 */
class ComponentManager {
	
	private $components = array();
	
	private $componentsByKey = array();
	
	/**
	 * 
	 * @var \Maven\Settings\Registry 
	 */
	private $registry;

	
	/**
	 *
	 * @param \Maven\\Settings\Registry $registry
	 */
	public function __construct( $registry ) {
		
		$this->registry = $registry;
		
	}
	
	
	/**
	 * Create a component
	 * 
	 * @param string $title
	 * @param string $group
	 * @return \Maven\Core\Component 
	 */
	public function createComponent( $title, $type, $isAdmin = true ){
		

		$component = new Component( $isAdmin );
		
		$component->setRegistry( $this->registry );
		
		$component->setType( $type );
		
		// This is used to search the component in the list, specially for ajax. 
		// We need to ensure that the "one" who is calling an action is a valid component
		$component->setKey( hash('md5', $type) );
		
		$slug = strtolower( $title );
		
		// We need to get the 5 first letters of the title to use as a slug. 
		// Remember WP let you use til 10
		if ( strlen( $title )> 5)
			$slug = strtolower( substr( $title, 0,5 ) );
		
		$component->setTitle( $title );
		$component->setSlug ( $this->registry->concatShortName ( $slug ) );
		
		
		// Register the component
		$this->components[ $component->getSlug() ] = $component;
		
		// Register the component using the key, it's just to have a faster way to search a component
		$this->componentsByKey[ $component->getKey() ] = &$component;
		
		
		return $component;
	}
	
	/**
	 * Create a component
	 * @param string $title
	 * @param string $group
	 * @return \Maven\Core\Component 
	 */
	public function createSettingsComponent( $title ){
		
		$component = new Component();
		
		$slug = "";
		// We need to get the 5 first letters of the title to use as a slug. 
		// Remember WP let you use til 10
		if ( strlen( $title )> 5)
			$slug = strtolower( substr( $title, 0,5 ) );
		
		$component->setTitle( $title );
		$component->setSlug ( $this->registry->concatShortName ( $slug ) );
		
		$component->setType( '\\Maven\\Core\Ui\\SettingsController' );
		$component->setDefaultAction( 'showForm' );
		$component->addAction( 'save' );
		
		//We need to tell the SettingManager what registry have to use.
		$component->setRegistry( $this->registry );
		
		// Register the component
		$this->components[ $component->getSlug() ] = &$component;
		
		
		
		return $component;
	}
	
	
	
	
	/**
	 * Get a component by slug
	 * @param string $slug/key
	 * @return \Maven\Core\Component
	 */
	public function getComponent( $slug ){
		
		if ( ! $slug ) return null;
		
		if ( isset( $this->components[$slug] ) )
			return $this->components[$slug];
		
		if ( isset( $this->componentsByKey[$slug] ) )
			return $this->componentsByKey[$slug];
		
		return null;
	}
	
	public  function printComponents(){
		print_r(self::$components);
	}
	
}
