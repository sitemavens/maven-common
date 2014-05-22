<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class CreditCard extends \Maven\Core\DomainObject {

	
	private $number;
	private $securityCode;
	private $month;
	private $year;
	private $holderName; 
	
	private $encryptor = null;
	
	public function __construct( $id = false ) {
		
		parent::__construct( $id );
		
		$rules = array(
			
			'number'			=> \Maven\Core\SanitizationRule::Text,
			'securityCode'		=> \Maven\Core\SanitizationRule::Integer,
			'month'				=> \Maven\Core\SanitizationRule::Integer,
			'year'				=> \Maven\Core\SanitizationRule::Integer,
			'holderName'		=> \Maven\Core\SanitizationRule::Text
			
		);
	
	
		$this->setSanitizationRules( $rules );
		
		
		
	}
	
	private function getEncryptor(){
		
		if ( ! $this->encryptor )
			$this->encryptor = \Maven\Encryption\EncryptionFactory::getEncryptor();
		
		return $this->encryptor;
	}

	/**
	 *
	 * @var string
	 */
	private $type;
	
	/**
	 * It will return the number encrypted
	 * @return string
	 */
	public function getNumber() {
		 
		return $this->getEncryptor()->decrypt( $this->number );
	}

	public function setNumber( $number ) {
		//Once we set the number, it must be encrypted. 
		$this->number = $this->getEncryptor()->encrypt( $number );
		
	}

	public function getSecurityCode() {
		return $this->getEncryptor()->decrypt( $this->securityCode );
	}

	public function setSecurityCode( $securityCode ) {
		$this->securityCode = $this->getEncryptor()->encrypt( $securityCode );
	}

	public function getMonth() {
		return $this->month;
	}

	public function setMonth( $month ) {
		$this->month = $month;
	}

	public function getYear() {
		return $this->year;
	}

	public function setYear( $year ) {
		$this->year = $year;
	}

	public function getHolderName() {
		return $this->holderName;
	}

	public function setHolderName( $holderName ) {
		$this->holderName = $holderName;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	public function setType( $type ) {
		$this->type = $type;
	}

	
	/**
	 * Check if the number and security code exists.
	 * @return boolean
	 */
	public function isValid(){
		
		return $this->number && $this->securityCode;
		
	}
	
	public function getMaskNumber(){
		return "XXXX-XXXX-XXXX-".substr($this->getNumber(), -4);
	}
	
	public function getLast4Digits(){
		return substr($this->getNumber(), -4);
	}
	
	
	public function getExpirationDate( ){
		return $this->getMonth()."/".$this->getYear();
	}

}