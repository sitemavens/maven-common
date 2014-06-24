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

			if ( $registry->isDevEnv() ) {
				wp_enqueue_style( 'main.css', $registry->getStylesUrl() . "main.css", array(), $registry->getPluginVersion() );

				wp_enqueue_script( 'mavenApp', $registry->getScriptsUrl() . "admin/app.js", 'angular', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/global/directives/loading.js', $registry->getScriptsUrl() . "admin/global/directives/loading.js", 'mavenApp', $registry->getPluginVersion() );
//			wp_enqueue_script( 'admin/services/admin-services.js', $registry->getScriptsUrl() . "admin/services/admin-services.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/global/controllers/main-nav.js', $registry->getScriptsUrl() . "admin/global/controllers/main-nav.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/dashboard/controllers/main.js', $registry->getScriptsUrl() . "admin/dashboard/controllers/main.js", 'mavenApp', $registry->getPluginVersion() );

				wp_enqueue_script( 'admin/settings/controllers/main.js', $registry->getScriptsUrl() . "admin/settings/controllers/main.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/settings/services/gateway.js', $registry->getScriptsUrl() . "admin/settings/services/gateway.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/settings/services/setting.js', $registry->getScriptsUrl() . "admin/settings/services/setting.js", 'mavenApp', $registry->getPluginVersion() );

				wp_enqueue_script( 'admin/taxes/controllers/list.js', $registry->getScriptsUrl() . "admin/taxes/controllers/list.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/taxes/controllers/edit.js', $registry->getScriptsUrl() . "admin/taxes/controllers/edit.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/taxes/services/tax.js', $registry->getScriptsUrl() . "admin/taxes/services/tax.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/taxes/services/taxes-filter.js', $registry->getScriptsUrl() . "admin/taxes/services/taxes-filter.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/taxes/services/taxes.js', $registry->getScriptsUrl() . "admin/taxes/services/taxes.js", 'mavenApp', $registry->getPluginVersion() );

				wp_enqueue_script( 'admin/promotions/controllers/list.js', $registry->getScriptsUrl() . "admin/promotions/controllers/list.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/promotions/controllers/edit.js', $registry->getScriptsUrl() . "admin/promotions/controllers/edit.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/promotions/services/promotion.js', $registry->getScriptsUrl() . "admin/promotions/services/promotion.js", 'mavenApp', $registry->getPluginVersion() );


				wp_enqueue_script( 'admin/roles/controllers/list.js', $registry->getScriptsUrl() . "admin/roles/controllers/list.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/roles/controllers/edit.js', $registry->getScriptsUrl() . "admin/roles/controllers/edit.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/roles/services/rol.js', $registry->getScriptsUrl() . "admin/roles/services/rol.js", 'mavenApp', $registry->getPluginVersion() );

				wp_enqueue_script( 'admin/attributes/controllers/list.js', $registry->getScriptsUrl() . "admin/attributes/controllers/list.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/attributes/controllers/edit.js', $registry->getScriptsUrl() . "admin/attributes/controllers/edit.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/attributes/services/attribute.js', $registry->getScriptsUrl() . "admin/attributes/services/attribute.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/attributes/services/attribute-filter.js', $registry->getScriptsUrl() . "admin/attributes/services/attribute-filter.js", 'mavenApp', $registry->getPluginVersion() );


				wp_enqueue_script( 'admin/profiles/controllers/list.js', $registry->getScriptsUrl() . "admin/profiles/controllers/list.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/profiles/controllers/edit.js', $registry->getScriptsUrl() . "admin/profiles/controllers/edit.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/profiles/services/profile.js', $registry->getScriptsUrl() . "admin/profiles/services/profile.js", 'mavenApp', $registry->getPluginVersion() );

				wp_enqueue_script( 'admin/orders/controllers/list.js', $registry->getScriptsUrl() . "admin/orders/controllers/list.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/orders/controllers/edit.js', $registry->getScriptsUrl() . "admin/orders/controllers/edit.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/orders/services/order-filter.js', $registry->getScriptsUrl() . "admin/orders/services/order-filter.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/orders/services/order-loader.js', $registry->getScriptsUrl() . "admin/orders/services/order-loader.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/orders/services/order.js', $registry->getScriptsUrl() . "admin/orders/services/order.js", 'mavenApp', $registry->getPluginVersion() );


				wp_enqueue_script( 'admin/https/controllers/main.js', $registry->getScriptsUrl() . "admin/https/controllers/main.js", 'mavenApp', $registry->getPluginVersion() );
				wp_enqueue_script( 'admin/https/services/https.js', $registry->getScriptsUrl() . "admin/https/services/https.js", 'mavenApp', $registry->getPluginVersion() );
			}else{
				wp_enqueue_style( 'mainCss', $registry->getStylesUrl() . "main.min.css", array(), $registry->getPluginVersion() );
				wp_enqueue_script( 'mainApp', $registry->getScriptsUrl() . "main.min.js", 'angular', $registry->getPluginVersion() );
			}
		}
	}

	public function registerRouters () {


		foreach ( $this->classes as $class ) {
			\Maven\Core\HookManager::instance()->addFilter( 'json_endpoints', array( $class, 'registerRoutes' ) );
		}
	}

}
