<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class Https extends MavenAdminController {

	public function __construct() {

		parent::__construct();
	}
	
	public function registerRoutes( $routes ) {

		$routes[ '/maven/https' ] = array(
		    array( array( $this, 'getHttpPages' ), \WP_JSON_Server::READABLE ),
		    array( array( $this, 'saveHttps' ), \WP_JSON_Server::CREATABLE|\WP_JSON_Server::ACCEPT_JSON ),
		);

		return $routes;
	}
	
	public function getHttpPages() {
		$registry = \Maven\Settings\MavenRegistry::instance();
		$httpsPages = $registry->getHttpsPages();
		if ( !$httpsPages ){
			$httpsPages = array();
		}

		$wp_pages = \get_pages();
		$entities = array();
		foreach ( $wp_pages as $page ) {
			$entities[] = array(
			    'id' => $page->ID,
			    'title' => $page->post_title,
			    'url' => \get_permalink($page->ID),
			    'name' => $page->post_name,
				'https' => in_array( $page->ID, $httpsPages )
			);
		}

		
		$this->getOutput()->sendApiResponse( $entities );
		
	}

	function saveHttps( $data ) {
		$httpPagesChecked = array();
		foreach ( $data as $page ) {
			if( isset($page['https']) && $page['https'] ){
				$httpPagesChecked[] = $page['id'];
			}
		}
		// Get all the settings 
		$options = $this->getRegistry()->getOptions();

		$options[ 'httpsPages' ]->setValue( $httpPagesChecked );

		$this->getRegistry()->saveOptions( $options );

		$this->getOutput()->sendData( "Settings udpated" );
	}
}
