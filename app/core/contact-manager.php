<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class ContactManager {


	private $mapper;

	public function __construct(  ) {

		$this->mapper = new Mappers\ContactMapper();
	}
	
	
	/**
	 * 
	 * @param int $id
	 * @return \Maven\Core\Domain\Contact
	 */
	public function get( $id ){
		
		return $this->mapper->get($id);
		
	}

}

