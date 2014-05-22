<?php

namespace Maven\SocialNetworks;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class Event {

	private $name;
	private $description;
	private $startTime;
	private $endTime;
	private $location;
	private $venue;
	private $picture;
	private $ticketUri;
	
	private $properties = array( );

	
	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription( $description ) {
		$this->description = $description;
	}

	public function getStartTime() {
		return $this->startTime;
	}

	public function setStartTime( $startTime ) {
		$this->startTime = $startTime;
	}

	public function getEndTime() {
		return $this->endTime;
	}

	public function setEndTime( $endTime ) {
		$this->endTime = $endTime;
	}

	public function getLocation() {
		return $this->location;
	}

	public function setLocation( $location ) {
		$this->location = $location;
	}

	public function getVenue() {
		return $this->venue;
	}

	public function setVenue( $venue ) {
		$this->venue = $venue;
	}

	public function getPicture() {
		return $this->picture;
	}

	public function setPicture( $picture ) {
		$this->picture = $picture;
	}

	public function getTicketUri() {
		return $this->ticketUri;
	}

	public function setTicketUri( $ticketUri ) {
		$this->ticketUri = $ticketUri;
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

