<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class Settings extends MavenAdminController {

	public function __construct() {

		parent::__construct();
	}

	function save() {
		
	}

	public function cancel() {
		
	}

	public function showForm() {


		$wpPagesDropDown = wp_dropdown_pages( array( 'echo' => false, 'name' => 'loginPage' ) );

		$this->addJSONData( 'wpPagesDropDown', $wpPagesDropDown );

		$wpThankYouPagesDropDown = wp_dropdown_pages( array( 'echo' => false, 'name' => 'registrationThankYouPage' ) );
		$this->addJSONData( 'wpThankYouPagesDropDown', $wpThankYouPagesDropDown );


		$options = $this->getRegistry()->getOptions();
		$options[ 'organizationLogoUrl' ] = new \Maven\Settings\Option( 'organizationLogoUrl', 'Organization Logo Label' );

		//Check the organizationLogo image 
		if ( $options[ 'organizationLogo' ]->getValue() )
			$options[ 'organizationLogoUrl' ]->setValue( $this->getRegistry()->getOrganizationLogoFullURl() );


		$this->addJSONData( 'options', $options );

		$this->readTemplates();

		$this->addGateways();

		$this->addTrackers();

		$this->addEmailProviders();

		$this->addSocialNetworks();

		$this->addMailLists();

		$this->getOutput()->setTitle( "Settings" );

		$this->getOutput()->loadAdminView( "settings" );
	}

	private function readTemplates() {

		$this->addJSONData( 'emailTemplates', array(
		    array( 'id' => 'simple-basic', 'name' => 'Basic', 'img' => $this->getRegistry()->getEmailThemesUrl() . "/simple-basic.png" ),
		    array( 'id' => 'simple-logo', 'name' => 'Basic with logo', 'img' => $this->getRegistry()->getEmailThemesUrl() . "/simple-logo.png" )
			)
		);
	}

	private function addGateways() {

		//Get all the gateways
		$gateways = \Maven\Gateways\GatewayFactory::getAll();
		$gatewaysSettings = array( );

		// We need to create an object with all the gateway properties
		foreach ( $gateways as $gateway ) {
			$settings = $gateway->getSettings();

			$row = array( 'id' => strtolower( $gateway->getName() ) );
			$row[ 'manageProfile' ] = $gateway->getManageProfile();

			foreach ( $settings as $setting ) {
				$row[ $setting->getName() ] = $setting->getValue();
			}

			$gatewaysSettings[ ] = $row;
		}

		$this->addJSONData( 'gateways', $gatewaysSettings );
	}

	private function addEmailProviders() {

		//Get all the emailProviders
		$providers = \Maven\Mail\MailFactory::getAll();
		$providersSettings = array( );

		// We need to create an object with all the gateway properties
		foreach ( $providers as $provider ) {
			$settings = $provider->getSettings();

			$row = array(
			    'id' => $provider->getId(),
			    'label' => $provider->getName()
			);


			$row[ "settings" ] = array( );
			foreach ( $settings as $setting ) {

				$row[ "settings" ][ $setting->getName() ] = array(
				    "id" => $setting->getName(),
				    "html" => $setting->getRenderedCode(),
				    "label" => $setting->getLabel(),
				    "value" => $setting->getValue()
				);
			}

			$providersSettings[ ] = $row;
		}

		$this->addJSONData( 'emailProviders', $providersSettings );
	}

	private function addMailLists() {

		//Get all the emailProviders
		$mailLists = \Maven\MailLists\MailListFactory::getAll();
		$mailListsSettings[ ] = array(
		    'id' => '',
		    'label' => ''
		);

		// We need to create an object with all the gateway properties
		foreach ( $mailLists as $provider ) {
			$settings = $provider->getSettings();

			$row = array(
			    'id' => $provider->getId(),
			    'label' => $provider->getName()
			);


			$row[ "settings" ] = array( );
			foreach ( $settings as $setting ) {

				$row[ "settings" ][ $setting->getName() ] = array(
				    "id" => $setting->getName(),
				    "html" => $setting->getRenderedCode(),
				    "label" => $setting->getLabel(),
				    "value" => $setting->getValue()
				);
			}

			$mailListsSettings[ ] = $row;
		}

		$this->addJSONData( 'mailLists', $mailListsSettings );
	}

	private function addTrackers() {

		//Get all the gateways
//		$trackers = \Maven\Tracking\Tracker::getAll();
//
//		$trackerSettings = array( );
//
//		// We need to create an object with all the gateway properties
//		foreach ( $trackers as $tracker ) {
//			$settings = $tracker->getSettings();
//
//			$row = array( 'id' => strtolower( $tracker->getName() ) );
//
//			foreach ( $settings as $setting ) {
//				$row[ $setting->getName() ] = $setting->getValue();
//			}
//
//			$trackerSettings[ ] = $row;
//		}

		$this->addJSONData( 'trackers', array() );
	}

	private function addSocialNetworks() {

		//Get all the gateways
		$socialNetworks = \Maven\SocialNetworks\SocialNetwork::getAll();

		$socialNetworksSettings = array( );

		// We need to create an object with all the gateway properties
		foreach ( $socialNetworks as $socialNetwork ) {
			$settings = $socialNetwork->getSettings();

			$row = array( 'id' => strtolower( $socialNetwork->getName() ) );

			foreach ( $settings as $setting ) {
				$row[ $setting->getName() ] = $setting->getValue();
			}

			$socialNetworksSettings[ ] = $row;
		}

		$this->addJSONData( 'socialNetworks', $socialNetworksSettings );
	}

	public function entryPointGateways() {

		$event = $this->getRequest()->getProperty( "event" );
		$data = $this->getRequest()->getProperty( "data" );

		switch ( $event ) {

			case "update":

				$this->updateGateways( $data );
				break;
		}
	}

	public function emailEntryPoint() {

		$event = $this->getRequest()->getProperty( "event" );
		$data = $this->getRequest()->getProperty( "data" );

		switch ( $event ) {

			case "create":

				$mail = \Maven\Mail\MailFactory::build( $data[ 'emailProvider' ] );

				
				$result = $mail->bcc( $data[ 'bcc' ] )
					->cc( $data[ 'cc' ] )
					->to( $data[ 'to' ] )
					->message( $data[ 'message' ] )
					->subject( $data[ 'subject' ] )
					->fromAccount( 'noreply@maven.com' )
					->fromMessage( 'Maven Test Message' )
					->send();

				if ( $result ) {
					$this->getOutput()->sendData( 'Success' );
				} else {
					$this->getOutput()->sendError( $mail->getErrorDescription() );
				}

				break;
		}
	}

	public function entryPointLicense() {

		$event = $this->getRequest()->getProperty( "event" );
		$data = $this->getRequest()->getProperty( "data" );

		switch ( $event ) {

			case "create":
			case "update":

				/* if ( !isset( $data[ 'value' ] ) || !$data[ 'value' ] )
				  $this->getOutput()->sendError( $this->__( 'You need to provide a valid license key' ) ); */

				if ( ! isset( $data[ 'id' ] ) || ! $data[ 'id' ] )
					$this->getOutput()->sendError( $this->__( 'You need to provide a valid plugin key' ) );

				$registry = $this->getRegistry()->getPluginRegistry( $data[ 'id' ] );

				if ( ! $registry )
					$this->getOutput()->sendError( $this->__( 'The plugin your are looking for isn\'t registered' ) );

				if ( isset( $data[ 'value' ] ) &&  $data[ 'value' ] ) {
					$result = \Maven\Core\PluginUpdater::activateLicense( $data[ 'value' ], $registry );

					if ( $result ) {
						$this->updateLicenseOption( $data[ 'id' ], $data[ 'value' ] );
						$this->getOutput()->sendData( 'Success' );
					} else {
						$this->getOutput()->sendError( 'Invalid license key' );
					}
				} else {
					//its a deactivation
					$this->updateLicenseOption( $data[ 'id' ], $data[ 'value' ] );
					$this->getOutput()->sendData( 'Success' );
				}
				break;
		}
	}

	public function entryPointMailLists() {

		$event = $this->getRequest()->getProperty( "event" );
		$data = $this->getRequest()->getProperty( "data" );

		switch ( $event ) {

			case "update":

				$this->updateMailLists( $data );
				break;
		}
	}

	public function entryPointMailProviders() {

		$event = $this->getRequest()->getProperty( "event" );
		$data = $this->getRequest()->getProperty( "data" );

		switch ( $event ) {

			case "update":

				$this->updateMailProviders( $data );
				break;
		}
	}

	public function updateMailLists( $providersArray ) {

		// First we need to get all the providers
		$providers = \Maven\MailLists\MailListFactory::getAll();

		foreach ( $providers as $provider ) {

			if ( isset( $providersArray[ $provider->getId() ] ) ) {

				$settings = $provider->getSettings();

				foreach ( $settings as $setting ) {
					if ( isset( $providersArray[ $provider->getId() ][ $setting->getName() ] ) )
						$setting->setValue( $providersArray[ $provider->getId() ][ $setting->getName() ][ 'value' ] );
				}

				$provider->saveOptions( $settings );
			}
		}
		$this->updateOption( $providersArray );

		$this->getOutput()->sendData( 'Success' );
	}

	public function updateMailProviders( $providersArray ) {

		// First we need to get all the providers
		$providers = \Maven\Mail\MailFactory::getAll();

		foreach ( $providers as $provider ) {

			if ( isset( $providersArray[ $provider->getId() ] ) ) {

				$settings = $provider->getSettings();

				foreach ( $settings as $setting ) {
					if ( isset( $providersArray[ $provider->getId() ][ $setting->getName() ] ) )
						$setting->setValue( $providersArray[ $provider->getId() ][ $setting->getName() ][ 'value' ] );
				}

				$provider->saveOptions( $settings );
			}
		}
		$this->updateOption( $providersArray );

		$this->getOutput()->sendData( 'Success' );
	}

	public function entryPointTrackers() {

		$event = $this->getRequest()->getProperty( "event" );
		$data = $this->getRequest()->getProperty( "data" );

		switch ( $event ) {

			case "update":

				$this->updateTrackers( $data );
				break;
			case "updateCollection":
				if ( is_array( $data ) ) {
					foreach ( $data as $tracker ) {
						$this->updateTrackers( $tracker );
					}

					$this->getOutput()->sendData( 'Success' );
				}
				else
					$this->getOutput()->sendError( 'Invalid collection' );
				break;
		}
	}

	public function entryPointSocialNetworks() {

		$event = $this->getRequest()->getProperty( "event" );
		$data = $this->getRequest()->getProperty( "data" );

		switch ( $event ) {

			case "update":

				$this->updateSocialNetwork( $data );
				break;
			case "updateCollection":
				if ( is_array( $data ) ) {
					foreach ( $data as $socialNetwork ) {
						$this->updateSocialNetwork( $socialNetwork );
					}

					$this->getOutput()->sendData( 'Success' );
				}
				else
					$this->getOutput()->sendError( 'Invalid collection' );
				break;
		}
	}

	public function entryPoint() {

		$event = $this->getRequest()->getProperty( "event" );
		$data = $this->getRequest()->getProperty( "data" );

		switch ( $event ) {

			case "update":

				$this->updateOption( $data );
				$this->getOutput()->sendData( 'Success' );
				break;

			case "read":
				$options = $this->getRegistry()->getOptions();
				$this->getOutput()->sendData( $options );
				break;
			case "updateCollection":
				if ( is_array( $data ) ) {
					foreach ( $data as $gate ) {
						$this->updateGateways( $gate );
					}

					$this->getOutput()->sendData( 'Success' );
				}
				else
					$this->getOutput()->sendError( 'Invalid collection' );
				break;
		}
	}

	public function updateTrackers( $trackerToUpdate ) {

//		if ( $trackerToUpdate[ 'id' ] != 'segment.io' )
//			return;

		$tracker = \Maven\Tracking\Tracker::getTracker( $trackerToUpdate[ 'id' ] );
		$settings = $tracker->getSettings();

		foreach ( $settings as $setting ) {
			if ( isset( $trackerToUpdate[ $setting->getName() ] ) )
				$setting->setValue( $trackerToUpdate[ $setting->getName() ] );
		}

		$tracker->saveOptions( $settings );
		$this->getOutput()->sendData( 'Success' );
	}

	public function updateSocialNetworks( $socialNetworkToUpdate ) {

//		if ( $socialNetworkToUpdate[ 'id' ]!='segment.io')
//			return;

		$socialNetwork = \Maven\SocialNetworks\SocialNetwork::getSocialNetwork( $socialNetworkToUpdate[ 'id' ] );
		$settings = $socialNetwork->getSettings();

		foreach ( $settings as $setting ) {
			if ( isset( $socialNetworkToUpdate[ $setting->getName() ] ) )
				$setting->setValue( $socialNetworkToUpdate[ $setting->getName() ] );
		}

		$socialNetwork->saveOptions( $settings );
		$this->getOutput()->sendData( 'Success' );
	}

	public function updateGateways( $gatewayToUpdate ) {

		if ( ! $gatewayToUpdate || ! isset( $gatewayToUpdate[ 'id' ] ) )
			return;

		$gateway = \Maven\Gateways\GatewayFactory::getGateway( \Maven\Settings\MavenRegistry::instance(), $gatewayToUpdate[ 'id' ] );

		$settings = $gateway->getSettings();
		foreach ( $settings as $setting ) {
			if ( isset( $gatewayToUpdate[ $setting->getName() ] ) )
				$setting->setValue( $gatewayToUpdate[ $setting->getName() ] );
		}
		//var_dump($settings);die();
		$gateway->saveOptions( $settings );
	}

	public function updateLicenseOption( $pluginKey, $license ) {

		// Get all the settings 
		$options = $this->getRegistry()->getOptions();

		foreach ( $options as $option ) {

			if ( $option->getId() == 'registeredPluginsLicensing' ) {

				$plugins = $option->getValue();
				$plugins[ $pluginKey ] = $license;

				$option->setValue( $plugins );

				break;
			}
		}


		$this->getRegistry()->saveOptions( $options );
	}

	public function updateOption( $optionToUpdate ) {


		// Get all the settings 
		$options = $this->getRegistry()->getOptions();

		foreach ( $options as $option ) {

			if ( ! isset( $optionToUpdate[ $option->getId() ] ) )
				continue;


			$option->setValue( $optionToUpdate[ $option->getId() ] );
		}


		$this->getRegistry()->saveOptions( $options );
	}

	public function updateAll() {

		$models = $this->getRequest()->getProperty( 'models' );

		// Get all the settings 
		$options = $this->getRegistry()->getOptions();

		foreach ( $options as $option ) {
			foreach ( $models as $key => $model ) {
				if ( $model[ 'id' ] == $option->getId() ) {

					$option->setValue( $model[ 'value' ] );
					unset( $models[ $key ] );
					break;
				}
			}
		}

		$this->getRegistry()->saveOptions( $options );

		$this->getOutput()->sendData( "Settings udpated" );
	}

	public function showList() {
		
	}

}

