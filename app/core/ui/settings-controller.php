<?php

namespace Maven\Core\UI;

class SettingsController extends AdminController{

	
	public function __construct( $registry ) {
		
		parent::__construct( $registry );
		
	}

	public function cancel() {
		
	}

	public function save() {
		
		$options = $this->getRegistry()->getOptions();
		foreach( $options as $option ){
			$option->setValue( $this->getRequest()->getProperty( $option->getName() ) );
		}
		
		$this->getRegistry()->saveOptions ( $options );
		
		$this->getMessageManager()->addRegularMessage("Settings saved successfully.");
		
		return \Maven\Core\UI\ActionStatus::redirect();
	
	}

	public function showForm() {
		
		$this->getOutput()->addData( 'options', $this->getRegistry()->getOptions() );
		
		$this->getOutput()->setTitle( "Settings" );
		
		$this->getOutput()->loadThemeView( "settings" );
		
		return \Maven\Core\UI\ActionStatus::stay();
		
	}

	public function showList() {
		
	}
	
	
	
} 