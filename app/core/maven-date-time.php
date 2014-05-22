<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Description of ExtDateTime
 *
 * @author mustela
 */
class MavenDateTime extends \DateTime {

	/**
	 *
	 * @var \Maven\Settings\Registry 
	 */
	private $registry = null;

	public function __construct( $time = null ) {

		$this->registry = \Maven\Settings\MavenRegistry::instance();

		parent::__construct( $time, $this->registry->getTimeZone() );
	}

	public function __toString() {
		return $this->mySqlFormatDateTime();
	}

	/**
	 * @param $interval
	 */
	public function subFromInterval( $interval ) {
		return $this->sub( new \DateInterval( $interval ) );
	}

	/**
	 * @param $interval
	 */
	public function subFromIntervalString( $interval ) {
		return $this->sub( \DateInterval::createFromDateString( $interval ) );
	}

	public function mySqlFormatDate() {

		return $this->format( "Y-m-d" );
	}

	public function mySqlFormatDateTime() {

		return $this->format( "Y-m-d H:i:s" );
	}

	public function mySqlTime() {

		return $this->format( "H:i:s" );
	}

	/**
	 * Retrieve the date in localized format, based on timestamp.
	 * @return string
	 */
	public function getDateFormated() {
		return date_i18n( $this->registry->getDateFormat() );
	}

	static function getMonths() {

		//TODO: We should have different languages here
		$months = array();
		$months[ 1 ] = "January";
		$months[ 2 ] = "February";
		$months[ 3 ] = "March";
		$months[ 4 ] = "April";
		$months[ 5 ] = "May";
		$months[ 6 ] = "June";
		$months[ 7 ] = "July";
		$months[ 8 ] = "August";
		$months[ 9 ] = "September";
		$months[ 10 ] = "October";
		$months[ 11 ] = "November";
		$months[ 12 ] = "December";

		return $months;
	}

	static function getYears() {

		//TODO: We should have the chance to send parameters to retrieve periods, for instance
		// 10 years before the current one.
		$years = array();
		$year = strftime( "%Y" );

		for ( $i = 1; $i <= 7; $i ++  ) {
			$years[ $year ] = "{$year}";
			$year = $year + 1;
		}

		return $years;
	}

	public static function getCurrentDayNumber() {

		return date( 'N' );
	}

	public static function getCurrentDayName() {

		return date( 'l' );
	}

	public static function getWPCurrentDateTime() {
		return current_time( 'mysql' );
	}

	public static function getWPCurrentDateTimeToInt() {
		return strtotime( self::getWPCurrentDateTime() );
	}

}
