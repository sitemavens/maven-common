<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class Seo extends MavenAdminController {

	public function __construct() {

		parent::__construct();
	}

	function save() {
		
	}

	public function cancel() {
		
	}

	public function showForm() {

		$this->getOutput()->setTitle( "SEO Settings" );

		$this->getOutput()->loadAdminView( "seo" );
	}

	public function showList() {
		
	}

}

