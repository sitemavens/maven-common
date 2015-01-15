<?php

/*
  Plugin Name: Maven
  Plugin URI:
  Description:
  Author: SiteMavens.com
  Version: 0.4.3
  Author URI: http://www.sitemavens.com/
 */

namespace Maven;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

//These are the only require_once needed. Then you should use the Loader class
require_once plugin_dir_path( __FILE__ ) . '/core/loader.php';

//We first load the registry 
$files = array(
	'core/domain-object',
	'settings/option',
	'settings/registry',
	'settings/wordpress-registry',
	'settings/maven-registry',
	'core/ui/option-output-generator',
	'core/ui/default-option-output-generator',
	'core/language',
	'core/utils',
	'core/ui/html-component',
	'core/observable',
	'core/observer',
	'gateways/gateway'
);


Core\Loader::load( plugin_dir_path( __FILE__ ), $files );


$registry = Settings\MavenRegistry::instance();

$registry->setPluginDir( plugin_dir_path( __FILE__ ) );

// We can't use plugin_dir_url(__FILE__) since we are using symbolic links to develop, so we have to 
// hardcode the plugin dir, which isn't so bad :)
$registry->setPluginDirectoryName( "maven" );
$registry->setPluginUrl( defined( 'DEV_ENV' ) && DEV_ENV ? WP_PLUGIN_URL . "/maven/" : plugin_dir_url( __FILE__ )  );
$registry->setPluginVersion( "0.4.2.1" );
$registry->setPluginName( 'Maven' );
$registry->setPluginShortName( 'm' );
$registry->init();

/**
 * We need to register the namespace of the plugin. It will be used for autoload function to add the required files. 
 */
Core\Loader::registerType( "Maven", $registry->getPluginDir() );




/**
 * 
 * Instantiate the installer 
 *
 * * */
$installer = new \Maven\Core\Installer();
register_activation_hook( __FILE__, array( &$installer, 'install' ) );
register_deactivation_hook( __FILE__, array( &$installer, 'uninstall' ) );


$director = Core\Director::getInstance();
$themeManager = $director->createThemeManager( $registry );


$director->createPluginElements( $registry );

$exceptionHandler = Exceptions\ExceptionHandler::instance();

$exceptionHandler->attach( new Core\Observers\ExceptionMailObserver() );

//Register actions and filters for external process in gateway
$hookManager = $director->getHookManager( $registry );

//$hookManager->addEnqueueScripts(array($themeManager, 'loadMavenScripts'));


$hookManager->addInit( array( 'Maven\Core\EntryPoint', 'init' ) );
$hookManager->addQueryVarsFilter( array( 'Maven\Core\EntryPoint', 'queryVars' ) );
$hookManager->addParseRequest( array( 'Maven\Core\EntryPoint', 'parseRequest' ) );

// We need to start the Session Manager
$hookManager->addInit( array( 'Maven\Session\SessionManager', 'init' ) );

$hookManager->addWp( array( 'Maven\Security\Blocker', 'init' ) );

// Set the print handler hooks
$hookManager->addInit( array( '\Maven\Core\PrintHandler', 'init' ) );
$hookManager->addQueryVarsFilter( array( '\Maven\Core\PrintHandler', 'queryVars' ) );
$hookManager->addParseRequest( array( '\Maven\Core\PrintHandler', 'parseRequest' ) );

// Set the auto login handler hooks
$hookManager->addInit( array( '\Maven\Core\AutoLoginHandler', 'init' ) );
$hookManager->addQueryVarsFilter( array( '\Maven\Core\AutoLoginHandler', 'queryVars' ) );
$hookManager->addParseRequest( array( '\Maven\Core\AutoLoginHandler', 'parseRequest' ) );

$hookManager->addInit( array( '\Maven\Core\ViewHandler', 'init' ) );
$hookManager->addQueryVarsFilter( array( '\Maven\Core\ViewHandler', 'queryVars' ) );
$hookManager->addParseRequest( array( '\Maven\Core\ViewHandler', 'parseRequest' ) );


Core\CronJobs::init();

$hookManager->addInit( array( '\Maven\Core\MailFormatter', 'init' ) );

//Set Password Reset hook
$hookManager->addInit( array( '\Maven\Core\UserManager', 'init' ) );

// We need to hook the login action to load the user information into the order
$cart = Core\Cart::current();
$hookManager->addLoginAction( array( $cart, 'login' ), 10, 2 );
$hookManager->addLogoutAction( array( $cart, 'logout' ) );

Front\AjaxFrontEnd::registerFrontEndHooks();

Shortcodes\ShortcodeManager::addShortcodes();

$adminInitizalizer = new Admin\AdminInitializer();
$hookManager->addAction( 'wp_json_server_before_serve', array( $adminInitizalizer, 'registerRouters' ) );

$frontEndManager = \Maven\Front\FrontEndManager::current();
$hookManager->addAction( 'wp_json_server_before_serve', array( $frontEndManager, 'registerRestApi' ) );

Api\Loader::init();

if ( !is_admin() ) {
	// Instantiate the front end
	$hookManager->addInit( array( '\Maven\Front\FrontEndManager', 'init' ), 999 );

	// TODO: We need to improve it
	$ssl = new \Maven\Core\Ssl();

	$hookManager->addWp( array( $ssl, 'forceSslCheckout' ) );

	$hookManager->addFilter( 'post_thumbnail_html', array( $ssl, 'forceSslImages' ) );
	$hookManager->addFilter( 'widget_text', array( $ssl, 'forceSslImages' ) );
	$hookManager->addFilter( 'wp_get_attachment_url', array( $ssl, 'forceSslImages' ) );
	$hookManager->addFilter( 'wp_get_attachment_image_attributes', array( $ssl, 'forceSslImages' ) );
	$hookManager->addFilter( 'wp_get_attachment_url', array( $ssl, 'forceSslImages' ) );
} else {

	$wpPosts = new Security\WpPosts();
	$hookManager->addLoadPost( array( $wpPosts, 'init' ) );

	// Initialize WP features
	$hookManager->addAdminInit( array( '\Maven\Admin\Wp\Loader', 'adminInit' ) );

	$componentManager = $director->getComponentManager( $registry );

	/** Settings * */
	$settings = $componentManager->createComponent( 'Settings', 'Maven\\Admin\\Controllers\\Settings' );

	$profiles = $componentManager->createComponent( 'Profiles', 'Maven\\Admin\\Controllers\\Profiles' );

	$orders = $componentManager->createComponent( 'Orders', 'Maven\\Admin\\Controllers\\Orders' );

	$roles = $componentManager->createComponent( 'Roles', 'Maven\\Admin\\Controllers\\Roles' );

	$promotions = $componentManager->createComponent( 'Promotions', 'Maven\\Admin\\Controllers\\Promotions' );

	$shippingMethods = $componentManager->createComponent( 'Shipping', 'Maven\\Admin\\Controllers\\ShippingMethods' );

	$taxes = $componentManager->createComponent( 'Taxes', 'Maven\\Admin\\Controllers\\Taxes' );
	$attributes = $componentManager->createComponent( 'Attributes', 'Maven\\Admin\\Controllers\\Attributes' );
	$https = $componentManager->createComponent( 'Https', 'Maven\\Admin\\Controllers\\Https' );

	$menuManager = $director->getMenuManager( $registry );

	$menuManager->registerMenu( $settings, "Maven", $registry->getAssetsUrl() . "images/icon.png" );
	$menuManager->registerMenu( $roles );
	$menuManager->registerMenu( $profiles );
	$menuManager->registerMenu( $orders );
	$menuManager->registerMenu( $promotions );
	$menuManager->registerMenu( $shippingMethods );
	$menuManager->registerMenu( $taxes );
	$menuManager->registerMenu( $attributes );
	$menuManager->registerMenu( $https );
}  


//
//$profile = new Core\Domain\Profile();
//
//var_dump(is_subclass_of( $profile, "DomainObject" ) );
//
//var_dump(is_subclass_of( $profile, "\Maven\Core\DomainObject" ) );