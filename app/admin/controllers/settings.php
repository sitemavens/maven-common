<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class Settings extends MavenAdminController {

	public function __construct() {
		parent::__construct();
	}
 
	public function registerRoutes(){
		return array();
	}
}

