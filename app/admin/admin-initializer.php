<?php

namespace Maven\Admin;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class AdminInitializer {

	private $classes = array();

	public function __construct () {

		\Maven\Core\HookManager::instance()->addAction( 'admin_enqueue_scripts', array( $this, 'registerScripts' ), 10, 1 );

		$registry = \Maven\Settings\MavenRegistry::instance();
		$this->classes[ 'settings' ] = new Controllers\Settings();
		$this->classes[ 'taxes' ] = new Controllers\Taxes();
		$this->classes[ 'roles' ] = new Controllers\Roles();
		$this->classes[ 'profiles' ] = new Controllers\Profiles();
		$this->classes[ 'orders' ] = new Controllers\Orders();
		$this->classes[ 'promotions' ] = new Controllers\Promotions();
		$this->classes[ 'attributes' ] = new Controllers\Attributes();
		$this->classes[ 'https' ] = new Controllers\Https();

		foreach ( $this->classes as $class ) {
			if ( $class instanceof \Maven\Core\Interfaces\iView ) {
				\Maven\Core\HookManager::instance()->addFilter( "maven/views/get/{$registry->getPluginKey()}", array( $class, 'getView' ) );
			}
		}
	}

	public function registerScripts ( $hook ) {

		$registry = \Maven\Settings\MavenRegistry::instance();

		if ( $hook == 'toplevel_page_maven' ) {
			wp_enqueue_style( 'main.css', $registry->getStylesUrl() . "main.css", array(), $registry->getPluginVersion() );

			wp_enqueue_script( 'mavenApp', $registry->getScriptsUrl() . "admin/app.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/directives/loading.js', $registry->getScriptsUrl() . "admin/directives/loading.js", 'mavenApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/services/admin-services.js', $registry->getScriptsUrl() . "admin/services/admin-services.js", 'mavenApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/controllers/main-nav.js', $registry->getScriptsUrl() . "admin/controllers/main-nav.js", 'mavenApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/controllers/dashboard.js', $registry->getScriptsUrl() . "admin/controllers/dashboard.js", 'mavenApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/controllers/settings.js', $registry->getScriptsUrl() . "admin/controllers/settings.js", 'mavenApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/controllers/taxes/taxes.js', $registry->getScriptsUrl() . "admin/controllers/taxes/taxes.js", 'mavenApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/controllers/taxes/taxes-edit.js', $registry->getScriptsUrl() . "admin/controllers/taxes/taxes-edit.js", 'mavenApp', $registry->getPluginVersion() );

			wp_enqueue_script( 'admin/controllers/promotions/promotions.js', $registry->getScriptsUrl() . "admin/controllers/promotions/promotions.js", 'mavenApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/controllers/promotions/promotions-edit.js', $registry->getScriptsUrl() . "admin/controllers/promotions/promotions-edit.js", 'mavenApp', $registry->getPluginVersion() );


			wp_enqueue_script( 'admin/controllers/roles/roles.js', $registry->getScriptsUrl() . "admin/controllers/roles/roles.js", 'mavenApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/controllers/roles/roles-edit.js', $registry->getScriptsUrl() . "admin/controllers/roles/roles-edit.js", 'mavenApp', $registry->getPluginVersion() );

			wp_enqueue_script( 'admin/controllers/attributes/attributes.js', $registry->getScriptsUrl() . "admin/controllers/attributes/attributes.js", 'mavenApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/controllers/attributes/attributes-edit.js', $registry->getScriptsUrl() . "admin/controllers/attributes/attributes-edit.js", 'mavenApp', $registry->getPluginVersion() );

			wp_enqueue_script( 'admin/controllers/profiles/profiles.js', $registry->getScriptsUrl() . "admin/controllers/profiles/profiles.js", 'mavenApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/controllers/profiles/profiles-edit.js', $registry->getScriptsUrl() . "admin/controllers/profiles/profiles-edit.js", 'mavenApp', $registry->getPluginVersion() );

			wp_enqueue_script( 'admin/controllers/orders/orders.js', $registry->getScriptsUrl() . "admin/controllers/orders/orders.js", 'mavenApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/controllers/orders/orders-edit.js', $registry->getScriptsUrl() . "admin/controllers/orders/orders-edit.js", 'mavenApp', $registry->getPluginVersion() );

			wp_enqueue_script( 'admin/controllers/https/https.js', $registry->getScriptsUrl() . "admin/controllers/https/https.js", 'mavenApp', $registry->getPluginVersion() );
		}
	}

	public function registerRouters () {


		foreach ( $this->classes as $class ) {
			\Maven\Core\HookManager::instance()->addFilter( 'json_endpoints', array( $class, 'registerRoutes' ) );
		}
	}

}
