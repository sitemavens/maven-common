<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class PluginUpdater {
	
	/**
	 *
	 * @param \Maven\Settings\MavenRegistry $registry 
	 */
	public function __construct( \Maven\Settings\Registry $registry ){
		
		
		if ( ! $registry->getPluginDirectoryName() )
			return; 
		
		$updaterSlug = $registry->getPluginDirectoryName()."/".$registry->getPluginDirectoryName().".php";
		
		$license = \Maven\Settings\MavenRegistry::instance()->getLicensePlugin($registry);
		
		// setup the updater
		$updater = new \Maven\Libs\EddSlPluginUpdater( 'http://www.sitemavens.com/', $updaterSlug, array( 
				'version' 	=> $registry->getPluginVersion(), 		// current version number
				'license' 	=> $license, 	// license key (used get_option above to retrieve from DB)
				'item_name'	=> $registry->getPluginName(), 	// name of this plugin
				'author'	=> 'SiteMavens'
			));
		
	}
	
	/**
	 * Activate a license
	 * @param string $license
	 * @param \Maven\Settings\Registry $registry
	 */
	public static function activateLicense( $license, \Maven\Settings\Registry $registry ){
		
		
		if ( ! $registry->getPluginDirectoryName() )
			return false;
		
		// setup the updater
		$updater = new \Maven\Libs\EddSlPluginUpdater( 'http://www.sitemavens.com/', $registry->getPluginDirectoryName(), array( 
				'version' 	=> $registry->getPluginVersion(), 		// current version number
				'license' 	=> $license, 	// license key (used get_option above to retrieve from DB)
				'item_name'	=> $registry->getPluginName(), 	// name of this plugin
				'author'	=> 'SiteMavens'
			));
		
		
		return $updater->activateLicense();
		
	}
	
} 