<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class PrintHandler {

	private static $entryPointVar = 'maven_print_handler';

	public function __construct() {
		;
	}

	public static function init() {

		$mavenSettings = \Maven\Settings\MavenRegistry::instance();

		add_rewrite_rule( "^{$mavenSettings->getPrintUrl()}([^/]*)/([^/]*)/?", "index.php?" . self::$entryPointVar . '=1&obj=$matches[1]&identifier=$matches[2]', 'top' );
	}

	public static function queryVars( $query_vars ) {

		$query_vars[] = self::$entryPointVar;
		$query_vars[] = 'obj';
		$query_vars[] = 'identifier';

		return $query_vars;
	}

	public static function parseRequest( &$wp ) {

		if ( array_key_exists( self::$entryPointVar, $wp->query_vars ) ) {

			//$request = \Maven\Core\Request::current();
			$objToPrint = isset( $wp->query_vars[ 'obj' ] ) ? $wp->query_vars[ 'obj' ] : false;
			$identifier = isset( $wp->query_vars[ 'identifier' ] ) ? $wp->query_vars[ 'identifier' ] : false;
			// Check if there is any object to print
			if ( ! $objToPrint ) {
				return;
			}
			if ( ! $identifier ) {
				die( 'Missing identifier' );
			}

			switch ( $objToPrint ) {
				case "order":

					//check if user has permitions
					if ( \current_user_can( 'manage_options' ) ) {

						$orderManager = new OrderManager();
						$order = $orderManager->get( $identifier );

						$mavenSettings = \Maven\Settings\MavenRegistry::instance();

						$url = $mavenSettings->getTemplateUrl() . 'order-print';
						$organizationName = $mavenSettings->getOrganizationName();
						$organizationSignature = $mavenSettings->getSignature();

						switch ( $order->getStatus() ) {
							case OrderStatusManager::getCompletedStatus():
							case OrderStatusManager::getReadyToShipStatus():
							case OrderStatusManager::getShippedStatus():
								$status = 'paid';
								break;
							case OrderStatusManager::getReceivedStatus():
							case OrderStatusManager::getProcessingStatus():
							case OrderStatusManager::getOnHoldStatus():
								$status = 'new';
								break;
							case OrderStatusManager::getErrorStatus():
							case OrderStatusManager::getCancelledStatus():
							case OrderStatusManager::getDeclinedStatus():
							case OrderStatusManager::getRefundedStatus():
							case OrderStatusManager::getVoidedStatus():
								$status = 'unpaid';
								break;
							default:
								$status = 'paid';
								break;
						}

						ob_start();
						require($mavenSettings->getTemplatePath() . 'order-print/invoice.html');
						
						$orderPrinted = ob_get_clean();
						die( $orderPrinted );
					} else {
						die( "You don't have permissions to access this page." );
					}
					break;
				default:
					die( 'No se encontro el objeto a imprimir' );
			}
		}

		return;
	}

}
