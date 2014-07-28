<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class AddressManager {

	private $mapper;

	public function __construct() {

		$this->mapper = new Mappers\AddressMapper();
	}

	/**
	 * 
	 * @param int $id
	 * @return \Maven\Core\Domain\Address
	 */
	public function get( $id ) {

		return $this->mapper->get( $id );
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Address $address
	 * @return \Maven\Core\Domain\Address
	 */
	public function save( \Maven\Core\Domain\Address $address ) {

		return $this->mapper->save( $address );
	}

	/**
	 * 
	 * @param int $profileId
	 * @return \Maven\Core\Domain\Address[]
	 */
	public function getAddresses( $profileId ) {
		
		\Maven\Loggers\Logger::log()->message('\Maven\Core\AddressManager: getAddresses: Profile Id: '.$profileId);
		
		return $this->mapper->getAddresses( $profileId );
	}

	public function deleteByProfile( $profileId ) {
		return $this->mapper->deleteByProfile( $profileId );
	}

	public function delete( $adddressId ) {
		return $this->mapper->delete( $adddressId );
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
		$tempArray = array();
		foreach ( $addresses as $address ) {
			if ( is_array( $address ) ) {
				$temp = new Domain\Address();
				\Maven\Core\FillerHelper::fillObject( $temp, $address );
			} else {
				$temp = $address;
			}
			$tempArray[] = $temp;
		}
		return $this->mapper->updateAddresses( $tempArray, $profile );
	}

}
