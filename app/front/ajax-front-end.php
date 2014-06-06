<?php

namespace Maven\Front;

class AjaxFrontEnd {

	public function __construct() {
		
	}

	public static function registerFrontEndHooks() {

//		$hookManager = \Maven\Core\HookManager::instance();
//		$hookManager->addEnqueueScripts( array( '\Maven\Front\AjaxFrontEnd', 'registerScript' ) );
//		
//		
//		$hookManager->addAjaxAction( 'mavenAjaxCountry', array( '\Maven\Front\AjaxFrontEnd', 'countryHandler' ) );
//		$hookManager->addPublicAjaxAction( 'mavenAjaxCountry', array( '\Maven\Front\AjaxFrontEnd', 'countryHandler' ) );
	}

	public function registerScript() {

		//wp_enqueue_script( 'mavenCommonJS', \Maven\Settings\MavenRegistry::instance()->getAssetsUrl() . "js/maven-common.js", array( 'jquery' ), \Maven\Settings\MavenRegistry::instance()->getPluginVersion() );
	}
	
	public static function countryHandler(){
		
		$request = \Maven\Core\Request::current();
		
		if ($request->isDoingAjax()){
			$countryManager = new \Maven\Core\CountryManager();
			
			$method = $request->getProperty('method');
			switch( $method ){
				case "getStates":
					$country = $request->getProperty('country');
					$states = $countryManager->getStates($country);
					$result = array('successful'=>true, 'error' => false, 'description'=>$states);
					die(json_encode($result));
					break;
				default:
					die('Invalid method: '.$method);
			}
			
		}
	}

}
