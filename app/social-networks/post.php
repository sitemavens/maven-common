<?php

namespace Maven\SocialNetworks;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class Post {

	private $name;
	private $picture;
	private $link;
	private $message;
	private $caption;
	private $description;
	private $properties = array( );

	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}
	
	public function getPicture() {
		return $this->picture;
	}

	public function setPicture( $picture ) {
		$this->picture = $picture;
	}

	public function getLink() {
		return $this->link;
	}

	public function setLink( $link ) {
		$this->link = $link;
	}

	public function getMessage() {
		return $this->message;
	}

	public function setMessage( $message ) {
		$this->message = $message;
	}

	public function getCaption() {
		return $this->caption;
	}

	public function setCaption( $caption ) {
		$this->caption = $caption;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription( $description ) {
		$this->description = $description;
	}

	public function getProperties() {
		return $this->properties;
	}

	public function setProperties( $properties ) {
		$this->properties = $properties;
	}

	public function addProperty( $key, $value ) {
		$this->properties[ $key ] = $value;
	}

}

