<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class CronJobs {

	const CleanReceivedOrdersHook = "maven/orders/cleanReceivedOrders";
	const IntelligenceReportHook = "maven/intelligenceReport";
	
	public static function init () {

		$orderManager = new OrderManager();
		$intelligenceReportManager = new IntelligenceReportManager();

		HookManager::instance()->addWp(array( '\Maven\Core\CronJobs', 'setupCleanReceivedOrdersSchedule' ) );
		
		HookManager::instance()->addWp(array( '\Maven\Core\CronJobs', 'setupIntelligenceReportSchedule' ) );
		
		HookManager::instance()->addAction( self::CleanReceivedOrdersHook, array( $orderManager, 'cleanReceivedOrders' ) );
		
		HookManager::instance()->addAction( self::IntelligenceReportHook, array( $intelligenceReportManager, 'sendDailyReport' ) );

		HookManager::instance()->addFilter( 'cron_schedules', array( '\Maven\Core\CronJobs', 'addScheduleInterval' ) );
		
	}

	public static function setupCleanReceivedOrdersSchedule () {

		if ( !wp_next_scheduled( self::CleanReceivedOrdersHook ) ) {
			wp_schedule_event( time(), 'every24Hours', self::CleanReceivedOrdersHook );
		}
	}
	
	public static function setupIntelligenceReportSchedule () {

		
		if ( !wp_next_scheduled( self::IntelligenceReportHook ) ) {
			wp_schedule_event( strtotime( 'back of 7am'), 'every24Hours', self::IntelligenceReportHook );
		}
	}
	
	

	public static function addScheduleInterval ( $schedules ) {

		// add a 'weekly' schedule to the existing set
		$schedules[ 'every24Hours' ] = array(
			'interval' => 86400,
			'display' => __( 'Every 24 hs' )
		);


		return $schedules;
	}

}
