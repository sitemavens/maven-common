<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class Https extends MavenAdminController {

	public function __construct() {

		parent::__construct();
	}

	function save() {
		
	}

	public function cancel() {
		
	}

	public function showForm() {

		$httpsPages = $this->getRegistry()->getHttpsPages();
		if ( ! $httpsPages )
			$httpsPages = array();

		$wp_pages = \get_pages();
		$pages = array();
		foreach ( $wp_pages as $page ) {
			$pages[] = array(
			    'id' => $page->ID,
			    'title' => $page->post_title,
			    'url' => \get_permalink($page->ID),
			    'name' => $page->post_name
			);
		}

		$model = array( 'pages' => implode( ',', $httpsPages ) );

		$this->addJSONData( 'httpPages', $model );
		$this->addJSONData( 'pages', $pages );


		$this->getOutput()->setTitle( "HTTPS Settings" );

		$this->getOutput()->loadAdminView( "https" );
	}

	public function showList() {
		
	}

	public function entryPoint() {

		$event = $this->getRequest()->getProperty( "event" );
		$data = $this->getRequest()->getProperty( "data" );

		switch ( $event ) {

			case "update":

				$this->updateOption( $data );
				break;
		}
	}

	public function updateOption( $optionToUpdate ) {

		// Get all the settings 
		$options = $this->getRegistry()->getOptions();

		//var_dump( explode( ',', $optionToUpdate[ 'pages' ] ) );

		$options[ 'httpsPages' ]->setValue( explode( ',', $optionToUpdate[ 'pages' ] ) );

		$this->getRegistry()->saveOptions( $options );

		$this->getOutput()->sendData( "Settings udpated" );
	}

}
