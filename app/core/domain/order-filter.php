<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class OrderFilter {

	private $pluginKey;
	private $number;
	private $orderDateFrom;
	private $orderDateTo;
	private $userID;
	private $statusID;

	private function protectField( $field ) {

		if ( ! ( $field instanceof \Maven\Core\MavenDateTime ) )
			return esc_sql( sanitize_text_field( $field ) );

		return $field;
	}

	public function __construct() {
		;
	}

	public function getPluginKey() {
		return $this->pluginKey;
	}

	public function setPluginKey( $pluginKey ) {
		$this->pluginKey = $pluginKey;
	}

	public function getNumber() {
		return $this->number;
	}

	public function setNumber( $number ) {
		$this->number = $number;
	}

	public function getOrderDateFrom() {
		return $this->orderDateFrom;
	}

	public function setOrderDateFrom( $orderDateFrom ) {
		$this->orderDateFrom = $orderDateFrom;
	}

	public function getOrderDateTo() {
		return $this->orderDateTo;
	}

	public function setOrderDateTo( $orderDateTo ) {
		$this->orderDateTo = $orderDateTo;
	}

	public function getStatusID() {
		return $this->statusID;
	}

	public function setStatusID( $statusID ) {
		$this->statusID = $statusID;
	}
	
	public function getUserID() {
		return $this->userID;
	}

	public function setUserID( $userID ) {
		$this->userID = $userID;
	}



}

