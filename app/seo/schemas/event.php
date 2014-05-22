<?php

namespace Maven\Seo\Schemas;


// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;



class Event extends Thing{
	
	/**
	 *
	 * @var \Maven\Seo\Schemas\Person
	 */
	private $attendee;
	
	/**
	 *
	 * @var \Maven\Seo\Schemas\Person[]
	 */
	private $attendees;
	
	/**
	 * The duration of the item (movie, audio recording, event, etc.) in ISO 8601 date format.
	 * @var string 
	 */
	private $duration;
	
	/**
	 * The end date and time of the event (in ISO 8601 date format).
	 * @var string 
	 */
	private $endDate;
	
	
	/**
	 * The location of the event or organization.
	 * @var \Maven\Seo\Schemas\Place 
	 */
	private $location;
	
	/**
	 * An offer to sell this item—for example, an offer to sell a product, the DVD of a movie, or tickets to an event.
	 * @var \Maven\Seo\Schemas\Offer 
	 */
	private $offers;
	
	/**
	 * A performer at the event—for example, a presenter, musician, musical group or actor.
	 * @var \Maven\Seo\Schemas\Person 
	 */
	private $performer;
	
	private $performers;
	
	/**
	 * The start date and time of the event (in ISO 8601 date format).
	 * @var string 
	 */
	private $startDate;
	
	/**
	 * An Event that is part of this event. For example, a conference event includes many presentations, each are a subEvent of the conference.
	 * @var \Maven\Seo\Schemas\Event  
	 */
	private $subEvent;
	
	/**
	 * Events that are a part of this event. For example, a conference event includes many presentations, each are subEvents of the conference (legacy spelling; see singular form, subEvent).
	 * @var \Maven\Seo\Schemas\Event  
	 */
	private $subEvents;
	
	/**
	 * An event that this event is a part of. For example, a collection of individual music performances might each have a music festival as their superEvent.
	 * @var \Maven\Seo\Schemas\Event  
	 */
	private $superEvent;
	
	
	public function __construct() {
		
		$this->location = new \Maven\Seo\Schemas\Place();
		$this->attendee = new \Maven\Seo\Schemas\Person();
		$this->attendees = array();
		$this->offers = new \Maven\Seo\Schemas\Offer();
		
	}

	
	public function getAttendee() {
		return $this->attendee;
	}

	public function setAttendee( \Maven\Seo\Schemas\Person $attendee ) {
		$this->attendee = $attendee;
	}

	public function getAttendees() {
		return $this->attendees;
	}

	public function setAttendees( \Maven\Seo\Schemas\Person $attendees ) {
		$this->attendees = $attendees;
	}

	public function getDuration() {
		return $this->duration;
	}

	public function setDuration( $duration ) {
		$this->duration = $duration;
	}

	public function getEndDate() {
		return $this->endDate;
	}

	public function setEndDate( $endDate ) {
		$this->endDate = $endDate;
	}

	public function getLocation() {
		return $this->location;
	}

	public function setLocation( \Maven\Seo\Schemas\Place $location ) {
		$this->location = $location;
	}

	public function getOffers() {
		return $this->offers;
	}

	public function setOffers( \Maven\Seo\Schemas\Offer $offers ) {
		$this->offers = $offers;
	}

	public function getPerformer() {
		return $this->performer;
	}

	public function setPerformer( \Maven\Seo\Schemas\Person $performer ) {
		$this->performer = $performer;
	}

	public function getPerformers() {
		return $this->performers;
	}

	public function setPerformers( $performers ) {
		$this->performers = $performers;
	}

	public function getStartDate() {
		return $this->startDate;
	}

	public function setStartDate( $startDate ) {
		$this->startDate = $startDate;
	}

	public function getSubEvent() {
		return $this->subEvent;
	}

	public function setSubEvent( \Maven\Seo\Schemas\Event $subEvent ) {
		$this->subEvent = $subEvent;
	}

	public function getSubEvents() {
		return $this->subEvents;
	}

	public function setSubEvents( \Maven\Seo\Schemas\Event $subEvents ) {
		$this->subEvents = $subEvents;
	}

	public function getSuperEvent() {
		return $this->superEvent;
	}

	public function setSuperEvent( \Maven\Seo\Schemas\Event $superEvent ) {
		$this->superEvent = $superEvent;
	}

	/**
	 * 
	 * @param \Maven\Seo\Schemas\Person $attende
	 */
	public function addAttendee( \Maven\Seo\Schemas\Person $attende ){
		$this->attendees[] = $attende;
	}
	
	public function hasAttendees(){
		return count($this->attendees) > 0;
	}
	
	public function hasAttendee(){
		return ! ( $this->attendee->getName() );
	}
	
	
	
}