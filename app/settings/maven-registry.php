<?php

namespace Maven\Settings;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

use Maven\Settings\Option;

class MavenRegistry extends WordpressRegistry {

	private static $instance;

	protected function __construct() {

		parent::__construct();
	}

	/**
	 * 
	 * @return \Maven\Settings\MavenRegistry
	 */
	static function instance() {
		if ( ! isset( self::$instance ) ) {
			$adminEmail = get_bloginfo( 'admin_email' );

			$defaultOptions = array(
			     new Option(
				    "emailProvider", "Email Provider", 'wordpress', '', OptionType::Input
			    ), new Option(
				    "exceptionNotification", "Exception Notification", $adminEmail, '', OptionType::Input
			    ), new Option(
				    "registeredPluginsGateway", "Registered plugins for gateway", '', '', OptionType::Input
			    ), new Option(
				    "registeredPluginsLicensing", "Registered plugins for licensing", '', '', OptionType::Input
			    ), new Option(
				    "activeGateway", "Active Gateway", 'default', '', OptionType::Input
			    ), new Option(
				    "recurringEnabled", "Recurring Enabled", false, '', OptionType::Input
			    ), new Option(
				    "enabledTrackers", "Enabled Trackers", array(
				'googleanalytics' => 0,
				'segment.io' => 0
				    ), '', OptionType::Input
			    ), new Option(
				    "enabledSocialNetworks", "Enabled Social Networks", array(
				'facebook' => 0
				    ), '', OptionType::Input
			    ), new Option(
				    "bccNotificationsTo", "BCC Notifications to", $adminEmail, '', OptionType::Input
			    ), new Option(
				    "organizationLogo", "Organization Logo", '', '', OptionType::Input
			    ), new Option(
				    "organizationName", "Organization Name", 'My Organization', '', OptionType::Input
			    ), new Option(
				    "contactEmail", "Contact Email", 'contact@myorganization.com', '', OptionType::Input
			    ), new Option(
				    "emailTemplate", "Email Template", 'simple-basic', '', OptionType::Input
			    ), new Option(
				    "signature", "Signature", 'My Organization team', '', OptionType::Input
			    ), new Option(
				    "senderEmail", "Sender Email", 'email@myorganization.com', '', OptionType::Input
			    ), new Option(
				    "senderName", "Sender Name", 'My Organization', '', OptionType::Input
			    ), new Option(
				    "activeMaillist", "Active Maillist", '', '', OptionType::Input
			    ), new Option(
				    "emailBackgroundColor", "Email template background color", '#6CAACC', '', OptionType::Input
			    ), new Option(
				    "httpsPages", "HTTPS Pages", '', '', OptionType::Input
			    ), new Option(
				    "loginPage", "Login Page", '', '', OptionType::Input
			    ), new Option(
				    "registrationThankYouPage", "Registration Thank You Page", '', '', OptionType::Input
			    ), new Option(
				    "gridRows", "Rows to show on Grid", '10', '', OptionType::Input
			    ), new Option(
				    "cartUrl", "Cart Url", 'cart/', '', OptionType::Input
			    )
			);

			self::$instance = new self( );
			self::$instance->setOptions( $defaultOptions );
		}

		return self::$instance;
	}

	public function getHttpsPages() {
		return $this->getValue( 'httpsPages' );
	}

	public function getActiveMaillist() {
		return $this->getValue( 'activeMaillist' );
	}

	public function getEmailProvider() {
		return $this->getValue( 'emailProvider' );
	}

	public function getSenderEmail() {
		return $this->getValue( 'senderEmail' );
	}

	public function getSenderName() {
		return $this->getValue( 'senderName' );
	}

	public function getExceptionNotification() {
		return $this->getValue( 'exceptionNotification' );
	}

	public function getActiveGateway() {
		return $this->getValue( 'activeGateway' );
	}

	public function getOrganizationLogo() {
		return $this->getValue( 'organizationLogo' );
	}

	public function getOrganizationLogoFullUrl() {
		return wp_get_attachment_url( $this->getOrganizationLogo() );
	}

	public function getSignature() {
		return $this->getValue( 'signature' );
	}

	public function getWebSiteUrl() {
		return get_site_url();
	}

	public function setLoginPage( $loginPage ) {
		$this->set( 'loginPage', $loginPage );
	}

	public function getOrganizationName() {
		return $this->getValue( 'organizationName' );
	}

	public function getContactEmail() {
		return $this->getValue( 'contactEmail' );
	}

	public function getEmailTemplate() {
		return $this->getValue( 'emailTemplate' );
	}

	public function getBccNotificationsTo() {
		return $this->getValue( 'bccNotificationsTo' );
	}

	public function isRecurringEnabled() {
		return $this->getValue( 'recurringEnabled' );
	}

	public function getEnabledTrackers() {
		$trackers = $this->getValue( 'enabledTrackers' );

		$enabledTrackers = array();

		foreach ( $trackers as $key => $value ) {
			if ( true === $value || 'true' === $value || '1' === $value || 1 === $value ) {
				$enabledTrackers[] = $key;
			}
		}

		return $enabledTrackers;
	}

	public function getEnabledSocialNetworks() {
		$socialNetworks = $this->getValue( 'enabledSocialNetworks' );

		$enabledSocialNetworks = array();

		foreach ( $socialNetworks as $key => $value ) {
			if ( true === $value || 'true' === $value || '1' === $value || 1 === $value ) {
				$enabledSocialNetworks[] = $key;
			}
		}

		return $enabledSocialNetworks;
	}

	public function registerPlugin( \Maven\Settings\Registry $registry ) {

		// Register the plugin for gateways
		$registedPlugins = $this->getValue( 'registeredPluginsGateway' );

		if ( ! is_array( $registedPlugins ) ) {
			$registedPlugins = array();
		}

		if ( ! isset( $registedPlugins[ $registry->getPluginKey() ] ) ) {
			//By default is testing mode
			$registedPlugins[ $registry->getPluginKey() ] = true;
		}

		$this->set( 'registeredPluginsGateway', $registedPlugins );

		// Register the plugin for licensing
		$registedPlugins = $this->getValue( 'registeredPluginsLicensing' );

		if ( ! is_array( $registedPlugins ) ) {
			$registedPlugins = array();
		}

		if ( ! isset( $registedPlugins[ $registry->getPluginKey() ] ) ) {
			//By default is testing mode
			$registedPlugins[ $registry->getPluginKey() ] = '';
		}

		$this->set( 'registeredPluginsLicensing', $registedPlugins );

		$this->addRegistry( $registry );
	}

	public function getLicensePlugin( \Maven\Settings\Registry $registry ) {

		$registedPlugins = $this->getRegisteredPluginsLicensing();

		if ( $registedPlugins && isset( $registedPlugins[ $registry->getPluginKey() ] ) )
			return $registedPlugins[ $registry->getPluginKey() ];

		return '';
	}

	public function getRegisteredPluginsLicensing() {
		return $this->getValue( 'registeredPluginsLicensing' );
	}

	public function isPluginTestingGatewayMode( \Maven\Settings\Registry $registry ) {

		$registedPlugins = $this->getValue( 'registeredPluginsGateway' );

		if ( $registedPlugins && isset( $registedPlugins[ $registry->getPluginKey() ] ) ) {
			return $registedPlugins[ $registry->getPluginKey() ];
		}

		return true;
	}

	public function getTimeZone() {

		if ( ! parent::getTimeZone() ) {

			$current_offset = get_option( 'gmt_offset' );
			$tzstring = get_option( 'timezone_string' );

			if ( empty( $tzstring ) ) { // Create a UTC+- zone if no timezone string exists
				if ( 0 == $current_offset )
					$tzstring = 'UTC+0';
				elseif ( $current_offset < 0 )
					$tzstring = 'UTC' . $current_offset;
				else
					$tzstring = 'UTC+' . $current_offset;
			}

			$allowed_zones = timezone_identifiers_list();

			if ( in_array( $tzstring, $allowed_zones ) ) {
				parent::setTimeZone( new \DateTimeZone( $tzstring ) );
			} else
				parent::setTimeZone( new \DateTimeZone( 'UTC' ) );
		}

		return parent::getTimeZone();
	}

	public function getOrderStatusImagesUrl() {
		return $this->getImagesUrl() . "order-status/";
	}

	public function getPromotionStatusImageUrl() {
		return $this->getImagesUrl() . "promotion-status/";
	}

	public function getProfileStatusImageUrl() {
		return $this->getImagesUrl() . "profile-status/";
	}

	public function getPrintUrl() {
		return "maven/print/";
	}

	public function getAutoLoginUrl() {
		return "maven/auto-login/";
	}

	public function getDateFormat() {

		if ( ! parent::getDateFormat() ) {
			parent::setDateFormat( get_option( 'date_format' ) );
		}

		return parent::getDateFormat();
	}

	public function getEmailThemesPath() {
		return $this->getPluginDir() . 'assets/email-templates/';
	}

	public function getEmailThemesUrl() {
		return $this->getPluginUrl() . 'assets/email-templates/';
	}

	public function getCurrentEmailThemePath() {
		return $this->getEmailThemesPath() . $this->getEmailTemplate() . ".html";
	}

	public function getNoPhotoUrl() {
		return $this->getPluginUrl() . 'assets/images/nophoto.jpg';
	}

	public function getEmailBackgroundColor() {
		return $this->getValue( 'emailBackgroundColor' );
	}

	public function getSecurityMetaKey() {
		return "_mvnSecurity";
	}

	public function getLoginPage() {
		return $this->getValue( 'loginPage' );
	}

	public function getRegistrationThankYouPage() {
		return $this->getValue( 'registrationThankYouPage' );
	}

	public function getGridRows() {
		return $this->getValue( 'gridRows' );
	}

	public function getCartUrl() {
		return $this->getValue( 'cartUrl' );
	}
	
	public function init() {

		// We need to build the logo path in the init method
		// Because we don't have the full path yet in the constructor.
		$this->set( 'organizationLogo', $this->getPluginUrl() . "assets/images/default-logo.gif" );

		parent::init();
	}

}
