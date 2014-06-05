<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class Settings extends MavenAdminController {

	public function __construct() {
		parent::__construct();
	}
 
	public function registerRoutes( $routes ){
		
		$routes[ '/maven/settings' ] = array(
			array( array( $this, 'getSettings' ), \WP_JSON_Server::READABLE )
		);
		
		$routes[ '/maven/settings/(?P<id>\d+)' ] = array(
			array( array( $this, 'getSettings' ), \WP_JSON_Server::READABLE ),
			array( array( $this, 'edit' ), \WP_JSON_Server::EDITABLE | \WP_JSON_Server::ACCEPT_JSON ),
		);
		
		 
		return $routes;
	}
	
	public function edit ( $id, $data ) {

		$settings = \Maven\Settings\MavenRegistry::instance()->getOptions();

		foreach( $settings as $setting ){
			if ( isset(  $data[$setting->getName()]) ){
				$setting->setValue( $data[$setting->getName()] );
			}
		}
		
		\Maven\Settings\MavenRegistry::instance()->saveOptions($settings);

		$this->getOutput()->sendApiResponse( $settings );
	}
	
	public function getView( $view ){
		
		switch($view){
			case "settings":
				$this->addJSONData("settingsCached", array("test"=>1234, "chau"=>false));
				return $this->getOutput()->getAdminView("settings/{$view}");
		}
	}
	
	public function getSettings () {
		
		$registry = \Maven\Settings\MavenRegistry::instance();
		
		$options = $registry->getOptions();
		$entity = array();
		foreach( $options as $option ){
			$entity[$option->getName()] = $option->getValue();
		}
		
		$this->getOutput()->sendApiResponse( $entity );
	}
	
	
	
}

