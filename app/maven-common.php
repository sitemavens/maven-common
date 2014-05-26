<?php

/*
  Plugin Name: Maven Common
  Plugin URI:
  Description:
  Author: Site Mavens
  Version: 0.4.2.1
  Author URI:
 */

namespace Maven;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
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
//	'core/action-controller',
//	'core/request',
//	'core/component',
//	'core/component-manager',
//	'core/ui/output',
    'core/ui/html-component',
    'core/observable',
    'core/observer'

//	'core/ui/admin-controller'
);


Core\Loader::load( plugin_dir_path( __FILE__ ), $files );


$registry = Settings\MavenRegistry::instance();

$registry->setPluginDir( plugin_dir_path( __FILE__ ) );

// We can't use plugin_dir_url(__FILE__) since we are using symbolic links to develop, so we have to 
// hardcode the plugin dir, which isn't so bad :)
$registry->setPluginDirectoryName( "maven-common" );
$registry->setPluginUrl( defined( 'DEV_ENV' ) && DEV_ENV ? WP_PLUGIN_URL . "/maven-common/" : plugin_dir_url( __FILE__ )  );
$registry->setPluginVersion( "0.4.2.1" );
$registry->setPluginName( 'Maven Common' );
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

Core\CronJobs::init();

$hookManager->addInit( array( '\Maven\Core\MailFormatter', 'init' ) );
//Set Password Reset hook
$hookManager->addInit( array( '\Maven\Core\UserManager', 'init' ) );

// We need to hook the login action to load the user information into the order
$cart = Core\Cart::current();
$hookManager->addLoginAction( array( $cart, 'login' ), 10, 2 );
$hookManager->addLogoutAction( array( $cart, 'logout' ) );

Front\AjaxFrontEnd::registerFrontEndHooks();

$hookManager->addAction( 'wp_json_server_before_serve', array( 'Maven\Admin\Controllers\AdminController', 'commonApiInit' ) );
	

if ( ! is_admin() ) {
	// Instantiate the front end
	$hookManager->addInit( array( '\Maven\Front\FrontEndManager', 'init' ), 999 );
}

if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
	$hookManager->addAjaxAction( 'mavenAjaxCartHandler', array( '\Maven\Front\FrontEndManager', 'init' ) );
	$hookManager->addPublicAjaxAction( 'mavenAjaxCartHandler', array( '\Maven\Front\FrontEndManager', 'init' ) );
}

$adminBarMenu = new Core\AdminBarMenu( 'Maven', '/wp-admin/admin.php?page=m-setti' );
$adminBarMenu->addSubMenu( 'Profiles', '/wp-admin/admin.php?page=m-profi' );
$adminBarMenu->addSubMenu( 'Orders', '/wp-admin/admin.php?page=m-profi' );
$adminBarMenu->addSubMenu( 'Https', '/wp-admin/admin.php?page=m-https' );

if ( is_admin() ) {

	$wpPosts = new Security\WpPosts();
	$hookManager->addLoadPost( array( $wpPosts, 'init' ) );

	// Initialize WP features
	$hookManager->addAdminInit( array( '\Maven\Admin\Wp\Loader', 'adminInit' ) );

	Admin\Main::init();
	
	$componentManager = $director->getComponentManager( $registry );

	/** Settings * */
	$settings = $componentManager->createComponent( 'Settings', 'Maven\\Admin\\Controllers\\Settings' );

	$settings->setDefaultAction( 'showForm' );

	$settings->addAjaxAction( 'entryPoint' );
	$settings->addAjaxAction( 'entryPointGateways' );
	$settings->addAjaxAction( 'entryPointTrackers' );
	$settings->addAjaxAction( 'uploadOrganizationLogo' );
	$settings->addAjaxAction( 'emailEntryPoint' );
	$settings->addAjaxAction( 'entryPointLicense' );
	$settings->addAjaxAction( 'entryPointMailProviders' );
	$settings->addAjaxAction( 'entryPointMailLists' );


	$settings->addWpMedia();
	$settings->addScriptResource( 'bootstrap' );
	$settings->addScriptResource( 'require' );
	$settings->addStyleResource( 'toggleButtons' );
	//$settings->addScriptResource('tagsinput');
	$settings->addStyleResource( 'tagsinput' );
	$settings->addStyleResource( 'backgrid' );

	$settings->addLocalizations( array(
	    'sendErrorNotificationsTo' => 'Send error notification to',
	    'sendErrorNotificationsToHelp' => 'Who wants to know when an issue occurs? ',
	    'senderEmail' => 'Sender Email',
	    'senderName' => 'Sender Name',
	    'contactEmail' => 'Contact Email',
	    'contactEmailHelp' => '(Info about this address. It will be added as contact address in the emails.)',
	    'activeTheme' => 'Active Theme',
	    'liveMode' => 'Live mode',
	    'testMode' => 'Test mode',
	    'login' => 'Login',
	    'transactionKey' => 'Transaction Key',
	    'connectionTicket' => 'Connection Ticket',
	    'appLogin' => 'Application Login',
	    'authorizationType' => 'Authorization Type',
	    'activeGateway' => 'Active Gateway',
	    'dummyAlertInfo' => 'The Dummy Gateway will let you play with your transactions, generating random responses. ',
	    'offlineAlertInfo' => 'Not sure what to put here. Ask memo how it deal with offline gateway in shop',
	    'activeTrackers' => 'Active Trackers',
	    'tabGeneral' => 'General',
	    'tabEmails' => 'Emails',
	    'tabGateway' => 'Gateways',
	    'tabTracking' => 'Tracking',
	    'tabMaillist' => 'Maillist',
	    'tabSocialNetworks' => 'Social Networks',
	    'analyticsAccountId' => 'Analytics Account ID',
	    'domain' => 'Domain',
	    'delimiter' => 'Delimiter',
	    'organizationLogo' => 'Organization Logo',
	    'bccNotificationsTo' => 'BCC Notifications To',
	    'addEmail' => 'Add email',
	    'emailTemplate' => 'Email Template',
	    'emailTo' => 'Email To',
	    'emailCC' => 'Email CC',
	    'emailBCC' => 'Email BCC',
	    'subject' => 'Subject',
	    'message' => 'Message',
	    'emailProvider' => 'Email Provider',
	    'organizationName' => 'Organization Name',
	    'signature' => 'Signature',
	    'license' => 'License',
	    'activate' => 'Activate',
	    'deactivate' => 'Deactivate',
	    'licenseNotActiveTitle' => 'The plugin license is not active!',
	    'licenseNotActiveDesc' => 'In order to receive updates, you need to active your plugin.',
	    'licenseActiveTitle' => 'The plugin license is active!',
	    'licenseActiveDesc' => '',
	    'recurringDisabled' => 'The selected gateway doesn\'t support Recurring feature.',
	    'recurringEnabled' => 'The selected gateway has Recurring feature. Enjoy!',
	    'username' => 'Username',
	    'password' => 'Password',
	    'tabLicensing' => 'Licensing',
	    'segmentIoId' => 'Key',
	    'businessEmail' => 'Business Email',
	    'cancelUrl' => 'Cancel Url',
	    'returnUrl' => 'Return Url',
	    'tabEmailProviders' => 'Email Providers',
	    'activeProviders' => 'Active Email Providers',
	    'activeSocialNetworks' => 'Active Social Network',
	    'facebookAppId' => 'Facebook App ID',
	    'facebookSecret' => 'Facebook App Secret',
	    'facebookAccessToken' => 'Access Token',
	    'emailBackgroundColor' => 'Email background color (exa)',
	    'loginPage' => 'Login Page',
	    'registrationThankYouPage' => 'Registration Thank You page',
	    'gridRows' => 'Rows to show on Grid'
		)
	);


	/** Settings * */
//	$seo = $componentManager->createComponent( 'SEO', 'Maven\\Admin\\Controllers\\Seo' );
//
//	$seo->setDefaultAction( 'showForm' );
//	$seo->addAjaxAction( 'entryPoint' );

	/** HTTPS * */
	$https = $componentManager->createComponent( 'Https', 'Maven\\Admin\\Controllers\\Https' );

	$https->setDefaultAction( 'showForm' );
	$https->addAjaxAction( 'entryPoint' );
	$https->addLocalizations( array(
	    'tabPages' => 'Pages',
	    'buttonSave' => 'Save',
	    'buttonCancelEdit' => 'Cancel',
		)
	);

	/** Intelligence Report * */
	$intelligenceReport = $componentManager->createComponent( 'Intelligence Report', 'Maven\\Admin\\Controllers\\IntelligenceReport' );

	$intelligenceReport->setDefaultAction( 'showForm' );
	$intelligenceReport->addAjaxAction( 'entryPoint' );
	$intelligenceReport->addStyleResource( 'toggleButtons' );
	$intelligenceReport->addStyleResource( 'tagsinput' );
	$intelligenceReport->addScriptResource( 'tagsinput' );
	$intelligenceReport->addScriptResource( 'bootstrap' );


	$intelligenceReport->addLocalizations( array(
	    'tabGeneral' => 'General',
	    'sendReportTo' => 'Send reports to',
	    'buttonSave' => 'Save',
	    'buttonCancelEdit' => 'Cancel',
		)
	);

	/** Roles * */
	$roles = $componentManager->createComponent( 'Roles', 'Maven\\Admin\\Controllers\\Roles' );

	$roles->setDefaultAction( 'showForm' );
	$roles->addAjaxAction( 'entryPoint' );
	$roles->addLocalizations( array(
	    'name' => 'Name',
	    'tabPages' => 'Pages',
	    'buttonSave' => 'Save',
	    'buttonCancelEdit' => 'Cancel',
	    'titleRoles' => 'Roles',
	    'buttonAddRole' => 'Add Role',
	    'columnName' => 'Name',
	    'buttonAddRole' => 'Add new Role',
	    'buttonEditRole' => 'Edit',
	    'buttonPrint' => 'Print',
	    'buttonDeleteRole' => 'Delete',
	    'deleteConfirmationMessage' => 'Are you sure you want to delete the Role?',
	    'roleDeleteWarning' => 'Warning!',
	    'roleDeleteWarningMessage' => 'This role will be removed from all events.<br/>This operation can\'t be undone!',
	    'roleDeleted' => 'Role deleted...',
	    'buttonCloseModal' => 'Close',
	    'buttonConfirmDelete' => 'Delete',
	    'buttonSaveRole' => 'Save',
	    'buttonCancelEditRole' => 'Cancel',
	    'tabEditRole' => 'Role'
		)
	);



	/** Profiles * */
	$profiles = $componentManager->createComponent( 'Profiles', 'Maven\\Admin\\Controllers\\Profiles' );
	$profiles->setDefaultAction( 'showForm' );
	$profiles->addAjaxAction( 'profileEntryPoint' );
	$profiles->addScriptResource( 'bootstrap' );
	$profiles->addScriptResource( 'require' );
	$profiles->addWpMedia();
	$profiles->addLocalizations( array(
	    'title' => 'Profiles',
	    'emptyResult' => 'You have no profiles yet. ',
	    'emptySearch' => 'No results.',
	    'columnEmail' => 'Email',
	    'columnFirstName' => 'First Name',
	    'columnLastName' => 'Last Name',
	    'buttonAdd' => 'Add new Profile',
	    'buttonEdit' => 'Edit',
	    'buttonPrint' => 'Print',
	    'buttonDelete' => 'Delete',
	    'deleteConfirmationMessage' => 'Are you sure you want to delete the Profile?',
	    'deleteWarning' => 'Warning!',
	    'deleteWarningMessage' => 'All related information will be deleted, like donors and attendes.<br/>This operation can\'t be undone!',
	    'deleted' => 'Profile deleted...',
	    'buttonCloseModal' => 'Close',
	    'buttonConfirmDelete' => 'Delete',
	    'buttonSave' => 'Save',
	    'buttonCancelEdit' => 'Cancel',
	    'buttonAddAddress' => 'Add Address',
	    'tabEditPersonalInfo' => 'Personal Info',
	    'tabEditPhoto' => 'Photo',
	    'tabEditAddress' => 'Address',
	    'tabEditUser' => 'WP User',
	    'tabEditRoles' => 'Roles',
	    'salutation' => 'Salutation',
	    'firstName' => 'First Name',
	    'lastName' => 'Last Name',
	    'email' => 'Email',
	    'phone' => 'Phone',
	    'company' => 'Company',
	    'notes' => 'Notes',
	    'wholesale' => 'Wholesale',
	    'adminNotes' => 'Admin Notes',
	    'createdOn' => 'Creation Date',
	    'lastUpdate' => 'Last Update',
	    'photo' => 'Photo',
	    'type' => 'Type',
	    'name' => 'Name',
	    'description' => 'Description',
	    'address' => 'Address',
	    'address2' => 'Address 2',
	    'neighborhood' => 'Neighborhood',
	    'city' => 'City',
	    'state' => 'State',
	    'country' => 'Country',
	    'zipcode' => 'ZIP',
	    'phoneAlternative' => 'Alternative Phone',
	    'primary' => 'Primary Address',
	    'primaryHelp' => 'If checked, this address will be used as default if other types are not set.',
	    'yes' => 'Yes',
	    'no' => 'No',
	    'registered' => 'Register',
	    'registeredMessage' => 'This profile is already associated with a Wordpress User.',
	    'username' => 'WP Username',
	    'password' => 'Password',
	    'confirm' => 'Confirm Password',
	    'roles' => 'Roles'
	) );

	/** Orders * */
	$orders = $componentManager->createComponent( 'Orders', 'Maven\\Admin\\Controllers\\Orders' );
	$orders->setDefaultAction( 'showForm' );
	$orders->addAjaxAction( 'orderEntryPoint' );
	$orders->addAjaxAction( 'orderStatsEntryPoint' );
	$orders->addAction( 'printOrder' );
	$orders->addScriptResource( 'bootstrap' );
	$orders->addScriptResource( 'require' );
	$orders->addLocalizations( array(
	    'title' => 'Orders',
	    'titleOrders' => 'Orders',
	    'emptyResult' => 'You have no orders yet. ',
	    'emptySearch' => 'No results.',
	    'columnStatus' => 'Status',
	    'columnNumber' => 'Number',
	    'columnOrderDate' => 'Date',
	    'columnTotal' => 'Total',
	    'columnCustomer' => 'Customer',
	    'allOrders' => 'All Orders',
	    'rangeLabel' => 'Custom Range',
	    'rangeApplyLabel' => 'Select',
	    'rangeFromLabel' => 'From',
	    'rangeToLabel' => 'To',
	    'noRange' => 'No Range Selected',
	    'today' => 'Today',
	    'yesterday' => 'Yesterday',
	    'lastSevenDays' => 'Last 7 Days',
	    'lastThirtyDays' => 'Last 30 Days',
	    'thisMonth' => 'This Month',
	    'lastMonth' => 'Last Month',
	    'selectOrderStatus' => 'Show all',
	    'buttonAddOrder' => 'Add new Order',
	    'buttonEdit' => 'Edit',
	    'buttonPrint' => 'Print',
	    'buttonDelete' => 'Delete',
	    'deleteConfirmationMessage' => 'Are you sure you want to delete the Order?',
	    'deleteWarning' => 'Warning!',
	    'deleteWarningMessage' => 'This order will be removed.<br/>This operation can\'t be undone!',
	    'deleted' => 'Order deleted...',
	    'buttonCloseModal' => 'Close',
	    'buttonConfirmDelete' => 'Delete',
	    'buttonSaveOrder' => 'Save',
	    'buttonCancelEditOrder' => 'Cancel',
	    'buttonAddStatus' => 'Add Status',
	    'tabEditOrder' => 'General',
	    'orderTitle' => 'Order',
	    'itemsOrdered' => 'Items Ordered',
	    'quantity' => 'Quantity',
	    'itemPrice' => 'Item Price',
	    'itemTotal' => 'Item Total',
	    'discount' => 'Discount',
	    'shipping' => 'Shipping',
	    'transactionId' => 'Transaction ID',
	    'notes' => 'Notes',
	    'extraInformation' => 'Extra Information',
	    'emptyExtraInformation' => 'No extra information...',
	    'creditCard' => 'Credit Card',
	    'emptyCreditCard' => 'No credit card information...',
	    'orderEvents' => 'Order Events',
	    'number' => 'Number',
	    'description' => 'Description',
	    'date' => 'Date',
	    'subtotal' => 'Subtotal',
	    'total' => 'Total',
	    'shippingMethod' => 'Shipping Method',
	    'shippingAmount' => 'Shipping Amount',
	    'discountAmount' => 'Discount Amount',
	    'contactTitle' => 'Contact',
	    'contactFirstName' => 'First Name',
	    'contactLastName' => 'Last Name',
	    'contactSalutation' => 'Salutation',
	    'contactEmail' => 'Email',
	    'contactCompany' => 'Company',
	    'contactPhone' => 'Phone',
	    'itemsTitle' => 'Items',
	    'name' => 'Name',
	    'amount' => 'Amount',
	    'removeOrderItem' => 'Remove',
	    'addNewOrderItemButton' => 'Add Order Item',
	    'contact' => 'Contact Info',
	    'shippingContact' => 'Shipping Contact',
	    'billingContact' => 'Billing Contact',
	    'firstLine' => 'First Line',
	    'secondLine' => 'Second Line',
	    'city' => 'City',
	    'state' => 'State',
	    'country' => 'Country',
	    'zipcode' => 'ZIP Code',
	    'yes' => 'Yes',
	    'no' => 'No',
	    'extraFields' => 'Extra Fields',
	    'orderShipping' => 'Shipment Information',
	    'carrier' => 'Carrier',
	    'trackingCode' => 'Tracking Code',
	    'trackingUrl' => 'Tracking Url',
	    'addShipmentNotice' => 'Add Shipment Notice',
	    'sendShipmentNotice' => 'Send',
	    'cancelShipmentNotice' => 'Cancel',
	    'unknownStatus' => 'Unknown Status',
	    'orders' => 'Orders',
	    'completedOrders' => 'Completed',
	    'sales' => 'Sales',
	    'averageSale' => 'Average Sale',
	    'totalCount' => 'Total Orders',
	    'totalCompletedCount' => 'Completed',
	    'totalSales' => 'Total Sales',
	    'required' => 'This field is required.'
	) );

	/** Promotions * */
	$promotions = $componentManager->createComponent( 'Promotions', 'Maven\\Admin\\Controllers\\Promotions' );
	$promotions->setDefaultAction( 'showForm' );
	$promotions->addAjaxAction( 'promotionEntryPoint' );
	$promotions->addAjaxAction( 'multiPromotionEntryPoint' );
	$promotions->addAjaxAction( 'exportEntryPoint' );
	$promotions->addScriptResource( 'bootstrap' );
	$promotions->addScriptResource( 'require' );
	$promotions->addLocalizations( array(
	    'title' => 'Promotions',
	    'titlePromotions' => 'Promotions',
	    'emptyResult' => 'You have no promotions yet. ',
	    'emptySearch' => 'No results.',
	    'columnCode' => 'Code',
	    'columnName' => 'Name',
	    'columnSection' => 'Section',
	    'columnFrom' => 'From',
	    'columnTo' => 'To',
	    'buttonAddPromotion' => 'Add new Promotion',
	    'buttonAddMultiplePromotions' => 'Add Multiple Promotions',
	    'buttonExport' => 'Export',
	    'buttonEdit' => 'Edit',
	    'buttonPrint' => 'Print',
	    'buttonDelete' => 'Delete',
	    'deleteConfirmationMessage' => 'Are you sure you want to delete the Promotion?',
	    'deleteWarning' => 'Warning!',
	    'deleteWarningMessage' => 'This promotion will be removed.<br/>This operation can\'t be undone!',
	    'deleted' => 'Promotion deleted...',
	    'buttonCloseModal' => 'Close',
	    'buttonConfirmDelete' => 'Delete',
	    'buttonSavePromotion' => 'Save',
	    'buttonCancelEditPromotion' => 'Cancel',
	    'tabEditPromotion' => 'General',
	    'promotionTitle' => 'Order',
	    'section' => 'Section',
	    'name' => 'Name',
	    'description' => 'Description',
	    'code' => 'Code',
	    'type' => 'Type',
	    'value' => 'Value',
	    'from' => 'From',
	    'to' => 'To',
	    'limitOfUse' => 'Limit of use',
	    'uses' => 'Uses',
	    'enabled' => 'Enabled',
	    'disabled' => 'Disabled',
	    'exclusive' => 'Exclusive',
	    'exclusiveTooltip' => 'If Exclusive, it cant be combined with other promotions',
	    'quantity' => 'Quantity',
	    'yes' => 'Yes',
	    'no' => 'No',
	    'unknownStatus' => 'Unknown Status',
	    'unlimited' => 'No Limit',
	    'cart' => 'Promotion apply to cart total amount.',
	    'item' => 'Promotion apply only to items subtotal amount.',
	    'shipping' => 'Promotion apply only to shipping ammount.'
	) );

	/** Taxes * */
	$taxes = $componentManager->createComponent( 'Taxes', 'Maven\\Admin\\Controllers\\Taxes' );
	$taxes->setDefaultAction( 'showForm' );
	$taxes->addAjaxAction( 'taxEntryPoint' );
	$taxes->addScriptResource( 'bootstrap' );
	$taxes->addScriptResource( 'require' );
	$taxes->addLocalizations( array(
	    'title' => 'Taxes',
	    'titleTaxes' => 'Taxes',
	    'emptyResult' => 'You have no taxes yet. ',
	    'emptySearch' => 'No results.',
	    'columnName' => 'Name',
	    'columnCountry' => 'Country',
	    'columnState' => 'State',
	    'columnValue' => 'Value',
	    'buttonAddTax' => 'Add new Tax',
	    'buttonEdit' => 'Edit',
	    'buttonPrint' => 'Print',
	    'buttonDelete' => 'Delete',
	    'deleteConfirmationMessage' => 'Are you sure you want to delete the Tax?',
	    'deleteWarning' => 'Warning!',
	    'deleteWarningMessage' => 'This tax will be removed.<br/>This operation can\'t be undone!',
	    'deleted' => 'Tax deleted...',
	    'buttonCloseModal' => 'Close',
	    'buttonConfirmDelete' => 'Delete',
	    'buttonSaveTax' => 'Save',
	    'buttonCancelEditTax' => 'Cancel',
	    'tabEditTax' => 'General',
	    'taxTitle' => 'Tax',
	    'name' => 'Name',
	    'country' => 'Country',
	    'state' => 'State',
	    'value' => 'Value',
	    'forShipping' => 'For Shipping',
	    'compound' => 'Compound',
	    'enabled' => 'Enabled',
	    'disabled' => 'Disabled',
	    'yes' => 'Yes',
	    'no' => 'No'
	) );

	/** Attributes * */
	$attributes = $componentManager->createComponent( 'Attributes', 'Maven\\Admin\\Controllers\\Attributes' );
	$attributes->setDefaultAction( 'showForm' );
	$attributes->addAjaxAction( 'attributeEntryPoint' );
	$attributes->addScriptResource( 'bootstrap' );
	$attributes->addScriptResource( 'require' );
	$attributes->addWpMedia();
	$attributes->addLocalizations( array(
	    'title' => 'Attributes',
	    'titleAttributes' => 'Attributes',
	    'emptyResult' => 'You have no attributes yet. ',
	    'emptySearch' => 'No results.',
	    'columnName' => 'Attribute',
	    'columnDefaultAmount' => 'Price',
	    'columnDefaultWholesaleAmount' => 'Wholesale Price',
	    'view' => 'View',
	    'buttonAddAttribute' => 'Add new Attribute',
	    'buttonEdit' => 'Edit',
	    'buttonPrint' => 'Print',
	    'buttonDelete' => 'Delete',
	    'deleteConfirmationMessage' => 'Are you sure you want to delete the Attribute?',
	    'deleteWarning' => 'Warning!',
	    'deleteWarningMessage' => 'This attribute will be removed.<br/>This operation can\'t be undone!',
	    'deleted' => 'Attribute deleted...',
	    'buttonCloseModal' => 'Close',
	    'buttonConfirmDelete' => 'Delete',
	    'buttonSaveAttribute' => 'Save',
	    'buttonCancelEditAttribute' => 'Cancel',
	    'tabEditAttribute' => 'Attribute',
	    'name' => 'Name',
	    'slug' => 'Slug',
	    'yes' => 'Yes',
	    'no' => 'No',
	    'description' => 'Description',
	    'defaultPrice' => 'Default Price',
	    'defaultWholesalePrice' => 'Default Wholesale Price'
	) );

	$menuManager = $director->getMenuManager( $registry );

	$menuManager->registerMenu( $settings, "Maven", $registry->getAssetsUrl() . "images/icon.png" );
	$menuManager->registerMenu( $https );
	$menuManager->registerMenu( $roles );
	$menuManager->registerMenu( $intelligenceReport );
	$menuManager->registerMenu( $profiles );
	$menuManager->registerMenu( $orders );
	$menuManager->registerMenu( $promotions );
	$menuManager->registerMenu( $taxes );
	$menuManager->registerMenu( $attributes );
} else {

	// TODO: We need to improve it
	$ssl = new \Maven\Core\Ssl();

	$hookManager->addWp( array( $ssl, 'forceSslCheckout' ) );

	$hookManager->addFilter( 'post_thumbnail_html', array( $ssl, 'forceSslImages' ) );
	$hookManager->addFilter( 'widget_text', array( $ssl, 'forceSslImages' ) );
	$hookManager->addFilter( 'wp_get_attachment_url', array( $ssl, 'forceSslImages' ) );
	$hookManager->addFilter( 'wp_get_attachment_image_attributes', array( $ssl, 'forceSslImages' ) );
	$hookManager->addFilter( 'wp_get_attachment_url', array( $ssl, 'forceSslImages' ) );
}
 
