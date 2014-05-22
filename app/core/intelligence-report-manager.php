<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class IntelligenceReportManager {

	private $mapper;

	public function __construct () {

		$this->mapper = new Mappers\IntelligenceReportMapper();
	}

	/**
	 * 
	 * @param int $id
	 * @return \Maven\Core\Domain\Contact
	 */
	public function getOptions () {

		return $this->mapper->getOptions();
	}

	public function getRunningDays () {
		return $this->mapper->getDaysOfTheWeek();
	}

	/**
	 * Save options
	 * @param array $options
	 */
	public function saveOptions ( $options ) {
		$this->mapper->saveOptions( $options );
	}

	public function getSendReportTo () {
		return $this->mapper->getSendReportTo();
	}

	public function hasToRun () {
		 
		\Maven\Loggers\Logger::log()->message( 'Maven/IntelligenceReportManager/hasToRun: Enabled: ' . var_export($this->mapper->isEnabled(),true));

		// First check if it is enabled
		if ( !$this->mapper->isEnabled() ) {
			return false;
		}

		// Check if is the day
		$days = $this->getRunningDays();

		if ( Utils::isEmpty( $days ) ) {
			$days = array();
		}

		\Maven\Loggers\Logger::log()->message( 'Maven/IntelligenceReportManager/hasToRun: Day of week: ' . in_array( strtolower( date( 'l' ) ), $days ) );

		return in_array( strtolower( date( 'l' ) ), $days );
	}

	public function sendDailyReport () {

		if ( !$this->hasToRun() ) {
			return false;
		}

		$data = array();

		$lastRun = new MavenDateTime();

		\Maven\Loggers\Logger::log()->message( 'Maven/IntelligenceReportManager/sendDailyReport: Before filter execution ' );

		$collectedData = apply_filters( 'maven\core\intelligenceReport:data', $data, $lastRun );


		$mavenSettings = \Maven\Settings\MavenRegistry::instance();

		$message = "";

		if ( !is_array( $collectedData ) ) {
			throw new \Maven\Exceptions\MavenException( "Data must be an array of elements" );
		}

		$data = array_merge( $this->getReportData(), $collectedData );

		//\Maven\Loggers\Logger::log()->message( 'Maven/IntelligenceReportManager/sendDailyReport: Read info: '.print_r($data,true) );

		\Maven\Loggers\Logger::log()->message( 'Maven/IntelligenceReportManager/sendDailyReport: Local report generated: ' . count( $data ) );

		foreach ( $data as $element ) {
			$message .= $element->toHtml();
		}

		$mail = \Maven\Mail\MailFactory::build();
		$mail->to( $this->getSendReportTo() )
				->message( $message )
				->subject( $mavenSettings->getLanguage()->__( $mavenSettings->getOrganizationName() . ': Intelligence Report' ) )
				->fromAccount( $mavenSettings->getSenderEmail() )
				->fromMessage( $mavenSettings->getSenderName() )
				->send();
	}

	private function getReportData () {
		$table = new \Maven\Core\Domain\IntelligenceReport\Table();

		$table->setTitle( 'Cart Activity' );

		$table->addColumn( "# of Carts" );
		$table->addColumn( "# of Carts Received" );
		$table->addColumn( "# of Carts Completed" );
		$table->addColumn( "# of Carts with Error" );


		$orderManager = new OrderManager();

		$countTotal = $orderManager->getCount( 'total' );
		$countError = $orderManager->getCount( 'error' );
		$countCompleted = $orderManager->getCount( 'completed' );
		$countReceived = $orderManager->getCount( 'received' );

		$table->addRow( array( $countTotal, $countReceived, $countCompleted, $countError ) );


		$data[] = $table;


		$gGraph = new \Maven\Core\Domain\IntelligenceReport\GoogleGraph();
		$gGraph->setTitle( 'Sales' );
		$gGraph->setUrl( "http://chart.googleapis.com/chart?chs=300x225&cht=p&chco=00A2FF|80C65A|FF0000&chd=t:{$countReceived},{$countCompleted},{$countError}&chdl=Received|Completed|Error" );


		$data[] = $gGraph;

		return $data;
	}

}
