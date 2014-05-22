<?php

namespace Maven\Core\Domain\IntelligenceReport;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


abstract class Element {
	
	private $emptyMessage;
	private $title;
	private $shortDesc;
	private $template;
	
	abstract function toHtml();
	
	public function getEmptyMessage() {
		return $this->emptyMessage;
	}

	public function setEmptyMessage( $emptyMessage ) {
		$this->emptyMessage = $emptyMessage;
	}


	public function getTitle() {
		return $this->title;
	}

	public function setTitle( $title ) {
		$this->title = $title;
	}

	public function getShortDesc() {
		return $this->shortDesc;
	}

	public function setShortDesc( $shortDesc ) {
		$this->shortDesc = $shortDesc;
	}

	public function processTemplate( $name, $element ){
		$registry = \Maven\Settings\MavenRegistry::instance();
		
		//Process the form
		$path = $registry->getTemplatePath()."/intelligence-report/";
		
		ob_start();
		//extract($order);
		require("{$path}{$name}.html");
		
		$this->template = ob_get_clean();
		
		return $this->template;
		
		
	}
	

}