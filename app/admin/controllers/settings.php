<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Settings extends MavenAdminController implements \Maven\Core\Interfaces\iView {

	public function __construct() {
		parent::__construct();
	}

	public function registerRoutes( $routes ) {

		$routes[ '/maven/settings' ] = array(
			array( array( $this, 'getSettings' ), \WP_JSON_Server::READABLE ),
			array( array( $this, 'edit' ), \WP_JSON_Server::EDITABLE | \WP_JSON_Server::ACCEPT_JSON ),
		);

		$routes[ '/maven/gateways' ] = array(
			array( array( $this, 'getGateways' ), \WP_JSON_Server::READABLE ),
			array( array( $this, 'saveGateways' ), \WP_JSON_Server::EDITABLE | \WP_JSON_Server::ACCEPT_JSON )
		);

		return $routes;
	}

	public function edit( $data ) {

		try {

			$settings = \Maven\Settings\MavenRegistry::instance()->getOptions();

			foreach ( $settings as $setting ) {
				if ( isset( $data[ $setting->getName() ] ) ) {
					$setting->setValue( $data[ $setting->getName() ] );
				}
			}

			\Maven\Settings\MavenRegistry::instance()->saveOptions( $settings );

			$this->getOutput()->sendApiSuccess( $settings, 'Settings Saved');
		} catch ( \Exception $e ) {
			//General exception, send general error
			$this->getOutput()->sendApiError( $data, $e->getMessage() );
		}
	}

	public function saveGateways( $data ) {
		try {
//			throw new \Exception('upss');
			$gateways = \Maven\Gateways\GatewayFactory::getAll();

			foreach ( $gateways as $gateway ) {

				foreach ( $data as $gateFromPost ) {
					if ( $gateFromPost[ 'key' ] === $gateway->getKey() && $gateFromPost[ 'hasSettings' ] ) {

						$settings = $gateway->getSettings();
						$settingsFromPost = $gateFromPost[ 'settings' ];

						foreach ( $settings as $setting ) {
							if ( isset( $settingsFromPost[ $setting->getName() ] ) ) {
								$setting->setValue( $settingsFromPost[ $setting->getName() ][ 'value' ] );
							}
						}

						$gateway->saveOptions( $settings );
					}
				}
			}
			$this->getOutput()->sendApiSuccess( $data, 'Gateway Settings saved' );
		} catch ( \Exception $e ) {
			//General exception, send general error
			$this->getOutput()->sendApiError( $data, $e->getMessage() );
		}
	}

	public function getView( $view ) {

		switch ( $view ) {
			case "settings":
				$this->addJSONData( "settingsCached", array( "test" => 1234, "chau" => false ) );
				return $this->getOutput()->getAdminView( "settings/{$view}" );
		}

		return $view;
	}

	public function getSettings() {

		$registry = \Maven\Settings\MavenRegistry::instance();

		$options = $registry->getOptions();
		$entity = array();
		foreach ( $options as $option ) {
			$entity[ $option->getName() ] = $option->getValue();
		}

		$this->getOutput()->sendApiResponse( $entity );
	}

	public function getGateways() {

		$gateways = \Maven\Gateways\GatewayFactory::getAll();

		$gatewaysData = array();
		foreach ( $gateways as $gateway ) {

			$settings = $gateway->getSettings();
			$data = array(
				'name' => $gateway->getName(),
				'key' => $gateway->getKey(),
				'settings' => $settings,
				'hasSettings' => \Maven\Core\Utils::isEmpty( $settings ) ? false : true
			);

			$gatewaysData[] = $data;
		}
		$this->getOutput()->sendApiResponse( $gatewaysData );
	}

}
