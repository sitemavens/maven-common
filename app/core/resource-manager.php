<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class ResourceManager implements ActionControllerObserver {

	/**
	 *
	 * @var \Maven\Core\HookManager 
	 */
	private $hookManager;

	/**
	 *
	 * @var \Maven\Core\Resource[] 
	 */
	private $adminScripts = array();

	/**
	 *
	 * @var \Maven\Core\Resource[] 
	 */
	private $globalScripts = array();
	private $mavenScriptResources = array();
	private $mavenStyleResources = array();

	/**
	 *
	 * @var \Maven\Core\Resource[]
	 */
	private $adminStyles = array();

	/**
	 *
	 * @var \Maven\Core\LocalizedScript[] 
	 */
	private $localizedScripts = array();
	private static $instance;

	/**
	 * 
	 * @param \Maven\Core\HookManager $hookManager
	 */
	public function __construct( \Maven\Core\HookManager $hookManager ) {

		$this->hookManager = $hookManager;
		$this->hookManager->addInit( array( $this, 'registerGlobalScripts' ) );

		if ( is_admin() ) {
			$this->hookManager->addAdminInit( array( $this, 'registerAdminScripts' ) );
			$this->hookManager->addAdminInit( array( $this, 'registerAdminStyles' ) );
			$this->hookManager->addAdminInit( array( $this, 'localizeScripts' ) );
		} else {
			$this->hookManager->addInit( array( $this, 'localizeScripts' ) );
		}


		$this->loadMavenScripts();
	}

	public function loadMavenScripts() {

		$registry = \Maven\Settings\MavenRegistry::instance();

		$this->addGlobalScript( $registry->getPluginUrl() . "assets/js/maven.js", $registry->getPluginVersion(), array( 'jquery', 'jquery-ui-tabs' ), 'maven' );
		$this->addLocalizedScript( 'maven', 'Maven', array(
		    'adminUrl' => admin_url(),
		    'viewsUrl' => $registry->getViewsUrl(),
		    'adminViewsUrl' => $registry->getAdminViewsUrl(),
		    'imagesUrl' => $registry->getImagesUrl(),
		    'viewHandlerUrl' => $registry->getWebSiteUrl() . "/" . $registry->getAdminViewHandlerUrl(),
		    'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		    'loadingImagePath' => $registry->getImagesUrl() . "loading.gif",
		    'ajaxLoadingPath' => $registry->getAssetsUrl() . "images/ajax-loader.gif",
			'imagesPathUrl' => $registry->getImagesUrl() ,
		    'printUrl' => get_bloginfo( 'url' ) . '/' . $registry->getPrintUrl()
			)
		);
	}

	public function localizeScripts() {

		foreach ( $this->localizedScripts as $localizeScript ) {

			$data = $localizeScript->getData();

			// This is the only way I found it, since the problem is that init action hasn't been initiaized yet, so we can't use wp_create_nonce
			if ( $localizeScript->getScriptKey() == "maven" ) {
				$data[ 'transactionNonce' ] = wp_create_nonce( \Maven\Front\FrontEndManager::MavenTransactionKey );
			}
			wp_localize_script( $localizeScript->getScriptKey(), $localizeScript->getDomain(), $data );
		}
	}

	/**
	 * 
	 * @return HookManager 
	 */
	static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self( );
		}

		return self::$instance;
	}

	public function registerGlobalScripts() {

		foreach ( $this->globalScripts as $script ) {

			wp_register_script( $script->getName(), $script->getFile(), $script->getDeps(), $script->getVersion() );

			if ( ! $script->getRegisterOnly() ) {
				wp_enqueue_script( $script->getName() );
			}
		}
	}

	public function registerAdminScripts() {

		foreach ( $this->adminScripts as $script ) {

			wp_register_script( $script->getName(), $script->getFile(), $script->getDeps(), $script->getVersion() );

			if ( ! $script->getRegisterOnly() )
				wp_enqueue_script( $script->getName() );
		}

		foreach ( $this->mavenScriptResources as $script ) {

			wp_enqueue_script( $script );
		}
	}

	public function registerAdminStyles() {

		foreach ( $this->adminStyles as $style ) {

			wp_register_style( $style->getName(), $style->getFile(), $style->getDeps(), $style->getVersion() );

			if ( ! $style->getRegisterOnly() )
				wp_enqueue_style( $style->getName() );
		}

		foreach ( $this->mavenStyleResources as $style ) {
			wp_enqueue_style( $style );
		}
	}

	/**
	 * 
	 * @param string $cssFile
	 * @param string $version
	 * @param array $deps
	 * @param string $key
	 * @param boolean $registerOnly 
	 */
	public function addAdminStyle( $cssFile, $version, $deps = array(), $key = '', $registerOnly = false ) {

		if ( ! $key )
			$key = "mvn-" . sanitize_key( basename( $cssFile, '.css' ) );

		$this->adminStyles[] = new Resource( $key, $cssFile, $version, $deps, true, $registerOnly );
	}

	public function addLocalizedScript( $scriptKey, $domain, $data ) {

		// If the script already exists, we just merge the data
		if ( isset( $this->localizedScripts[ $scriptKey ] ) ) {
			$this->localizedScripts[ $scriptKey ]->setData( array_merge( $data, $this->localizedScripts[ $scriptKey ]->getData() ) );
		} else
			$this->localizedScripts[ $scriptKey ] = new LocalizedScript( $scriptKey, $domain, $data );
	}

	/**
	 * 
	 * @param string $jsFile
	 * @param string $version
	 * @param array $deps
	 * @param string $key
	 * @param boolean $registerOnly
	 */
	public function addGlobalScript( $jsFile, $version, $deps = array(), $key = '', $registerOnly = false ) {

		if ( ! $key ) {
			$key = "mvn-" . sanitize_key( basename( $jsFile, '.js' ) );
		}

		$this->globalScripts[] = new \Maven\Core\Resource( $key, $jsFile, $version, $deps, true, $registerOnly );
	}

	/**
	 * 
	 * @param string $jsFile
	 * @param string $version
	 * @param array $deps
	 * @param string $key
	 * @param boolean $registerOnly
	 */
	public function addAdminScript( $jsFile, $version, $deps = array(), $key = '', $registerOnly = false ) {

		if ( ! $key )
			$key = "mvn-" . sanitize_key( basename( $jsFile, '.js' ) );

		$this->adminScripts[] = new \Maven\Core\Resource( $key, $jsFile, $version, $deps, true, $registerOnly );
	}

	public function update( Component $component ) {

		// A component was used, so we need to see if there are some resources to add. 
		$resources = $component->getScriptResources();

		foreach ( $resources as $resource ) {
			if ( $resource->isAdmin() ) {

				// If there is no file, is trying to load a maven resource
				if ( ! $resource->getFile() )
					$this->mavenScriptResources[] = $resource->getName();
				else
					$this->addAdminScript( $resource->getFile(), $resource->getVersion(), $resource->getDeps(), $resource->getName() );
			}
		}

		$resources = $component->getStyleResources();

		foreach ( $resources as $resource ) {
			if ( $resource->isAdmin() ) {

				// If there is no file, is trying to load a maven resource
				if ( ! $resource->getFile() )
					$this->mavenStyleResources[] = $resource->getName();
				else
					$this->addAdminStyle( $resource->getFile(), $resource->getVersion(), $resource->getDeps(), $resource->getName() );
			}
		}


		if ( $component->wpMedia() )
			$this->hookManager->addAdminEnqueueScripts( array( &$this, 'enqueueWpMedia' ) );
	}

	function enqueueWpMedia() {

		wp_enqueue_media();
	}

}
