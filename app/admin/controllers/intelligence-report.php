<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class IntelligenceReport extends \Maven\Admin\Controllers\MavenAdminController{

	public function __construct() {
		parent::__construct();
	}
   
	public function admin_init(){

	}
	
	public function showForm() {
         
		$manager = new \Maven\Core\IntelligenceReportManager();
		
		$options = $manager->getOptions();
		
		$this->addJSONData('savedSettings', $options);
		
		
		$this->getOutput()->setTitle( "Settings" );

		$this->getOutput()->loadAdminView( "intelligence-report" );
	}
	
	public function entryPoint() {

		$event = $this->getRequest()->getProperty( "event" );
		$data  = $this->getRequest()->getProperty( "data" );

		switch ( $event ) {

			case "update":

				$this->updateOption( $data );
				
				$this->getOutput()->sendData('Success');
				
				break;

			case "read":
				$options = $this->getRegistry()->getOptions();
				$this->getOutput()->sendData( $options );
				break;
			case "updateCollection":
				if ( is_array( $data ) ){
					foreach( $data as $option ){
						$this->updateOption( $option );
						
					}
					
					$this->getOutput()->sendData('Success');
				}
				else
					$this->getOutput()->sendError('Invalid collection');
				break;
		}
	}
	
	
	public function updateOption( $optionToUpdate ) {

		if ( ! is_array( $optionToUpdate ) )
			return;

		$manager = new \Maven\Core\IntelligenceReportManager();
		
		// Get all the settings 
		$options = $manager->getOptions();

		foreach ( $options as $option ) {
			//$properties = $optionToUpdate[ 'properties' ];
			//$values = array_flip($optionToUpdate[ 'properties' ]);
			
			if (  ! isset( $optionToUpdate[ $option->getId() ] ) )
				continue;
			
			
			//$option->setValue( $optionToUpdate[ $option->getId() ]  );
			$value = $optionToUpdate[ $option->getId() ];
			
			switch ( $option->getType() ) {
				case \Maven\Settings\OptionType::CheckBox:
					if ( $value === 'false' || $value === false || $value === '' ) {
						$option->setValue( FALSE );
					} else {
						$option->setValue( TRUE );
					}
					break;
				default:$option->setValue( $value );
					break;
			}
			
		}
		
		$manager->saveOptions( $options );
		
		
	}
	
	public function showList() {
		
	}
	

}