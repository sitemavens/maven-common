<?php

namespace Maven\Core\Mappers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class ProfileMapper extends \Maven\Core\Db\WordpressMapper {

	protected static $profileTable = 'mvn_profile';
	private $wishlistItemTable = "mvn_whishlist_items";
	protected $profileTableName = 'mvn_profile';
	protected $profileSelectFields = "  `mvn_profile`.`id`,
							`mvn_profile`.`description`,
							`mvn_profile`.`user_id`,
							`mvn_profile`.`notes`,
							`mvn_profile`.`admin_notes`,
							`mvn_profile`.`salutation`,
							`mvn_profile`.`first_name`,
							`mvn_profile`.`last_name`,
							`mvn_profile`.`email`,
							`mvn_profile`.`phone`,
							`mvn_profile`.`profile_image`,
							`mvn_profile`.`timestamp`, 
							`mvn_profile`.`website`,
							`mvn_profile`.`company`,
							`mvn_profile`.`twitter`,
							`mvn_profile`.`facebook`,
							`mvn_profile`.`google_plus`,
							`mvn_profile`.`sex`,
							`mvn_profile`.`linked_in`";

	public function __construct( $tableName ) {

		parent::__construct( $tableName );
	}

	public static function getTableName() {
		return self::$profileTable;
	}

	protected function getProfileJoin( $table, $field ) {

		$join = " INNER JOIN {$this->profileTableName} ON {$this->profileTableName}.id = {$table}.{$field}";

		return $join;
	}

	/**
	 * Return a profile object
	 * @param int $id
	 * @return \Maven\Core\Domain\Profile
	 */
	protected function loadProfile( \Maven\Core\Domain\Profile $profile ) {

		if ( ! $profile->getProfileId() )
			throw new \Maven\Exceptions\MissingParameterException( "Profile Id is required" );

		// We need to save the domain entity id, since it will be loosed when we use fillObject. 
		$entityId = $profile->getId();

		$row = $this->getRowById( $profile->getProfileId(), "%d", $this->profileTableName );

		if ( ! $row )
			throw new \Maven\Exceptions\NotFoundException( "Item not found: {$profile->getProfileId()}" );

		$this->fillObject( $profile, $row );

		// We restore the entity id
		$profile->setId( $entityId );

		$addressMapper = new AddressMapper();
		$profile->setAddresses( $addressMapper->getAddresses( $row->id ) );
	}

	/** Create or update the donation to the database
	 * 
	 * @param \MavenDonations\Core\Domain\Donation $profile
	 * @return \MavenDonations\Core\Domain\Donation
	 */
	protected function saveProfile( \Maven\Core\Domain\Profile $profile ) {

		if ( ! $profile->getEmail() ) {
			throw new \Maven\Exceptions\RequiredException( 'Profile email is required' );
		}


		$profile->sanitize();


		$data = array(
		    'description' => $profile->getDescription(),
		    'notes' => $profile->getNotes(),
		    'admin_notes' => $profile->getAdminNotes(),
		    'salutation' => $profile->getSalutation(),
		    'first_name' => $profile->getFirstName(),
		    'last_name' => $profile->getLastName(),
		    'sex' => $profile->getSex(),
		    'email' => $profile->getEmail(),
		    'phone' => $profile->getPhone(),
		    'profile_image' => $profile->getProfileImage(),
		    'company' => $profile->getCompany(),
		    'website' => $profile->getWebsite(),
		    'twitter' => $profile->getTwitter(),
		    'facebook' => $profile->getFacebook(),
		    'google_plus' => $profile->getGooglePlus(),
		    'linked_in' => $profile->getLinkedIn(),
		    'user_id' => $profile->getUserId(),
		    'wholesale' => $profile->isWholesale() ? 1 : 0,
		    'last_update' => \Maven\Core\MavenDateTime::getWPCurrentDateTime(),
		);

		$format = array(
		    '%s', //description
		    '%s', //notes
		    '%s', //admin_notes
		    '%s', //sal
		    '%s', //firs
		    '%s', //last
		    '%s', //sex
		    '%s', //email
		    '%s', //phone
		    '%s', //profile_image
		    '%s', //company
		    '%s', //website
		    '%s', //twitter
		    '%s', //facebook
		    '%s', //google_plus
		    '%s', //linked_in
		    '%d', //user_id
		    '%s' //last_update
		);

		// Before insertint it, we have to check if the profile already exists using 
		// the email
		$row = $this->getRowBy( 'email', $profile->getEmail(), '%s', $this->profileTableName );

		if ( $row && $row->id ) {
			$profile->setProfileId( $row->id );
		}

		if ( ! $profile->getProfileId() ) {

			//if we are inserting, we should send creation date
			$data[ 'created_on' ] = \Maven\Core\MavenDateTime::getWPCurrentDateTime();
			$format[] = '%s';

			$profileId = $this->insert( $data, $format, $this->profileTableName );

			$profile->setProfileId( $profileId );
		} else {
			$this->updateById( $profile->getProfileId(), $data, $format, $this->profileTableName );
		}

		/* if ( $profile->hasAddresses() ) {
		  $addressMapper = new AddressMapper();

		  $addresses = $profile->getAddresses();
		  foreach ( $addresses as $address ) {
		  $address->setProfileId( $profile->getProfileId() );
		  $addressMapper->save( $address );
		  }
		  } */

		$addresses = array();
		if ( $profile->hasAddresses() ) {
			$addresses = $profile->getAddresses();
		}

		$addressMapper = new AddressMapper();
		$addressMapper->addAddresses( $addresses, $profile );

		//die();
		
		//Update items
		$this->addWishlistItems( $profile );

		return $profile;
	}

	/**
	 * Return an Profile object
	 * @param int $id
	 * @return \Maven\Core\Domain\Profile
	 */
	public function get( $id ) {

		$profile = new \Maven\Core\Domain\Profile();

		if ( ! $id ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );
		}

		$row = $this->getRowById( $id );

		if ( ! $row ) {
			throw new \Maven\Exceptions\NotFoundException();
		}

		$this->fillObject( $profile, $row );

		$profile->setId( false );

		$profile->setProfileId( $row->id );

		$addressMapper = new AddressMapper();
		$profile->setAddresses( $addressMapper->getAddresses( $row->id ) );

		if ( $profile->getUserId() ) {
			$registrationManager = new \Maven\Core\RegistrationManager();
			$user = $registrationManager->getById( $profile->getUserId() );

			$profile->setUserName( $user->user_login );
		}
		
		$items = $this->getWishlistItems( $profile->getId() );

		$profile->setWhishlist( $items );


		return $profile;
	}

	/** Create or update the donation to the database
	 * 
	 * @param \Maven\Core\Domain\Profile $profile
	 * @return \Maven\Core\Domain\Profile
	 */
	public function save( \Maven\Core\Domain\Profile $profile ) {

		return $this->saveProfile( $profile );
	}

	/**
	 * Check if a profile exists
	 * @param string $email
	 * @return boolean
	 */
	public function existsProfile( $email ) {

		$row = $this->getRowBy( 'email', $email, '%s', $this->profileTableName );

		if ( $row == null ) {
			return false;
		}

		return $row->id;
	}

	/**
	 * 
	 * @param string $email
	 * @return boolean
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function isWPUser( $email ) {

		if ( ! $email ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Email is required' );
		}

		$profile = $this->getProfileByEmail( $email, true );

		if ( $profile && $profile->getUserId() ) {
			return true;
		}

		return false;
	}

	public function deleteProfile( $id ) {

		if ( ! $id ) {
			throw new \Maven\Exceptions\MissingParameterException( "Id is required" );
		}

		$this->deleteRow( $id, "%d", $this->profileTableName );

		$addressMapper = new AddressMapper();
		$addressMapper->deleteByProfile( $id );
		
		//delete the wishlist items
		$query = "DELETE FROM {$this->wishlistItemTable} where profile_id=%d";
		$query = $this->prepare( $query, $id );
		$this->executeQuery( $query );

		return true;
	}

	public function populateProfileByEmail( \Maven\Core\Domain\Profile $profile ) {

		$row = $this->getProfileByEmail( $profile->getEmail(), false );

		// We need to save the domain entity id, since it will be loosed when we use fillObject. 
		$entityId = $profile->getId();

		$this->fillObject( $profile, $row );

		$profile->setProfileId( $row->id );

		// We restore the entity id
		$profile->setId( $entityId );

		$addressMapper = new AddressMapper();
		$profile->setAddresses( $addressMapper->getAddresses( $row->id ) );

		return $profile;
	}

	/**
	 * Get profile by email
	 * @param string $email
	 * @param boolean $object
	 * @return \Maven\Core\Domain\Profile
	 */
	public function getProfileByEmail( $email, $object = false ) {

		$instance = new \Maven\Core\Domain\Profile();

		$row = $this->getRowBy( 'email', $email, '%s', $this->profileTableName );

		if ( ! $row ) {
			return $instance;
		}

		if ( ! $object ) {
			return $row;
		}



		$this->fillObject( $instance, $row );

		$instance->setProfileId( $row->id );

		// We restore the entity id
		$instance->setId( false );

		$addressMapper = new AddressMapper();
		$instance->setAddresses( $addressMapper->getAddresses( $row->id ) );

		return $instance;
	}

	public function getAll( $orderBy = "id" ) {
		$instances = array();
		$results = $this->getResults( $orderBy );
		$addressMapper = new AddressMapper();

		foreach ( $results as $row ) {

			$instance = new \Maven\Core\Domain\Profile();

			$this->fillObject( $instance, $row );

			$instance->setAddresses( $addressMapper->getAddresses( $row->id ) );
			//$this->loadProfile( $instance );

			$registry = \Maven\Settings\MavenRegistry::instance();
			if ( $instance->getUserId() ) {
				$instance->setStatusImageUrl( $registry->getProfileStatusImageUrl() . 'enabled.png' );
			} else {
				$instance->setStatusImageUrl( $registry->getProfileStatusImageUrl() . 'disabled.png' );
			}

			$instances[] = $instance;
		}

		return $instances;
	}

	public function getCount( \Maven\Core\Domain\ProfileFilter $filter ) {
		$where = '';
		$values = array();
		//first value is plugin key
		//$values[] = array();

		$email = $filter->getEmail();
		if ( $email ) {
			$values[] = "%{$email}%";
			$where.=" AND email LIKE %s";
		}

		$firstName = $filter->getFirstName();
		if ( $firstName ) {
			$values[] = "%{$firstName}%";
			$where.=" AND first_name LIKE %s";
		}

		$lastName = $filter->getLastName();
		if ( $lastName ) {
			$values[] = "%{$lastName}%";
			$where.=" AND last_name LIKE %s";
		}

		$query = "select count(*)
					from {$this->tableName} 
					where 1=1
					{$where}";

		//$query = $this->prepare( $query, $filter->getPluginKey(), $orderBy, $orderType, $start, $limit );
		$query = $this->prepare( $query, $values );

		return $this->getVar( $query );
	}

	public function getPage( \Maven\Core\Domain\ProfileFilter $filter, $orderBy = 'email', $orderType = 'desc', $start = 0, $limit = 1000 ) {
		$where = '';
		$values = array();
		//first value is plugin key
		//$values[] = $filter->getPluginKey();

		$email = $filter->getEmail();
		if ( $email ) {
			$values[] = "%{$email}%";
			$where.=" AND email LIKE %s";
		}

		$firstName = $filter->getFirstName();
		if ( $firstName ) {
			$values[] = "%{$firstName}%";
			$where.=" AND first_name LIKE %s";
		}

		$lastName = $filter->getLastName();
		if ( $lastName ) {
			$values[] = "%{$lastName}%";
			$where.=" AND last_name LIKE %s";
		}

		if ( ! $orderBy )
			$orderBy = 'id';


		$query = "select	{$this->tableName}.*
					from {$this->tableName} 
					where 1=1 
					{$where} order by {$orderBy} {$orderType}
					LIMIT %d , %d;";

		//other values
		//$values[ ] = $orderBy;
		//$values[ ] = $orderType;
		$values[] = $start;
		$values[] = $limit;
		//$query = $this->prepare( $query, $filter->getPluginKey(), $orderBy, $orderType, $start, $limit );
		$query = $this->prepare( $query, $values );

		$results = $this->getQuery( $query );

		$instances = array();

		$addressMapper = new AddressMapper();
		$rolesMapper = new RoleMapper();

		foreach ( $results as $row ) {

			$instance = new \Maven\Core\Domain\Profile();

			$this->fillObject( $instance, $row );

			$instance->setAddresses( $addressMapper->getAddresses( $row->id ) );

			if ( $instance->getUserId() )
				$instance->setRoles( $rolesMapper->getUserRoles( $instance->getUserId() ) );
			//$this->loadProfile( $instance );

			$registry = \Maven\Settings\MavenRegistry::instance();
			if ( $instance->getUserId() ) {
				$instance->setStatusImageUrl( $registry->getProfileStatusImageUrl() . 'enabled.png' );
			} else {
				$instance->setStatusImageUrl( $registry->getProfileStatusImageUrl() . 'disabled.png' );
			}

			$instances[] = $instance;
		}

		return $instances;
	}

	/**
	 * Update the autologin key
	 * @param int $profileId
	 * @param string $key
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function updateAutoLoginKey( $profileId, $key ) {

		if ( ! $profileId || ! ( int ) $profileId ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Profile id is required' );
		}

		$data = array( 'auto_login_key' => sanitize_key( $key ) );

		$this->updateById( $profileId, $data );
	}

	/**
	 * Clean the autologin key
	 * @param int $profileId
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function resetAutoLoginKey( $profileId ) {

		\Maven\Loggers\Logger::log()->message("Maven/Core/Mappers/resetAutoLoginKey: ProfileId: {$profileId}");
		
		if ( ! $profileId || ! ( int ) $profileId ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Profile id is required' );
		}

		$data = array( 'auto_login_key' => '' );

		$this->updateById( $profileId, $data );
	}
	
	private function addWishlistItems ( \Maven\Core\Domain\Profile $profile ) {

		$wishlistItems = $profile->getWishlist();

		$existingId = array();

		if ( $wishlistItems ) {
			foreach ( $wishlistItems as $item ) {
				//TODO: Move this validation to manager (on add item)
				if ( !$item->getPluginKey() ) {
					throw new \Maven\Exceptions\RequiredException( "Plugin key is required: " . $item->getName() );
				}

				$data = array(
					'name' => $item->getName(),
					'price' => $item->getPrice(),
					'profile_id' => $profile->getId(),
					'thing_id' => $item->getThingId(),
					'sku' => $item->getSku(),
					'plugin_key' => $item->getPluginKey(),
					'thing_variation_id' => $item->getThingVariationId(),
					'attributes' => serialize( $item->getAttributes() )
				);

				$format = array(
					'%s', //name
					'%f', //price
					'%d', //profile_id
					'%d', //id
					'%s', //sku
					'%s', //plugin_key
					'%d', //thing_variation_id
					'%s' //attributes
				);

				if ( !$item->getId() ) {
					$insertedItemId = $this->insert( $data, $format, $this->wishlistItemTable);
					$item->setId( $insertedItemId );
				} else {
					$this->updateById( $item->getId(), $data, $format, $this->wishlistItemTable );
				}

				$existingId[] = $item->getId();
			}
		}

		if ( count( $existingId ) == 0 ) {
			$query = $this->prepare( "DELETE FROM {$this->wishlistItemTable} WHERE order_id = %d", $profile->getId() );
			return;
		}

		$items = implode( ',', $existingId );

		//Delete the removed items.
		$query = $this->prepare( "DELETE FROM {$this->wishlistItemTable} WHERE id NOT IN ({$items}) AND profile_id = %d", $profile->getId() );

		$this->executeQuery( $query );
	}
	
	public function getWishlistItems ( $profileId ) {

		$items = array();

		//Get items
		$itemsRows = $this->getResultsBy( 'profile_id', $profileId, 'id', 'asc', '%s', $this->wishlistItemTable );

		if ( $itemsRows ) {

			foreach ( $itemsRows as $itemRow ) {
				//TODO: Check if passing the plugin key on creation is correct
				$item = new \Maven\Core\Domain\WishlistItem( $itemRow->plugin_key );
				$this->fillObject( $item, $itemRow );

				$items[] = $item;
			}
		}

		return $items;
	}
}
