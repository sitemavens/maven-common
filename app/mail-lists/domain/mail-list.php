<?php

namespace Maven\MailLists\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class MailList extends \Maven\Core\DomainObject{
	
	private $name;
	
	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}
	
}