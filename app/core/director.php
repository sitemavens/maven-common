<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Super class that can create any component
 *
 * @author mustela
 */
class Director implements ActionControllerObserver {

	private static $instance;
	private $plugins = array( );

	/**
	 *
	 * @var \Maven\Core\ThemeManager  
	 */
	private $themeManager;
	private $resourceManager;

	/**
	 * 
	 * @return \Maven\Core\Director
	 */
	public function getInstance() {

		if ( !self::$instance )
			self::$instance = new self();

		return self::$instance;
	}

	/**
	 * 
	 * @param \Maven\Settings\Registry $registry
	 * @return \Maven\Core\ThemeManager 
	 */
	public function createThemeManager( \Maven\Settings\Registry $registry ) {

		$this->createResourceManager();

		if ( !$this->themeManager ) {
			$this->themeManager = new \Maven\Core\Ui\ThemeManager( $registry, $this->resourceManager );
		}
		
		

		return $this->themeManager;
	}

	/**
	 * 
	 * @param \Maven\Settings\Registry $registry
	 * @return \Maven\Core\ComponentManager
	 */
	private function createComponentManager( \Maven\Settings\Registry $registry ) {

		$componentManager = new \Maven\Core\ComponentManager( $registry );

		$plugin = $this->getPlugin( $registry->getPluginKey() );

		//Save the component manager for future use
		$plugin->setComponentManager( $componentManager );
	}

	/**
	 * 
	 * @param \Maven\Settings\Registry $registry
	 */
	public function createPluginElements( \Maven\Settings\Registry $registry ) {

		$this->createComponentManager( $registry );
		$this->createHookManager( $registry );
		$this->createMenuManager( $registry );

		// We need to register the plugin so we can configure gateways
		$mavenRegistry = \Maven\Settings\MavenRegistry::instance();

		$mavenRegistry->registerPlugin( $registry );
		
		//Instantiate the updater
		$updater = new PluginUpdater($registry);
		

		//TODO: Hacer mas automatica la carga del installer
//		\Maven\Core\Loader::load($registry->getPluginDir(), 'installer');
//		
//		HookManager::instance()->addActivation($registry->getPluginDir(). 'installer.php');
	}

	private function createResourceManager() {

		$this->resourceManager = new ResourceManager( new \Maven\Core\HookManager() );
	}
	
	

	/**
	 * 
	 * @param string $key
	 * @return \Maven\Core\MavenPlugin
	 */
	private function &getPlugin( $key ) {
		if ( !isset( $this->plugins[ $key ] ) )
			$this->plugins[ $key ] = new MavenPlugin();

		return $this->plugins[ $key ];
	}

	public function update( Component $component ) {

		// It's a maven component, so we need to init the theme
		$this->themeManager->init();
	}

	
	/**
	 * 
	 * @param \Maven\Settings\Registry $registry
	 */
	private function &createMenuManager( \Maven\Settings\Registry $registry ) {

		$plugin = $this->getPlugin( $registry->getPluginKey() );

		$wpActionController = new \Maven\Core\WpActionController( $plugin->getComponentManager(), $plugin->getHookManager(), $this->resourceManager );

		$wpActionController->attach( $this );
		$wpActionController->attach( $this->resourceManager );


		/** Menu Manager * */
		$menuManager = new \Maven\Core\MenuManager(
						$plugin->getHookManager(),
						$wpActionController,
						$registry
		);

		$plugin->setMenuManager( $menuManager );

		return $menuManager;
	}

	private function &createHookManager( \Maven\Settings\Registry $registry ) {

		$plugin = $this->getPlugin( $registry->getPluginKey() );

		$hookManager = \Maven\Core\HookManager::instance();
		$hookManager->setRegistry( $registry );

		$plugin->setHookManager( $hookManager );

		return $hookManager;
	}

	/**
	 * 
	 * @param \Maven\Settings\Registry $registry
	 * @return \Maven\Core\ComponentManager
	 */
	public function getComponentManager( \Maven\Settings\Registry $registry ) {

		$plugin = $this->getPlugin( $registry->getPluginKey() );

		return $plugin->getComponentManager();
	}

	/**
	 * 
	 * @param  \Maven\Settings\Registry $registry
	 * @return \Maven\Core\MenuManager
	 */
	public function getMenuManager( \Maven\Settings\Registry $registry ) {

		$plugin = $this->getPlugin( $registry->getPluginKey() );

		return $plugin->getMenuManager();
	}

	/**
	 * 
	 * @param  \Maven\Settings\Registry $registry
	 * @return  \Maven\Core\HookManager
	 */
	public function getHookManager( \Maven\Settings\Registry $registry ) {

		$plugin = $this->getPlugin( $registry->getPluginKey() );

		return $plugin->getHookManager();
	}

}

class MavenPlugin {

	/**
	 *
	 * @var \Maven\Core\ComponentManager 
	 */
	private $componentManager;

	/**
	 *
	 * @var \Maven\Core\MenuManager 
	 */
	private $menuManager;

	/**
	 *
	 * @var \Maven\Core\Component 
	 */
	private $components = array( );

	/**
	 *
	 * @var \Maven\Settings\Registry 
	 */
	private $registry;

	/**
	 *
	 * @var \Maven\Core\HookManager 
	 */
	private $hookManager;

	public function __construct() {
		;
	}

	/**
	 * 
	 * @return \Maven\Core\ComponentManager
	 */
	public function getComponentManager() {
		return $this->componentManager;
	}

	/**
	 * 
	 * @param \Maven\Core\ComponentManager $value
	 */
	public function setComponentManager( \Maven\Core\ComponentManager $value ) {
		$this->componentManager = $value;
	}

	/**
	 * 
	 * @return \Maven\Core\HookManager
	 */
	public function getHookManager() {
		return $this->hookManager;
	}

	public function setHookManager( \Maven\Core\HookManager $hookManager ) {
		$this->hookManager = $hookManager;
	}

	public function getRegistry() {
		return $this->registry;
	}

	public function setRegistry( $value ) {
		$this->registry = $value;
	}

	public function getComponents() {
		return $this->components;
	}

	public function addComponent( \Maven\Core\Component $component ) {
		$this->components[ ] = $component;
	}

	/**
	 * 
	 * @return \Maven\Core\MenuManager
	 */
	public function getMenuManager() {
		return $this->menuManager;
	}

	/**
	 * 
	 * @param \Maven\Core\MenuManager  $value
	 */
	public function setMenuManager( $value ) {
		$this->menuManager = $value;
	}

}