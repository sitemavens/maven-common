<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class Contact extends \Maven\Core\Domain\Profile {

	public function __construct( $id = false ) {
		
		parent::__construct( $id );
		
		$rules = array(
			
			
		);
	
		$this->setSanitizationRules( $rules );
	}
	
	public function sanitize(){
		
		parent::sanitize();
		
	}
	

}
