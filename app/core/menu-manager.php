<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class MenuManager{
	
	
	/**
	 *
	 * @var \Maven\Core\ActionController
	 */
	private $actionController;
	
	/**
	 * 
	 * @var \Maven\Settings\Registry  
	 */
	private $registry;
	
	/**
	 * 
	 * @var \Maven\Core\Menu[] 
	 */
	private $menues  = array();
	
	/**
	 * 
	 * @var \Maven\Core\HookManager 
	 */
	private $hookManager;
	
	
	
	/**
	 *
	 * @param \Maven\Core\HookManager  $hookManager
	 * @param \Maven\Core\ActionController $actionController
	 * @param \Maven\Settings\Registry $registry 
	 */
	public function __construct( $hookManager, $actionController, $registry){
		
		$this->hookManager		= $hookManager;
		$this->actionController		= $actionController;
		$this->registry			= $registry;
		
		$this->addAdminMenu( array( &$this,'registerMenues' ) );
		
	}
	
	
	/**
	 * admin_menu hook
	 * @param type $function
	 * @param type $priority
	 * @param type $acceptedArgs 
	 */
	public function addAdminMenu( $function, $priority= 10, $acceptedArgs = 1 ){
		
		$this->hookManager->addAction('admin_menu',$function, $priority, $acceptedArgs );
	}
	
	
	/**
	 * 
	 * @param string $pageTitle
	 * @param string $menuTitle
	 * @param string $icon
	 * @param string $capability
	 */
	public function addMenu( $pageTitle, $menuTitle, $icon = "", $capability = "manage_options"){
		
		$this->menues[] = new Menu( $pageTitle, $menuTitle, $icon, $capability );
	
	}
	
	public function addSubMenu( $pageTitle, $menuTitle, $slug, $capability = "manage_options"  ){
		
		$this->menues[] = new Menu( $pageTitle, $menuTitle, "", $capability, $this->registry->getPluginKey(), $slug );
	}
	
	public function registerMenues(){
		
		if ( ! $this->actionController )
			throw new \Exception("No action controller defined");
		
		
		foreach( $this->menues as &$menu ){
			
			if ( ! $menu->getParent() )
				add_menu_page( $menu->getPageTitle(), $menu->getMenuTitle(), null, $this->registry->getPluginKey(), array( &$this->actionController, 'handleRequest' ), $menu->getIcon() ) ;
			else
				add_submenu_page ($menu->getParent(), $menu->getPageTitle(), $menu->getMenuTitle(), $menu->getCapability(),  $menu->getSlug() , array( &$this->actionController, 'handleRequest' ) );
			
		}
	}
	
	public function registerMenu( $component, $group = false, $icon = ""  ){
				
		if ( $group )
			$this->addMenu( $group, $group, $icon );


		$this->addSubMenu( $component->getTitle(), $component->getTitle(), $component->getSlug() );
	
	}
	
}

class Menu{
	
	private $pageTitle;
	private $menuTitle;
	private $capability;
	private $slug;
	private $parent;
	private $icon;

	public function __construct( $pageTitle, $menuTitle, $icon, $capability, $parent = null, $slug = null ){
		
		$this->pageTitle  = $pageTitle;
		$this->menuTitle  = $menuTitle;
		$this->capability = $capability;
		$this->parent	  = $parent;
		$this->slug		  = $slug;
		$this->icon		  = $icon;
		
	}
	
	public function getParent() 
	{
		return $this->parent;
	}

	public function setParent($value) 
	{
		$this->parent = $value;
	}
	
	public function getCapability() 
	{
		return $this->capability;
	}

	public function setCapability($value) 
	{
		$this->capability = $value;
	}
	
	public function getMenuTitle() 
	{
		return $this->menuTitle;
	}

	public function setMenuTitle($value) 
	{
		$this->menuTitle = $value;
	}
	
	public function getPageTitle() 
	{
		return $this->pageTitle;
	}

	public function setPageTitle($value) 
	{
		$this->pageTitle = $value;
	}
			
	public function getSlug() 
	{
		return $this->slug;
	}

	public function setSlug( $value ) 
	{
		$this->slug = $value;
	}
		
	
	public function getIcon() {
		return $this->icon;
	}

	public function setIcon( $icon ) {
		$this->icon = $icon;
	}


	
}