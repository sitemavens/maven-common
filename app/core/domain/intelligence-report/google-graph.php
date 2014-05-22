<?php

namespace Maven\Core\Domain\IntelligenceReport;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class GoogleGraph extends Element{
	
	private $url;
	private $width = "300";
	private $height = "225";
	
	
	public function __construct() {
		;
	}
	 
	
	public function toHtml(){
		
		return $this->processTemplate('google-graph', $this);
		
	}
	
	
	public function getUrl() {
		return $this->url;
	}

	public function setUrl( $url ) {
		$this->url = $url;
	}
	
	public function getWidth() {
		return $this->width;
	}

	public function setWidth( $width ) {
		$this->width = $width;
	}

	public function getHeight() {
		return $this->height;
	}

	public function setHeight( $height ) {
		$this->height = $height;
	}


	
}