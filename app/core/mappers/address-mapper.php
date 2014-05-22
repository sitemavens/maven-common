<?php

namespace Maven\Core\Mappers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class AddressMapper extends \Maven\Core\Db\WordpressMapper {

	public function __construct() {

		parent::__construct( "mvn_address" );
	}

	/**
	 * Return an Promotion object
	 * @param int $id
	 * @return \Maven\Core\Domain\Promotion
	 */
	public function get( $id ) {

		$address = new \Maven\Core\Domain\Address();

		if ( ! $id )
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );

		$row = $this->getRowById( $id );

		if ( ! $row )
			throw new \Maven\Exceptions\NotFoundException();


		$this->fillObject( $address, $row );

		return $address;
	}

	/**
	 * Return the profile's address
	 * @param int $id
	 * @return \Maven\Core\Domain\Address[]
	 */
	public function getAddresses( $profileId ) {

		$addresses = array();

		if ( ! $profileId )
			throw new \Maven\Exceptions\MissingParameterException( 'Profile ID: is required' );

		$rows = $this->getResultsBy( 'profile_id', $profileId );


		foreach ( $rows as $row ) {
			$address = new \Maven\Core\Domain\Address();

			$this->fillObject( $address, $row );

			$addresses[$address->getType()] = $address;
		}

		return $addresses;
	}

	/**
	 * 
	 * @param int $profileId
	 * @return void
	 */
	public function deleteByProfile( $profileId ) {
		return parent::deleteByColumn( 'profile_id', $profileId );
	}

	public function saveMultiplesAddresses( $addresses ) {

		if ( ! is_array( $addresses ) )
			return;

		foreach ( $addresses as $address ) {
			$this->save( $address );
		}
	}

	/**
	 * Save address
	 * @param \Maven\Core\Domain\Address $address
	 * @return \Maven\Core\Domain\Address
	 * @throws \Maven\Exceptions\RequiredException
	 */
	public function save( \Maven\Core\Domain\Address $address ) {

		$address->sanitize();

		if ( ! $address->getProfileId() ) {
			throw new \Maven\Exceptions\RequiredException( 'Profile id is required' );
		}

		$data = array(
		    'name' => $address->getName(),
		    'description' => $address->getDescription(),
		    'first_line' => $address->getFirstLine(),
		    'second_line' => $address->getSecondLine(),
		    'city' => $address->getCity(),
		    'state' => $address->getState(),
		    'country' => $address->getCountry(),
		    'zipcode' => $address->getZipcode(),
		    'neighborhood' => $address->getNeighborhood(),
		    'phone' => $address->getPhone(),
		    'phone_alternative' => $address->getPhoneAlternative(),
		    'notes' => $address->getNotes(),
		    'primary' => $address->isPrimary() ? 1 : 0,
		    'type' => $address->getType(),
		    'profile_id' => $address->getProfileId()
		);


		$format = array(
		    '%s', // name
		    '%s', // description
		    '%s', // first_line
		    '%s', // second_line
		    '%s', // city
		    '%s', // state
		    '%s', // country
		    '%s', // zipcode
		    '%s', // neighborhood
		    '%s', //phone
		    '%s', //phone alternative
		    '%s', //notes
		    '%d', // primary
		    '%s', // type
		    '%d', // profile_id
		);

		if ( ! $address->getId() ) {

			//First let's check if the address combination => type/profile id exists. 
			$existingAddress = $this->getQueryRow( $this->prepare( "SELECT id FROM {$this->tableName} WHERE profile_id= %d and type=%s", array( $address->getProfileId(), $address->getType() ) ) );

			if ( $existingAddress ) {
				$address->setId( $existingAddress->id );
			}
		}

		if ( ! $address->getId() ) {
			$id = $this->insert( $data, $format );
			$address->setId( $id );
		} else {
			$this->updateById( $address->getId(), $data, $format );
		}
		
		
		return $address;
	}

	public function addAddresses( $addresses, \Maven\Core\Domain\Profile $profile ) {

		if ( is_null( $addresses ) ) {
			$addresses = array();
		}

		if ( !$profile->getProfileId() ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Profile Id is required' );
		}

		/* First: remove missing addresses */
//		$existingAddresses = $this->getAddresses( $profile->getProfileId() );
//
//		foreach ( $existingAddresses as $exAddress ) {
//
//			//search the address in the incoming array
//			$existingId = $exAddress->getId();
//
//			$found = array_filter( $addresses, function($item) use ($existingId) {
//
//				if ( $item->getId() == $existingId ) {
//					return true;
//				}
//
//				return false;
//			} );
//			if ( ! $found ) {
//				//The address is not in the array, delete the record
//				$this->delete( $existingId );
//			}
//		}

		/* Second: Update/Insert new addresses */
		foreach ( $addresses as $address ) {
			$address->setProfileId( $profile->getProfileId() );
			$this->save( $address );
		}

		return true;
	}

	/**
	 * Update the addresses colletion of the profile THIS DELETE REMOVED ADDRESSES
	 * 
	 * @param \Maven\Core\Domain\Address[] $addresses
	 * @param \Maven\Core\Domain\Profile $profile
	 * @return \Maven\Core\Domain\Address[]
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function updateAddresses( $addresses, \Maven\Core\Domain\Profile $profile ) {

		if ( is_null( $addresses ) ) {
			$addresses = array();
		}

		if ( !$profile->getProfileId() ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Profile Id is required' );
		}

		/* First: remove missing addresses */
		$existingAddresses = $this->getAddresses( $profile->getProfileId() );

		foreach ( $existingAddresses as $exAddress ) {

			//search the address in the incoming array
			$existingId = $exAddress->getId();

			$found = array_filter( $addresses, function($item) use ($existingId) {

				if ( $item->getId() == $existingId ) {
					return true;
				}

				return false;
			} );
			if ( ! $found ) {
				//The address is not in the array, delete the record
				$this->delete( $existingId );
			}
		}

		/* Second: Update/Insert new addresses */
		foreach ( $addresses as $address ) {
			$address->setProfileId( $profile->getProfileId() );
			$this->save( $address );
		}

		return $addresses;
	}
	
	/**
	 * 
	 * @param int $id
	 * @return void
	 */
	public function delete( $id ) {
		//delete the address
		return parent::delete( $id );
	}

}
