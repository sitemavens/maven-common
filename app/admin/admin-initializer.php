<?php

namespace Maven\Admin;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class AdminInitializer {

	public function __construct () {

		
		\Maven\Core\HookManager::instance()->addAction( 'admin_enqueue_scripts', array( $this, 'registerScripts' ), 10, 1 );
	}

	public function registerScripts ( $hook ) {

		$registry = \Maven\Settings\MavenRegistry::instance();
		 
		if ( $hook == 'toplevel_page_mavencommon' ) {
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

	static function registerRouters () {

		$taxes = new Controllers\Taxes();
		\Maven\Core\HookManager::instance()->addFilter( 'json_endpoints', array( $taxes, 'registerRoutes' ) );
	}

}
