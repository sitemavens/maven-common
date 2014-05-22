<?php

namespace Maven\Front;

class ProfileFrontEnd {

	public function __construct() {
		
	}

	public static function registerFrontEndHooks() {
		$frontEnd = new ProfileFrontEnd();
		$hookManager = \Maven\Core\HookManager::instance();

		$hookManager->addEnqueueScripts( array( $frontEnd, 'registerScript' ) );
	}

	public function registerScript() {

		wp_enqueue_script( 'mavenCommonJS', \Maven\Settings\MavenRegistry::instance()->getAssetsUrl() . "js/maven-common.js", array( 'jquery' ), \Maven\Settings\MavenRegistry::instance()->getPluginVersion() );
	}

}
