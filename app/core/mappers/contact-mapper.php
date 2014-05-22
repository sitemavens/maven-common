<?php

namespace Maven\Core\Mappers;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class ContactMapper extends \Maven\Core\Mappers\ProfileMapper {


	public function __construct() {

		parent::__construct( "mvn_orders" );
	}


	/**
	 * Return a Contact object
	 * @param int $id
	 * @return \Maven\Core\Domain\Contact
	 */
	public function get( $id ) {

		$contact = new \Maven\Core\Domain\Contact();
		$contact->setProfileId( $id );
		$contact->setId( $id );
		
		$this->loadProfile( $contact );
		
		return $contact;
		
	}


}