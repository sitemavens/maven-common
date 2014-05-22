<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Address extends \Maven\Core\DomainObject {

	private $firstLine;
	private $secondLine;
	private $city;
	private $state;
	private $country;
	private $zipcode;
	private $type;
	private $neighborhood;
	private $primary = false;
	private $name;
	private $description;
	private $profileId;
	private $notes;
	private $phone;
	private $phoneAlternative;

	public function __construct( $id = false ) {

		parent::__construct( $id );

		$rules = array(
		    'name' => \Maven\Core\SanitizationRule::Text,
		    'description' => \Maven\Core\SanitizationRule::Text,
		    'firstLine' => \Maven\Core\SanitizationRule::Text,
		    'secondLine' => \Maven\Core\SanitizationRule::Text,
		    'city' => \Maven\Core\SanitizationRule::Text,
		    'state' => \Maven\Core\SanitizationRule::Text,
		    'country' => \Maven\Core\SanitizationRule::Text,
		    'zipcode' => \Maven\Core\SanitizationRule::Text,
		    'neighborhood' => \Maven\Core\SanitizationRule::Text,
		    'primary' => \Maven\Core\SanitizationRule::Boolean,
		    'type' => \Maven\Core\SanitizationRule::Slug,
		    'phone' => \Maven\Core\SanitizationRule::Text,
		    'phoneAlternative' => \Maven\Core\SanitizationRule::Text,
		    'notes' => \Maven\Core\SanitizationRule::Text,
		    'profileId' => \Maven\Core\SanitizationRule::Integer
		);

		$this->setSanitizationRules( $rules );
	}

	public function isPrimary() {
		return $this->primary;
	}

	public function setPrimary( $primary ) {
		if ( $primary === 'false' || $primary === false || $primary === '0' ) {
			$this->primary = FALSE;
		} else {
			$this->primary = TRUE;
		}
	}

	public function getNeighborhood() {
		return $this->neighborhood;
	}

	public function setNeighborhood( $neighborhood ) {
		$this->neighborhood = $neighborhood;
	}

	public function getType() {
		return $this->type;
	}

	public function setType( $type ) {
		$this->type = $type;
	}

	public function getFirstLine() {
		return $this->firstLine;
	}

	public function setFirstLine( $firstLine ) {
		$this->firstLine = $firstLine;
	}

	public function getSecondLine() {
		return $this->secondLine;
	}

	public function setSecondLine( $secondLine ) {
		$this->secondLine = $secondLine;
	}

	public function getCity() {
		return $this->city;
	}

	public function setCity( $city ) {
		$this->city = $city;
	}

	public function getState() {
		return $this->state;
	}

	public function setState( $state ) {
		$this->state = $state;
	}

	public function getCountry() {
		return $this->country;
	}

	public function setCountry( $country ) {
		$this->country = $country;
	}

	public function getZipcode() {
		return $this->zipcode;
	}

	public function setZipcode( $zipcode ) {
		$this->zipcode = $zipcode;
	}

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

	public function getProfileId() {
		return $this->profileId;
	}

	public function setProfileId( $profileId ) {
		$this->profileId = $profileId;
	}

	public function getAddress() {
		return trim( $this->firstLine . " " . $this->secondLine );
	}

	public function getFullAddress() {
		return trim( $this->firstLine . " " . $this->secondLine . " " . $this->city . " " . $this->state . " " . $this->country . " " . $this->zipcode );
	}

	public function getNotes() {
		return $this->notes;
	}

	public function setNotes( $notes ) {
		$this->notes = $notes;
	}

	public function getPhone() {
		return $this->phone;
	}

	public function setPhone( $phone ) {
		$this->phone = $phone;
	}

	public function getPhoneAlternative() {
		return $this->phoneAlternative;
	}

	public function setPhoneAlternative( $phoneAlternative ) {
		$this->phoneAlternative = $phoneAlternative;
	}

	public function convertToBilling() {
		$this->setType( AddressType::Billing );
	}

	public function convertToShipping() {
		$this->setType( AddressType::Shipping );
	}

	public function convertToHome() {
		$this->setType( AddressType::Home );
	}

	public function convertToWork() {
		$this->setType( AddressType::Work );
	}

	/**
	 * Copy an existing address
	 * @return \Maven\Core\Domain\Address
	 */
	public function copy() {

		$address = new Address();

		$address->setCity( $this->getCity() );
		$address->setCountry( $this->getCountry() );
		$address->setFirstLine( $this->getFirstLine() );
		$address->setDescription( $this->getDescription() );
		$address->setNeighborhood( $this->getNeighborhood() );
		$address->setNotes( $this->getNotes() );
		$address->setPhone( $this->getPhone() );
		$address->setSecondLine( $this->getSecondLine() );
		$address->setState( $this->getState() );
		$address->setType( $this->getType() );
		$address->setPhoneAlternative( $this->getPhoneAlternative() );
		$address->setZipcode( $this->getZipcode() );
		$address->setProfileId( $this->getProfileId() );
		$address->setPrimary( $this->isPrimary() );

		return $address;
	}

}
