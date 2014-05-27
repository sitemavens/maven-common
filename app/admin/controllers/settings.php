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
		
		return $routes;
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

