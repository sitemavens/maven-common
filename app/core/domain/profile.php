<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

use \Maven\Core\Domain\AddressType;
use \Maven\Core\Utils;

class Profile extends \Maven\Core\DomainObject {

	protected $description = "";
	protected $userId;
	protected $salutation;
	protected $firstName;
	protected $lastName;
	protected $sex;
	protected $profileImage;
	protected $profileId;
	protected $maillist;
	protected $website;
	protected $company;
	protected $twitter;
	protected $facebook;
	protected $googlePlus;
	protected $linkedIn;
	private $statusImageUrl;
	protected $wholesale;
	private $lastUpdate;
	private $createdOn;

	/**
	 *
	 * @var \Maven\Core\Domain\Address[] 
	 * @collectionType: \Maven\Core\Domain\Address
	 */
	protected $addresses = array();
	protected $profileImageUrl;
	protected $userName;

	/**
	 * It is used to clasify the donation, for instance public or protected, or associate it to an specific form
	 * @var type 
	 */
	protected $email;
	protected $phone;
	protected $extraInfo = array();

	/**
	 * Some aditional information
	 * @protected string 
	 */
	protected $notes = "";

	/**
	 * Some aditional information
	 * @protected string 
	 */
	protected $adminNotes = "";

	/**
	 *
	 * @var \Maven\Core\Domain\Role[] 
	 * @collectionType: \Maven\Core\Domain\Role
	 */
	protected $roles = array();

	/**
	 *
	 * @var \Maven\Core\Domain\WhishlistItem[] 
	 * @collectionType: \Maven\Core\Domain\WhishlistItem
	 */
	protected $whishlist = array();

	public function __construct( $id = false ) {

		parent::__construct( $id );

		$rules = array(
		    'salutation' => \Maven\Core\SanitizationRule::Text,
		    'email' => \Maven\Core\SanitizationRule::Email,
		    'firstName' => \Maven\Core\SanitizationRule::Text,
		    'lastName' => \Maven\Core\SanitizationRule::Text,
		    'sex' => \Maven\Core\SanitizationRule::Text,
		    'profileImage' => \Maven\Core\SanitizationRule::Integer,
		    'profileId' => \Maven\Core\SanitizationRule::Integer,
		    'maillist' => \Maven\Core\SanitizationRule::Text,
		    'website' => \Maven\Core\SanitizationRule::Text,
		    'company' => \Maven\Core\SanitizationRule::Text,
		    'twitter' => \Maven\Core\SanitizationRule::URL,
		    'facebook' => \Maven\Core\SanitizationRule::URL,
		    'googlePlus' => \Maven\Core\SanitizationRule::URL,
		    'linkedIn' => \Maven\Core\SanitizationRule::URL,
		    'phone' => \Maven\Core\SanitizationRule::Text,
		    'notes' => \Maven\Core\SanitizationRule::TextWithHtml,
		    'adminNotes' => \Maven\Core\SanitizationRule::TextWithHtml,
		    'userId' => \Maven\Core\SanitizationRule::Integer,
		    'wholesale' => \Maven\Core\SanitizationRule::Boolean,
		    'lastUpdate' => \Maven\Core\SanitizationRule::DateTime
		);


		$this->setSanitizationRules( $rules );
	}

	public function getMaillist() {
		return $this->maillist;
	}

	public function setMaillist( $maillist ) {
		$this->maillist = $maillist;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription( $description ) {
		$this->description = $description;
	}

	public function getUserId() {
		return $this->userId;
	}

	public function setUserId( $userId ) {
		$this->userId = $userId;
	}

	public function getSalutation() {
		return $this->salutation;
	}

	public function setSalutation( $salutation ) {
		$this->salutation = $salutation;
	}

	public function getFirstName() {
		return $this->firstName;
	}

	public function setFirstName( $firstName ) {
		$this->firstName = $firstName;
	}

	public function getLastName() {
		return $this->lastName;
	}

	public function setLastName( $lastName ) {
		$this->lastName = $lastName;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail( $email ) {
		$this->email = $email;
	}

	public function getPhone() {
		return $this->phone;
	}

	public function setPhone( $phone ) {
		$this->phone = $phone;
	}

	public function getNotes() {
		return $this->notes;
	}

	public function setNotes( $notes ) {
		$this->notes = $notes;
	}

	public function getAdminNotes() {
		return $this->adminNotes;
	}

	public function setAdminNotes( $adminNotes ) {
		$this->adminNotes = $adminNotes;
	}

	public function addExtraInfo( $key, $value ) {

		$this->extraInfo[ $key ] = $value;
	}

	public function getExtraInfo() {
		return $this->extraInfo;
	}

	public function getProfileImage() {
		return $this->profileImage;
	}

	public function setProfileImage( $profileImage ) {
		$this->profileImage = $profileImage;
	}

	public function getProfileImageUrl() {

		if ( $this->profileImage ) {
			return wp_get_attachment_url( $this->profileImage );
		}

		return "";
	}

	public function getProfileId() {
		return $this->profileId;
	}

	public function setProfileId( $profileId ) {
		$this->profileId = $profileId;
	}

	public function getWebsite() {
		return $this->website;
	}

	public function setWebsite( $website ) {
		$this->website = $website;
	}

	public function getTwitter() {
		return $this->twitter;
	}

	public function setTwitter( $twitter ) {
		$this->twitter = $twitter;
	}

	public function getFacebook() {
		return $this->facebook;
	}

	public function setFacebook( $facebook ) {
		$this->facebook = $facebook;
	}

	public function getGooglePlus() {
		return $this->googlePlus;
	}

	public function setGooglePlus( $googlePlus ) {
		$this->googlePlus = $googlePlus;
	}

	public function getLinkedIn() {
		return $this->linkedIn;
	}

	public function setLinkedIn( $linkedIn ) {
		$this->linkedIn = $linkedIn;
	}

	public function getCompany() {
		return $this->company;
	}

	public function setCompany( $company ) {
		$this->company = $company;
	}

	public function getSex() {
		return $this->sex;
	}

	public function setSex( $sex ) {
		$this->sex = $sex;
	}

	public function isWholesale() {
		return $this->wholesale;
	}

	public function setWholesale( $wholesale ) {
		if ( $wholesale === 'false' || $wholesale === false ) {
			$this->wholesale = FALSE;
		} else {
			$this->wholesale = $wholesale;
		}
	}

	/**
	 * Get roles
	 * @collectionType: \Maven\Core\Domain\Role
	 * @return \Maven\Core\Domain\Role[] 
	 */
	public function getRoles() {
		return $this->roles;
	}

	public function hasRoles() {
		return $this->roles && count( $this->roles ) > 0;
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Role[]
	 */
	public function setRoles( $roles ) {
		$this->roles = $roles;
	}

	/**
	 * Get addresses
	 * @collectionType: \Maven\Core\Domain\Address
	 * @return \Maven\Core\Domain\Address[] 
	 */
	public function getAddresses() {
		return $this->addresses;
	}

	public function hasAddresses() {
		return $this->addresses && count( $this->addresses ) > 0;
	}

	public function hasAddress( $type ) {
		return $this->hasAddresses() && isset( $this->addresses[ $type ] );
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Address[]
	 */
	public function setAddresses( $addresses ) {
		$this->addresses = $addresses;
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Address $address
	 */
	public function setPrimaryAddress( \Maven\Core\Domain\Address $primaryAddress ) {

		foreach ( $this->addresses as $address ) {
			if ( $address->isPrimary() ) {
				$address = $primaryAddress;
				return;
			}
		}

		$primaryAddress->setType( AddressType::Home );

		$this->addresses[ AddressType::Home ] = $primaryAddress;
	}

	public function cleanAddresses() {
		$this->addresses[] = array();
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Address $address
	 */
	public function setHomeAddress( \Maven\Core\Domain\Address $homeAddress ) {

		foreach ( $this->addresses as $address ) {
			if ( $address->getType() == AddressType::Home ) {
				$address = $homeAddress;
				return;
			}
		}

		$homeAddress->setType( AddressType::Home );

		$this->addresses[ AddressType::Home ] = $homeAddress;
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Address $address
	 */
	public function setBillingAddress( \Maven\Core\Domain\Address $billingAddress ) {

		foreach ( $this->addresses as $address ) {
			if ( $address->getType() == AddressType::Billing ) {
				$address = $billingAddress;
				return;
			}
		}

		$billingAddress->setType( AddressType::Billing );

		$this->addresses[ AddressType::Billing ] = $billingAddress;
	}

	public function setShippingAddress( \Maven\Core\Domain\Address $shippingAddress ) {

		foreach ( $this->addresses as $address ) {
			if ( $address->getType() == AddressType::Shipping ) {
				$address = $shippingAddress;
				return;
			}
		}

		$shippingAddress->setType( AddressType::Shipping );

		$this->addresses[ AddressType::Shipping ] = $shippingAddress;
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\Address
	 */
	public function getPrimaryAddress() {

		foreach ( $this->addresses as $address ) {
			if ( $address->isPrimary() ) {
				return $address;
			}
		}

		//there is no primary address, create a new one

		$address = new Address();
		$address->setPrimary( true );
		$address->setType( AddressType::Home );

		$this->addresses[ AddressType::Home ] = $address;

		return $address;
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\Address
	 */
	public function getBillingAddress() {
		return $this->getAddressByType( AddressType::Billing );
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\Address
	 */
	public function getAddressByType( $type ) {
		foreach ( $this->addresses as $address ) {
			if ( $address->getType() == $type ) {
				return $address;
			}
		}

		//There was no type address, duplicate primary address.
		$primary = $this->getPrimaryAddress()->copy();

		$primary->setType( $type );
		$primary->setPrimary( FALSE );

		$this->addresses[ $type ] = $primary;

		return $primary;
	}

	public function removeAddress( $type ) {
		$i = 0;
		foreach ( $this->addresses as $address ) {
			if ( $address->getType() == $type ) {
				$this->addresses = array_splice( $this->addresses, $i, 1 );
				return true;
			}
			$i ++;
		}

		return false;
	}

	public function removeAllAddressesExcept( $type ) {
		foreach ( $this->addresses as $address ) {
			if ( $address->getType() == $type ) {
				$this->addresses = array( $address );
				return;
			}
		}
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\Address
	 */
	public function getShippingAddress() {
		return $this->getAddressByType( AddressType::Shipping );
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\Address
	 */
	public function getHomeAddress() {
		return $this->getAddressByType( AddressType::Home );
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\Address
	 */
	public function getWorkAddress() {
		return $this->getAddressByType( AddressType::Work );
	}

	public function getStatusImageUrl() {
		return $this->statusImageUrl;
	}

	public function getFullName() {
		return $this->firstName . " " . $this->lastName;
	}

	public function setStatusImageUrl( $statusImageUrl ) {
		$this->statusImageUrl = $statusImageUrl;
	}

	public function getUserName() {
		return $this->userName;
	}

	public function setUserName( $userName ) {
		$this->userName = $userName;
	}

	public function isEmpty() {
		return Utils::isEmpty( $this->getProfileId() );
	}

	public function isCompleted() {
		$result = ! Utils::isEmpty( $this->firstName ) && ! Utils::isEmpty( $this->lastName );
		$result = $result && ! Utils::isEmpty( $this->email );
		$result = $result && ! Utils::isEmpty( $this->getProfileId() );

		return $result;
	}

	/**
	 * Copy the current profile into a new objet
	 * @return \Maven\Core\Domain\Profile
	 */
	public function copy() {
		$profile = new Profile();

		$profile->setSalutation( $this->getSalutation() );
		$profile->setFirstName( $this->getFirstName() );
		$profile->setLastName( $this->getLastName() );
		$profile->setCompany( $this->getCompany() );
		$profile->setEmail( $this->getEmail() );
		$profile->setPhone( $this->getPhone() );
		$profile->setLinkedIn( $this->getLinkedIn() );
		$profile->setFacebook( $this->getFacebook() );
		$profile->setDescription( $this->getDescription() );
		$profile->setUserId( $this->getUserId() );
		$profile->setSex( $this->getSex() );
		$profile->setProfileImage( $this->getProfileImage() );
		$profile->setWebsite( $this->getWebsite() );
		$profile->setNotes( $this->getNotes() );


		return $profile;
	}

	public function getLastUpdate() {
		return $this->lastUpdate;
	}

	public function setLastUpdate( $lastUpdate ) {
		$this->lastUpdate = $lastUpdate;
	}

	public function getCreatedOn() {
		return $this->createdOn;
	}

	public function setCreatedOn( $createdOn ) {
		$this->createdOn = $createdOn;
	}

	/**
	 * Get whishlist
	 * @collectionType: \Maven\Core\Domain\WhishlistItem
	 * @return \Maven\Core\Domain\WhishlistItem[] 
	 */
	public function getWishlist() {
		return $this->whishlist;
	}

	public function hasWhishlist() {
		return $this->whishlist && count( $this->whishlist ) > 0;
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\WhishlistItem[]
	 */
	public function setWhishlist( $whishlist ) {
		$this->whishlist = $whishlist;
	}

	/**
	 * Add wihslist item to the profile
	 * @param \Maven\Core\Domain\WishlistItem $item
	 */
	public function addWishlistItem( \Maven\Core\Domain\WishlistItem $item ) {

		if ( ! $item->getPluginKey() ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Plugin Key is required' );
		}

		//TODO: Check if the item exists, we have to remove it and add the new one.
		if ( $this->itemExists( $item->getIdentifier() ) ) {
			$this->removeItem( $item->getIdentifier() );
		}

		$this->whishlist[ $item->getIdentifier() ] = $item;
	}
	
	/**
	 * Remove wishlits item from profile
	 * @param int $identifier
	 * @return boolean
	 */
	public function removeWhishlistItem( $identifier ) {

		if ( $this->wishlistItemExists( $identifier ) ) {

			unset( $this->items[ $identifier ] );

			return true;
		}

		return false;
	}
	
	public function wishlistItemExists( $identifier ) {

		return $this->whishlist && count( $this->wishlist ) > 0 && isset( $this->wishlist[ $identifier ] );
	}

}
