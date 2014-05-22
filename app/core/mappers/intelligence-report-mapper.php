<?php



namespace Maven\Core\Mappers;
use \Maven\Settings\Option,	Maven\Settings\OptionType;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class IntelligenceReportMapper extends \Maven\Core\Db\WpOptionMapper {

	const DailyReportRunKey = '_maven-common-daily-report-run';
	
	public function __construct() {

		parent::__construct( "intelligence-report" );
		
	}

	/**
	 * Return an Promotion object
	 * @param int $id
	 * @return \Maven\Core\Domain\Promotion
	 */
	public function getOptions(  ) {

		$existingsOptions = $this->getOption();
		
		$adminEmail = get_bloginfo( 'admin_email' );

		$defaultOptions = array(
			'sendReportTo' => new Option( "sendReportTo", "Send Report To", $adminEmail, '', OptionType::Input ),
			'enabled' => new Option( "enabled", "Enabled", false, '', OptionType::CheckBox ),
			'daysOfTheWeek' => new Option( "daysOfTheWeek", "Days of the week", array('monday','wednesday'), '', OptionType::Input )
			
		);
			
		
		if ( $existingsOptions ){
			foreach ( $existingsOptions as $option ){
				if ( isset( $defaultOptions[ $option->getName() ] ) && $defaultOptions[ $option->getName() ] !== "" ) {
					$defaultOptions[ $option->getName() ]->setValue( $option->getValue() );
				}
			}
		}
		
		return $defaultOptions;
		
	}
	
	public function getDaysOfTheWeek(){
		return $this->getValue('daysOfTheWeek');
	}
	
	/**
	 * Return a setting
	 * @param string $key
	 * @return null 
	 */
	public function getValue( $key ) {

		$options = $this->getOptions();
		
		if ( isset( $options[ $key ] ) ) {
			return $options[ $key ]->getValue();
		}

		return null;
	}
	
	private function getSetting( $key ){
		$options = $this->getOptions();
		
		if ( isset( $options[ $key ] ) ) {
			return $options[ $key ];
		}

		return false;
	}
	public function getSendReportTo(){
		return $this->getValue('sendReportTo');
	}
 
	
	public function saveOptions( $settings ){
		$this->updateOption($settings);
	}
	
	
	public function isEnabled(){
		
		$value = $this->getValue('enabled');
		
		if (  is_bool ( $value ) ){
			return $value;
		}
		
		if ( $value === "false" ) {
			return false;
		} else if ( $value === "true" ) {
			return true;
		} else {
			return false;
		}
	}

}