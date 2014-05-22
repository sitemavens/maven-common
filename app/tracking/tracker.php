<?php

namespace Maven\Tracking;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Tracker {

	public static function getAll() {

		$trackers = array();

		$trackers = \Maven\Core\HookManager::instance()->applyFilters( 'maven/trackers/register', $trackers );

		return $trackers;
	}

	public static function getTracker( $key ) {

		$tracker = null;

		switch ( strtolower( $key ) ) {
//			case "googleanalytics":
//				$tracker = new GoogleAnalyticsTracker();
//				break;
//			case "segment.io":
//				$tracker = new SegmentIoTracker();
//				break;
		}

		return $tracker;
	}

	public static function addTransaction( \Maven\Tracking\ECommerceTransaction $transaction ) {

		$settings = \Maven\Settings\MavenRegistry::instance();

		$trackers = $settings->getEnabledTrackers();

		$trackers = \Maven\Core\HookManager::instance()->applyFilters( 'maven/trackers/enabled', $trackers );

		foreach ( $trackers as $trackerKey => $tracker ) {

			if ( ! $tracker || ! ( $tracker instanceof \Maven\Tracking\BaseTracker) ) {
				$tracker = self::getTracker( $trackerKey );
			}

			if ( $tracker ) {
				$tracker->addTransaction( $transaction );
			}
		}
	}

	public static function addEvent( \Maven\Tracking\Event $event ) {

		$settings = \Maven\Settings\MavenRegistry::instance();

		$trackers = $settings->getEnabledTrackers();

		$trackers = \Maven\Core\HookManager::instance()->applyFilters( 'maven/trackers/enabled', $trackers );
		if ( is_array($trackers) ) {
			foreach ( $trackers as $trackerKey => $tracker ) {

				if ( ! $tracker || ! ( $tracker instanceof \Maven\Tracking\BaseTracker) ) {
					$tracker = self::getTracker( $trackerKey );
				}

				if ( $tracker ) {
					$tracker->addEvent( $event );
				}
			}
		}
	}

}
