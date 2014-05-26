<?php

namespace Maven\Admin;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Main {

	public static function init() {


		$adminController = new Controllers\AdminController();
		$adminController->init();
	}

	public static function registerStyles() {

		$registry = \GFSeoMarketingAddOn\Core\Settings\GfRegistry::instance();

		if ( $registry->isDevEnv() ) {
			wp_enqueue_style( 'bootstrap', $registry->getBowerComponentUrl() . "bootstrap/dist/css/bootstrap.css", null, $registry->getPluginVersion() );
			wp_enqueue_style( 'bootstrap-theme', $registry->getBowerComponentUrl() . "bootstrap/dist/css/bootstrap-theme.css", null, $registry->getPluginVersion() );

			wp_enqueue_style( 'main', $registry->getStylesUrl() . "main.css", array( 'bootstrap', 'bootstrap-theme' ), $registry->getPluginVersion() );
		} else {
			wp_enqueue_style( 'mainCss', $registry->getStylesUrl() . "main.min.css", array(), $registry->getPluginVersion() );
		}
	}

	public static function registerScripts() {

		$registry = \GFSeoMarketingAddOn\Core\Settings\GfRegistry::instance();

		if ( $registry->isDevEnv() ) {
			wp_enqueue_script( 'angular', $registry->getBowerComponentUrl() . "angular/angular.js", 'jquery', $registry->getPluginVersion() );
			wp_enqueue_script( 'bootstrap', $registry->getBowerComponentUrl() . "bootstrap/dist/js/bootstrap.js", 'jquery', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-resource', $registry->getBowerComponentUrl() . "angular-resource/angular-resource.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-cookies', $registry->getBowerComponentUrl() . "angular-cookies/angular-cookies.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-sanitize', $registry->getBowerComponentUrl() . "angular-sanitize/angular-sanitize.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-route', $registry->getBowerComponentUrl() . "angular-route/angular-route.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-bootstrap', $registry->getBowerComponentUrl() . "angular-bootstrap/ui-bootstrap-tpls.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-google-chart', $registry->getBowerComponentUrl() . "angular-google-chart/ng-google-chart.js", 'angular', $registry->getPluginVersion() );

			wp_enqueue_script( 'mavenApp', $registry->getScriptsUrl() . "app.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'controllers/main.js', $registry->getScriptsUrl() . "controllers/main.js", 'mavenApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'controllers/main-nav.js', $registry->getScriptsUrl() . "controllers/main-nav.js", 'mavenApp', $registry->getPluginVersion() );
		} else {
			wp_enqueue_script( 'mainApp', $registry->getScriptsUrl() . "main.min.js", 'angular', $registry->getPluginVersion() );
		}

		/*wp_localize_script( 'mavenApp', 'Maven', array(
		    'adminUrl' => admin_url(),
		    'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		    'viewsUrl' => $registry->getViewsUrl()
		) );*/
	}

	public static function registerMenu( $menus ) {

		$menus[] = array( "name" => "gf_ggseomarketing", "label" => "Marketing addon", "callback" => array( __CLASS__, 'showApp' ), "permission" => 'manage_options' );

		return $menus;


		//add_menu_page( 'Marketing Addon', 'GF SEO Marketing Addon', 'manage_options', 'ggseomarketing', array( __CLASS__, 'showApp' ) );
	}

	public static function showApp() {
		$registry = \GFSeoMarketingAddOn\Core\Settings\GfRegistry::instance();


		$fullPath = $registry->getPluginDir() . "index.html";
		$content = \GFSeoMarketingAddOn\Core\Loader::getFileContent( $fullPath );

		echo $content;
	}

}
