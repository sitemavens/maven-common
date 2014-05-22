<?php

namespace Maven\Gateways;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

/**
 * Description of mvn-gateway-pro-manager
 *
 * @author mustela
 */
class GatewayFactory {

	/**
	 * Get a default gateway or you can choose one. 
	 * @param string $ey
	 * @return \Maven\Gateways\Gateway
	 */
	public static function getGateway ( \Maven\Settings\Registry $registry, $key = null ) {

		$mavenRegistry = \Maven\Settings\MavenRegistry::instance();

		if ( !$key ) {
			$key = $mavenRegistry->getActiveGateway()?$mavenRegistry->getActiveGateway():"dummy";

			$key = \Maven\Core\HookManager::instance()->applyFilters( 'maven/gateway/active', $key );
		}

		if ( !$key ) {
			throw new \Maven\Exceptions\MavenException( 'You need an active gateway' );
		}

		//Get the testing mode for the plugin 
		$testingMode = $mavenRegistry->isPluginTestingGatewayMode( $registry );
		$testingMode = \Maven\Core\HookManager::instance()->applyFilters( 'maven/gateway/testingMode', $testingMode );

		$gateways = self::getAll();

		if ( !is_array( $gateways ) ) {
			throw new \Maven\Exceptions\MavenException( 'There is no registered gateways' );
		}

		if ( isset( $gateways[ $key ] ) ) {
			$gateway = $gateways[ $key ];
		} else {
			$gateway = new DefaultGateway();
		}

		$gateway->setTestMode( $testingMode );

		return $gateway;
	}

	/**
	 * Return all the existsing gateways
	 * @return \Maven\Gateways\Gateway
	 */
	public static function getAll () {

		$gateways = array();
		$gateways[ 'authorize.net' ] = new AuthorizeNetGateway();
		$gateways[ 'offline' ] = new OfflineGateway();
		$gateways[ 'dummy' ] = new DummyGateway();
		$gateways[ 'default' ] = new DefaultGateway();
		$gateways[ 'navigate' ] = new NavigateGateway();

		$gateways = \Maven\Core\HookManager::instance()->applyFilters( 'maven/gateways/register', $gateways );

		return $gateways;
	}

	/**
	 * Check if the gateway has External process features.
	 * @param \Maven\Gateways\Gateway $gateway
	 * @return boolean
	 */
	public static function hasExternalProcessFeatures ( \Maven\Gateways\Gateway $gateway ) {

		if ( $gateway instanceof \Maven\Gateways\iExternalProcess )
			return true;

		return false;
	}

}
