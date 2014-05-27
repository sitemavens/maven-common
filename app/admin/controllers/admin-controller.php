<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class AdminController extends \Maven\Admin\Controllers\MavenAdminController {


	public function __construct () {
		parent::__construct();
	}

	public function init () {
		
				
	}
	
	public function registerScripts( $hook ){
 
		$registry = $this->getRegistry();
			
		wp_enqueue_script( 'mavenApp', $registry->getScriptsUrl() . "admin/app.js", 'angular', $registry->getPluginVersion() );
		wp_enqueue_script( 'admin/directives/loading.js', $registry->getScriptsUrl() . "admin/directives/loading.js", 'mavenApp', $registry->getPluginVersion() );
		wp_enqueue_script( 'admin/services/admin-services.js', $registry->getScriptsUrl() . "admin/services/admin-services.js", 'mavenApp', $registry->getPluginVersion() );
		wp_enqueue_script( 'admin/controllers/main-nav.js', $registry->getScriptsUrl() . "admin/controllers/main-nav.js", 'mavenApp', $registry->getPluginVersion() );
		wp_enqueue_script( 'admin/controllers/dashboard.js', $registry->getScriptsUrl() . "admin/controllers/dashboard.js", 'mavenApp', $registry->getPluginVersion() );
		wp_enqueue_script( 'admin/controllers/settings.js', $registry->getScriptsUrl() . "admin/controllers/settings.js", 'mavenApp', $registry->getPluginVersion() );
		wp_enqueue_script( 'admin/controllers/taxes/taxes.js', $registry->getScriptsUrl() . "admin/controllers/taxes/taxes.js", 'mavenApp', $registry->getPluginVersion() );
		wp_enqueue_script( 'admin/controllers/taxes/taxes-edit.js', $registry->getScriptsUrl() . "admin/controllers/taxes/taxes-edit.js", 'mavenApp', $registry->getPluginVersion() );
		
	}

	static function commonApiInit () {
		$admin = new AdminController();

		add_filter( 'json_endpoints', array( $admin, 'registerRoutes' ) );
	}

	function registerRoutes ( $routes ) {
		 
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

	

	

	// The menu entry
	function register_menu_page () {

		$me = new self();
		$this->page_hook = \add_menu_page( 'Maven&copy;', 'Maven&copy;', 'manage_options', 'mvn_maven_new', array( $me, 'showList' ) );
	}

	/**
	 * update a Maven product
	 * @param int $postId
	 * @param object $post
	 */
	public function save ( $postId, $post ) {
		
	}

	/**
	 * Update a Maven product
	 * @param int $termId
	 * @param int $taxonomyId
	 */
	public function insert ( $postId, $post ) {
		
	}

	/**
	 * Delete a Maven Category
	 * @param int $termId
	 * @param int $taxonomyId
	 * @param object $deletedTerm
	 */
	public function delete ( $termId, $taxonomyId, $deletedTerm ) {
		
	}

	public function showForm () {
		
	}

	public function showList () {
		echo $this->getOutput()->getAdminView( "admin" );
	}

	public function getTaxes () {
		$manager = new \Maven\Core\TaxManager();

		$filter = new \Maven\Core\Domain\TaxFilter();
		$filter->setAll( TRUE );

		$taxes = $manager->getTaxes( $filter );

		$this->getOutput()->sendApiResponse( $taxes );
	}

	public function newTax ( $data ) {
		$manager = new \Maven\Core\TaxManager();

		$tax = new \Maven\Core\Domain\Tax();

		$tax->load( $data );

		$tax = $manager->addTax( $tax );

		$this->getOutput()->sendApiResponse( $tax );
	}

	public function getTax ( $id ) {
		$manager = new \Maven\Core\TaxManager();
		$tax = $manager->get( $id );

		$this->getOutput()->sendApiResponse( $tax );
	}

	public function editTax ( $id, $data ) {

		$manager = new \Maven\Core\TaxManager();

		$tax = new \Maven\Core\Domain\Tax();

		$tax->load( $data );

		$tax = $manager->addTax( $tax );

		$this->getOutput()->sendApiResponse( $tax );
	}

	public function deleteTax ( $id ) {
		
	}

}
