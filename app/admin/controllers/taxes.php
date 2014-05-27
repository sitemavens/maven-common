<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class Taxes extends \Maven\Admin\Controllers\MavenAdminController {

	public function __construct() {
		parent::__construct();
	}
	
	public function registerRoutes(){
		
		$routes[ '/common/taxes' ] = array(
			array( array( $this, 'getTaxes' ), \WP_JSON_Server::READABLE ),
			array( array( $this, 'newTax' ), \WP_JSON_Server::CREATABLE | \WP_JSON_Server::ACCEPT_JSON ),
		);
		$routes[ '/common/taxes/(?P<id>\d+)' ] = array(
			array( array( $this, 'getTax' ), \WP_JSON_Server::READABLE ),
			array( array( $this, 'editTax' ), \WP_JSON_Server::EDITABLE | \WP_JSON_Server::ACCEPT_JSON ),
			array( array( $this, 'deleteTax' ), \WP_JSON_Server::DELETABLE ),
		);
		
		return $routes;
		
		
	}
	
 
}