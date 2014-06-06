<?php

namespace Maven\Core\UI;

class ThemeManager {

	/**
	 *
	 * @var \Maven\Core\HookManager 
	 */
	private $hookManager;

	/**
	 *
	 * @var \Maven\Settings\MavenRegistry
	 */
	private $registry;

	/**
	 * 
	 * @param \Maven\Settings\MavenRegistry $registry
	 * @param \Maven\Core\ResourceManager $resourceManager
	 */
	public function __construct( \Maven\Settings\MavenRegistry $registry, \Maven\Core\ResourceManager $resourceManager ) {

		$this->resourceManager = $resourceManager;
		$this->registry = $registry;
	}

	public function init() {

		$this->loadScripts();
	}
	
	

	public function loadScripts() {

		$themeName = $this->registry->getActiveThemeName();

		/**
		 * Declare all the basic scripts/styles
		 */
		// Get the current WP language
		$lang = get_bloginfo( "language" );
		$lang = explode( "-", $lang );
		$lang = count( $lang ) > 0 ? $lang[ 0 ] : $lang;

		$lowerLang = strtolower( $lang );

		$main = 'main.min';
		if ( defined( 'COMPRESS_SCRIPTS' ) && ! COMPRESS_SCRIPTS ) {
			$main = 'main';
		}

		$this->resourceManager->addLocalizedScript( 'maven', 'Maven', array(
		    'main' => $main,
		    'handler' => 'mavenHandleRequest',
		    'gridRows' => $this->registry->getGridRows(),
		    'printUrl' => get_bloginfo('url').'/'. $this->registry->getPrintUrl(),
		    'noPhotoUrl' => $this->registry->getPluginUrl() . 'assets/images/nophoto.jpg',
		    'requireTextPluginPath' => $this->registry->getPluginUrl() . "assets/js/require/text.min",
		    'requireStickItPluginPath' => $this->registry->getPluginUrl() . "assets/js/require/backbone.stickit.min",
		    'requireBackboneValidationPluginPath' => $this->registry->getPluginUrl() . "assets/js/require/backbone.validation.min",
		    'requireBackgridPluginPath' => $this->registry->getPluginUrl() . "assets/js/backgrid/backgrid",
		    'requireBackgridPaginatorPluginPath' => $this->registry->getPluginUrl() . "assets/js/backgrid/backgrid-paginator.min",
		    'requireBackgridFilterPluginPath' => $this->registry->getPluginUrl() . "assets/js/backgrid/backgrid-filter.min",
		    'requireBackgridFilterLunrPluginPath' => $this->registry->getPluginUrl() . "assets/js/backgrid/lunr",
		    'requireBackbonePageablePluginPath' => $this->registry->getPluginUrl() . "assets/js/backgrid/backbone-pageable",
		    'requireDataTablesPluginPath' => $this->registry->getPluginUrl() . "assets/js/jquery.dataTables.min",
		    'requireToggleButtonsPluginPath' => $this->registry->getPluginUrl() . "assets/js/jquery.toggle.buttons.min",
		    'requireDatePickerPluginPath' => $this->registry->getPluginUrl() . "assets/js/bootstrap-datepicker/bootstrap-datepicker.min",
		    'requireDateJSPluginPath' => $this->registry->getPluginUrl() . "assets/js/date.min",
		    'requireDateRangePickerPluginPath' => $this->registry->getPluginUrl() . "assets/js/bootstrap/daterangepicker.min",
		    'requireGritterPluginPath' => $this->registry->getPluginUrl() . 'assets/js/jquery.gritter.min',
		    'requireTimePickerPluginPath' => $this->registry->getPluginUrl() . "assets/js/bootstrap/bootstrap-timepicker.min",
		    'adminImagesPath' => $this->registry->getPluginUrl() . "admin/assets/images/",
		    'imagesPath' => $this->registry->getPluginUrl() . "assets/images/",
//'requireTinyMce' =>$this->registry->getPluginUrl().'assets/js/tinymce/tiny_mce',
		    'requireWysiPluginPath' => $this->registry->getPluginUrl() . "assets/js/bootstrap/wysihtml5-0.3.0.min",
		    'requireWysihtml5PluginPath' => $this->registry->getPluginUrl() . "assets/js/bootstrap/bootstrap-wysihtml5.min",
		    'requireForm' => $this->registry->getPluginUrl() . 'assets/js/jquery.form.min',
		    'requireTagsInput' => $this->registry->getPluginUrl() . 'assets/js/jquery.tagsinput.min',
		    'requireDomReady' => $this->registry->getPluginUrl() . 'assets/js/require/domReady.min',
		    'requireClockfacePluginPath' => $this->registry->getPluginUrl() . 'assets/js/clockface/clockface',
		    'requireSelect2PluginPath' => $this->registry->getPluginUrl() . 'assets/js/select2/select2.min',
		    'recurringEnabled' => $this->registry->isRecurringEnabled(),
		    'requireGoogleMaps' => $this->registry->getPluginUrl() . 'assets/js/backbone.googlemaps',
			)
		);

		$this->resourceManager->addAdminScript( $this->registry->getPluginUrl() . "assets/js/jquery.dataTables.js", $this->registry->getPluginVersion(), array( 'jquery' ), 'dataTables', true );
		$this->resourceManager->addAdminScript( $this->registry->getPluginUrl() . "assets/js/bootstrap/bootstrap.min.js", $this->registry->getPluginVersion(), array( 'jquery' ), 'bootstrap', true );
		$this->resourceManager->addAdminScript( $this->registry->getPluginUrl() . "assets/js/bootstrap/bootstrap-datepicker.min.js", $this->registry->getPluginVersion(), array( 'bootstrap' ), 'bootstrap-datepicker', true );
		$this->resourceManager->addAdminScript( $this->registry->getPluginUrl() . "assets/js/chosen/chosen.jquery.js", $this->registry->getPluginVersion(), array( 'jquery' ), 'chosen', true );
		$this->resourceManager->addAdminScript( $this->registry->getPluginUrl() . "assets/js/flot/jquery.flot.js", $this->registry->getPluginVersion(), array( 'jquery' ), 'flot', true );
		$this->resourceManager->addAdminScript( $this->registry->getPluginUrl() . "assets/js/flot/jquery.flot.resize.js", $this->registry->getPluginVersion(), array( 'jquery', 'flot' ), 'chosen', true );
		$this->resourceManager->addAdminScript( $this->registry->getPluginUrl() . "assets/js/jquery.tagsinput.min.js", $this->registry->getPluginVersion(), array( 'jquery' ), 'tagsinput', true );
		$this->resourceManager->addAdminScript( $this->registry->getPluginUrl() . "assets/js/jquery.form.js", $this->registry->getPluginVersion(), array( 'jquery' ), 'jquery.forms', true );

		$this->resourceManager->addAdminScript( 'https://maps.googleapis.com/maps/api/js?sensor=false', $this->registry->getPluginVersion(), array( 'jquery' ), 'google-maps', true );


		$this->resourceManager->addAdminScript( $this->registry->getPluginUrl() . "assets/js/jquery.toggle.buttons.js", $this->registry->getPluginVersion(), array( 'jquery', 'maven' ), 'toggleButtons', true );
		$this->resourceManager->addAdminStyle( $this->registry->getPluginUrl() . "assets/css/bootstrap-toggle-buttons.css", $this->registry->getPluginVersion(), 'toggleButtons', true );

		// These css are needed all the time
		$this->resourceManager->addAdminStyle( $this->registry->getPluginUrl() . "assets/css/bootstrap.min.css", $this->registry->getPluginVersion(), array( 'toggleButtons' ), 'bootstrap', false );
		$this->resourceManager->addAdminStyle( $this->registry->getPluginUrl() . "assets/css/jquery.gritter.css", $this->registry->getPluginVersion(), array( ), 'gritter', false );
		$this->resourceManager->addAdminStyle( $this->registry->getPluginUrl() . "assets/css/jquery.tagsinput.css", $this->registry->getPluginVersion(), array( ), 'tagsinput', true );

		//Backgrid
		$this->resourceManager->addAdminStyle( $this->registry->getPluginUrl() . "assets/css/backgrid/backgrid-filter.min.css", $this->registry->getPluginVersion(), array( ), 'backgridFilter', false );
		$this->resourceManager->addAdminStyle( $this->registry->getPluginUrl() . "assets/css/backgrid/backgrid-paginator.min.css", $this->registry->getPluginVersion(), array( ), 'backgridPaginator', false );
		$this->resourceManager->addAdminStyle( $this->registry->getPluginUrl() . "assets/css/backgrid/backgrid.min.css", $this->registry->getPluginVersion(), array( 'backgridFilter', 'backgridPaginator' ), 'backgrid', false );

		/*
		 * Theme stuff
		 */
		$this->resourceManager->addAdminStyle( $this->registry->getPluginUrl() . "themes/{$themeName}/css/style.css", $this->registry->getPluginVersion() );
		$this->resourceManager->addAdminScript( $this->registry->getPluginUrl() . "themes/{$themeName}/js/script.js", $this->registry->getPluginVersion(), array( 'jquery', 'jquery-ui-core', 'jquery-ui-tabs', 'require' ) );


		$this->resourceManager->addAdminScript( $this->registry->getPluginUrl() . "assets/js/require/require.js", $this->registry->getPluginVersion(), array( 'jquery', 'backbone', 'underscore' ), 'requireLib', true );
		$this->resourceManager->addAdminScript( $this->registry->getPluginUrl() . "assets/js/require/config.js", $this->registry->getPluginVersion(), array( 'requireLib' ), 'require', true );
	}

}
