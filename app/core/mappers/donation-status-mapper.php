<?php

namespace MavenDonations\Core\Mappers;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class DonationStatusMapper extends \Maven\Core\Db\WordpressMapper {

	private $status = null;
	
	private $donationStatusTable = "mvnd_donations_status";

	public function __construct() {
		
		parent::__construct( $this->donationStatusTable );

		$items = array( 'referred'=>'Referred', 'received' =>'Received', 'cancelled' => 'Cancelled', 'completed' => 'Completed', 'declined' => 'Declined', 'error' => 'Error', 'on-hold' => 'On hold', 'pending' => 'Pending', 'processing' => 'Processing', 'refunded' => 'Refunded', 'voided' => 'Voided' );

		$registry = \MavenDonations\Settings\DonationsRegistry::instance();
		
		foreach ( $items as $key => $value ) {

			$instance = new \MavenDonations\Core\Domain\DonationStatus();
			$instance->setid( $key );
			$instance->setName( $value );
			$instance->setImageUrl( $registry->getDonationStatusImagesUrl().$key.".png");

			$this->status[ $key ] = $instance;
		}
	}

	public function getAll() {

		return $this->status;
	}

	public function getDonationHistory( $donationId ){
		
		$statusRows = $this->getResultsBy( 'donation_id', $donationId, 'timestamp','desc');
		
		$status = array();
		
		foreach($statusRows as $statusRow ){
			
			$instance = $this->get( $statusRow->status_id );
			$this->fillObject($instance, $statusRow);
			$status[] = $instance;
			
		}
		
		return $status;
		
	}
	
	
	/**
	 * 
	 * @param type $id
	 * @return MavenDonations\Core\Domain\DonationStatus | Boolean
	 */
	public function get( $id ) {

		if ( isset( $this->status[ $id ] ) )
			return clone $this->status[ $id ];

		return false;
	}
	
	
	/**
	 * 
	 * @param \MavenDonations\Core\Domain\DonationStatus $status
	 * @param \MavenDonations\Core\Domain\Donation $donation
	 * @return type
	 */
	public function addStatus( \MavenDonations\Core\Domain\DonationStatus $status, \MavenDonations\Core\Domain\Donation $donation ){
		
		$data = array(
			'donation_id' => $donation->getId(),
			'status_id' => $status->getId()
		);
		
		$format = array(
			'%d',
			'%s'
		);
		
		return $this->insert($data, $format, $this->donationStatusTable);
	}
	
	public function removeDonationHistory( $donationId ){
		
		$query = "DELETE FROM {$this->donationStatusTable} WHERE donation_id = %d";
		$query = $this->prepare($query, $donationId );
		
		return $this->executeQuery($query);
	}

}