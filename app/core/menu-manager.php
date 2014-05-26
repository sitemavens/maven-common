<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class MenuManager {

	private $mainSlug = "";

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
	private $menues = array();

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
	public function __construct ( $hookManager, $actionController, $registry ) {

		$this->hookManager = $hookManager;
		$this->actionController = $actionController;
		$this->registry = $registry;

		$this->addAdminMenu( array( $this, 'registerMenues' ) );
		$this->hookManager->addAction( 'admin_enqueue_scripts', array( $this, 'registerStyles' ), 10, 1 );

		$this->hookManager->addAction( 'admin_enqueue_scripts', array( $this, 'registerScripts' ), 10, 1 );
	}

	/**
	 * admin_menu hook
	 * @param type $function
	 * @param type $priority
	 * @param type $acceptedArgs 
	 */
	public function addAdminMenu ( $function, $priority = 10, $acceptedArgs = 1 ) {

		$this->hookManager->addAction( 'admin_menu', $function, $priority, $acceptedArgs );
	}

	/**
	 * 
	 * @param string $pageTitle
	 * @param string $menuTitle
	 * @param string $icon
	 * @param string $capability
	 */
	public function addMenu ( $pageTitle, $menuTitle, $icon = "", $capability = "manage_options" ) {

		$this->menues[] = new Menu( $pageTitle, $menuTitle, $icon, $capability );
	}

	public function addSubMenu ( $pageTitle, $menuTitle, $slug, $capability = "manage_options" ) {

		$this->menues[] = new Menu( $pageTitle, $menuTitle, "", $capability, $this->registry->getPluginKey(), $slug );
	}

	public function registerMenues () {

		if ( !$this->actionController ) {
			throw new \Exception( "No action controller defined" );
		}

		foreach ( $this->menues as $menu ) {

			if ( !$menu->getParent() ) {
				if ( !$this->mainSlug ) {
					$this->mainSlug = add_menu_page( $menu->getPageTitle(), $menu->getMenuTitle(), 'manage_options', $this->registry->getPluginKey(), array( $this, 'showApp' ), $menu->getIcon() );
				}else{
					add_menu_page( $menu->getPageTitle(), $menu->getMenuTitle(), 'manage_options', $this->registry->getPluginKey(), array( $this, 'showApp' ), $menu->getIcon() );
				}
			} else {
				add_submenu_page( $menu->getParent(), $menu->getPageTitle(), $menu->getMenuTitle(), $menu->getCapability(), $this->registry->getPluginKey() . "#" . $menu->getSlug(), array( $this, 'showApp' ) );
			}
		}
	}

	function registerScripts ( $hook ) {
		global $post;
		//var_dump( $hook );
		if ( $hook == $this->mainSlug ) {

			$mavenRegistry = \Maven\Settings\MavenRegistry::instance();
			$registry = $this->registry;
			
			if ( $mavenRegistry->isDevEnv() ) {
				wp_enqueue_script( 'angular', $registry->getBowerComponentUrl() . "angular/angular.js", 'jquery', $registry->getPluginVersion() );
				wp_enqueue_script( 'bootstrap', $registry->getBowerComponentUrl() . "bootstrap/dist/js/bootstrap.js", 'jquery', $registry->getPluginVersion() );
				wp_enqueue_script( 'angular-resource', $registry->getBowerComponentUrl() . "angular-resource/angular-resource.js", 'angular', $registry->getPluginVersion() );
				wp_enqueue_script( 'angular-cookies', $registry->getBowerComponentUrl() . "angular-cookies/angular-cookies.js", 'angular', $registry->getPluginVersion() );
				wp_enqueue_script( 'angular-sanitize', $registry->getBowerComponentUrl() . "angular-sanitize/angular-sanitize.js", 'angular', $registry->getPluginVersion() );
				wp_enqueue_script( 'angular-route', $registry->getBowerComponentUrl() . "angular-route/angular-route.js", 'angular', $registry->getPluginVersion() );
				wp_enqueue_script( 'angular-bootstrap', $registry->getBowerComponentUrl() . "angular-bootstrap/ui-bootstrap-tpls.js", 'angular', $registry->getPluginVersion() );
				//wp_enqueue_script( 'angular-google-chart', $registry->getBowerComponentUrl() . "angular-google-chart/ng-google-chart.js", 'angular', $registry->getPluginVersion() );

				wp_enqueue_script( 'mavenApp', $registry->getScriptsUrl() . "admin/app.js", 'angular', $registry->getPluginVersion() );

				wp_enqueue_script( 'admin/directives/loading.js', $registry->getScriptsUrl() . "admin/directives/loading.js", 'mavenApp', $registry->getPluginVersion() );

				wp_enqueue_script( 'admin/services/admin-services.js', $registry->getScriptsUrl() . "admin/services/admin-services.js", 'mavenApp', $registry->getPluginVersion() );

				wp_enqueue_script( 'admin/controllers/main-nav.js', $registry->getScriptsUrl() . "admin/controllers/main-nav.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/controllers/dashboard.js', $registry->getScriptsUrl() . "admin/controllers/dashboard.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/controllers/settings.js', $registry->getScriptsUrl() . "admin/controllers/settings.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/controllers/taxes/taxes.js', $registry->getScriptsUrl() . "admin/controllers/taxes/taxes.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/controllers/taxes/taxes-edit.js', $registry->getScriptsUrl() . "admin/controllers/taxes/taxes-edit.js", 'mavenApp', $registry->getPluginVersion() );
			}
		}
	}

	function registerStyles ( $hook ) {
		if ( $hook == $this->mainSlug ) {
			$mavenRegistry = \Maven\Settings\MavenRegistry::instance();
			$registry = $this->registry;

			if ( $mavenRegistry->isDevEnv() ) {
				wp_enqueue_style( 'bootstrap', $registry->getBowerComponentUrl() . "bootstrap/dist/css/bootstrap.css", null, $registry->getPluginVersion() );
				wp_enqueue_style( 'bootstrap-theme', $registry->getBowerComponentUrl() . "bootstrap/dist/css/bootstrap-theme.css", null, $registry->getPluginVersion() );

				wp_enqueue_style( 'main', $registry->getStylesUrl() . "main.css", array( 'bootstrap', 'bootstrap-theme' ), $registry->getPluginVersion() );
			} else {
				wp_enqueue_style( 'mainCss', $registry->getStylesUrl() . "main.min.css", array(), $registry->getPluginVersion() );
			}
		}
	}

	public function showApp () {

		$output = new Ui\Output( $this->registry->getPluginDir() );
		echo $output->getAdminView( "admin" );
	}

	/**
	 * 
	 * @param type $component
	 * @param string $group
	 * @param string $icon
	 */
	public function registerMenu ( $component, $group = false, $icon = "" ) {

		if ( $group ) {
			$this->addMenu( $group, $group, $icon );
		}

		$this->addSubMenu( $component->getTitle(), $component->getTitle(), $component->getSlug() );
	}

}

class Menu {

	private $pageTitle;
	private $menuTitle;
	private $capability;
	private $slug;
	private $parent;
	private $icon;

	public function __construct ( $pageTitle, $menuTitle, $icon, $capability, $parent = null, $slug = null ) {

		$this->pageTitle = $pageTitle;
		$this->menuTitle = $menuTitle;
		$this->capability = $capability;
		$this->parent = $parent;
		$this->slug = $slug;
		$this->icon = $icon;
	}

	public function getParent () {
		return $this->parent;
	}

	public function setParent ( $value ) {
		$this->parent = $value;
	}

	public function getCapability () {
		return $this->capability;
	}

	public function setCapability ( $value ) {
		$this->capability = $value;
	}

	public function getMenuTitle () {
		return $this->menuTitle;
	}

	public function setMenuTitle ( $value ) {
		$this->menuTitle = $value;
	}

	public function getPageTitle () {
		return $this->pageTitle;
	}

	public function setPageTitle ( $value ) {
		$this->pageTitle = $value;
	}

	public function getSlug () {
		return $this->slug;
	}

	public function setSlug ( $value ) {
		$this->slug = $value;
	}

	public function getIcon () {
		return $this->icon;
	}

	public function setIcon ( $icon ) {
		$this->icon = $icon;
	}

}
