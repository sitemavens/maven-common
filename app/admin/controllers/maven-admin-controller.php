<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

abstract class MavenAdminController extends \Maven\Core\Ui\AdminController{
	
	public function __construct(){
		
		parent::__construct( \Maven\Settings\MavenRegistry::instance() );
		
		// We set the message manager and the key generator
		//$this->setMessageManager( \Maven\Core\Message\MessageManager::getInstance( new \Maven\Core\Message\UserMessageKeyGenerator() ) );
	}
	
	
	
}