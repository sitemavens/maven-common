<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class AdminController extends \Maven\Admin\Controllers\MavenAdminController {

	private $page_hook = '';

	public function __construct() {
		parent::__construct();
	}

	public function init() {
//		$this->getHookManager()->addAction( 'admin_menu', array( $this, 'register_menu_page' ) );

		$this->getHookManager()->addAction( 'admin_enqueue_scripts', array( $this, 'registerStyles' ), 10, 1 );

		$this->getHookManager()->addAction( 'admin_enqueue_scripts', array( $this, 'registerScripts' ), 10, 1 );

		//$this->getHookManager()->addAjaxAction( 'mvn_getTaxes', array( $this, 'getTaxes' ) );
		\Maven\Loggers\Logger::log()->message( "Admin Init" );
	}

	static function commonApiInit() {
		\Maven\Loggers\Logger::log()->message( "API INIT" );
		$admin = new AdminController();

		add_filter( 'json_endpoints', array( $admin, 'registerRoutes' ) );
	}

	function registerRoutes( $routes ) {
		
		\Maven\Loggers\Logger::log()->message( "Register Routes" );
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

	function registerStyles( $hook ) {
		if ( $hook == $this->page_hook ) {
			$registry = \Maven\Settings\MavenRegistry::instance();

			if ( $registry->isDevEnv() ) {
				wp_enqueue_style( 'bootstrap', $registry->getBowerComponentUrl() . "bootstrap/dist/css/bootstrap.css", null, $registry->getPluginVersion() );
				wp_enqueue_style( 'bootstrap-theme', $registry->getBowerComponentUrl() . "bootstrap/dist/css/bootstrap-theme.css", null, $registry->getPluginVersion() );

				wp_enqueue_style( 'main', $registry->getStylesUrl() . "main.css", array( 'bootstrap', 'bootstrap-theme' ), $registry->getPluginVersion() );
			} else {
				wp_enqueue_style( 'mainCss', $registry->getStylesUrl() . "main.min.css", array(), $registry->getPluginVersion() );
			}
		}
	}

	function registerScripts( $hook ) {

		global $post;
		//var_dump( $hook );
		if ( $hook == $this->page_hook ) {

			$registry = \Maven\Settings\MavenRegistry::instance();

			wp_enqueue_script( 'angular', $registry->getBowerComponentUrl() . "angular/angular.js", 'jquery', $registry->getPluginVersion() );
			wp_enqueue_script( 'bootstrap', $registry->getBowerComponentUrl() . "bootstrap/dist/js/bootstrap.js", 'jquery', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-resource', $registry->getBowerComponentUrl() . "angular-resource/angular-resource.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-cookies', $registry->getBowerComponentUrl() . "angular-cookies/angular-cookies.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-sanitize', $registry->getBowerComponentUrl() . "angular-sanitize/angular-sanitize.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-route', $registry->getBowerComponentUrl() . "angular-route/angular-route.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-bootstrap', $registry->getBowerComponentUrl() . "angular-bootstrap/ui-bootstrap-tpls.js", 'angular', $registry->getPluginVersion() );
			//wp_enqueue_script( 'angular-google-chart', $registry->getBowerComponentUrl() . "angular-google-chart/ng-google-chart.js", 'angular', $registry->getPluginVersion() );

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

	// The menu entry
	function register_menu_page() {

		$me = new self();
		$this->page_hook = \add_menu_page( 'Maven&copy;', 'Maven&copy;', 'manage_options', 'mvn_maven_new', array( $me, 'showList' ) );
 
	}

	/**
	 * update a Maven product
	 * @param int $postId
	 * @param object $post
	 */
	public function save( $postId, $post ) {
		
	}

	/**
	 * Update a Maven product
	 * @param int $termId
	 * @param int $taxonomyId
	 */
	public function insert( $postId, $post ) {
		
	}

	/**
	 * Delete a Maven Category
	 * @param int $termId
	 * @param int $taxonomyId
	 * @param object $deletedTerm
	 */
	public function delete( $termId, $taxonomyId, $deletedTerm ) {
		
	}

	public function showForm() {
		
	}

	public function showList() {
		echo $this->getOutput()->getAdminView( "admin" );
	}

	public function getTaxes() {
		$manager = new \Maven\Core\TaxManager();

		$filter = new \Maven\Core\Domain\TaxFilter();
		$filter->setAll( TRUE );

		$taxes = $manager->getTaxes( $filter );

		$this->getOutput()->sendApiResponse( $taxes );
	}

	public function newTax( $data ) {
		$manager = new \Maven\Core\TaxManager();

		$tax = new \Maven\Core\Domain\Tax();

		$tax->load( $data );

		$tax = $manager->addTax( $tax );

		$this->getOutput()->sendApiResponse( $tax );
	}

	public function getTax( $id ) {
		$manager = new \Maven\Core\TaxManager();
		$tax = $manager->get( $id );

		$this->getOutput()->sendApiResponse( $tax );
	}

	public function editTax( $id, $data ) {

		$manager = new \Maven\Core\TaxManager();

		$tax = new \Maven\Core\Domain\Tax();

		$tax->load( $data );

		$tax = $manager->addTax( $tax );

		$this->getOutput()->sendApiResponse( $tax );
	}

	public function deleteTax( $id ) {
		
	}

}
