<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

abstract class DomainObject {

	private $id;
	
	/**
	 * Just save what you want in here. It is just a temporary data
	 * @var anything 
	 */
	private $tempData;
	

	public function __construct( $id = false ) {

		$this->id = $id;
	}

	private $sanitizationRules = array( );

	protected function addSanitationRule( $property, $rules ) {

		$this->sanitizationRules[ $property ] = $rules;
	}

	protected function setSanitizationRules( $rules ) {

		if ( is_array( $rules ) && count( $rules ) > 0 )
			$this->sanitizationRules = array_merge( $this->sanitizationRules, $rules );
	}

	public function sanitize() {

		foreach ( $this->sanitizationRules as $property => $rules ) {

			$getMethodName = "get{$property}";
			$setMethodName = "set{$property}";
			$isMethodName = "is{$property}";

			if ( is_callable( array( $this, $getMethodName ) ) )
				$value = $this->$getMethodName();

			else if ( is_callable( array( $this, $isMethodName ) ) )
				$value = $this->$isMethodName();
			else
				continue;

			if ( is_array( $rules ) ) {
				foreach ( $rules as $rule ) {
					$value = $this->applySanitization( $value, $rule );
				}
			}
			else
				$value = $this->applySanitization( $value, $rules );

			//$prop->setValue($this, $value);
			$this->{$setMethodName}( $value );
		}
	}

	private function applySanitization( $value, $rule ) {

		switch ( $rule ) {

			case SanitizationRule::Email:
				$value = sanitize_email( $value );
				break;

			case SanitizationRule::URL:
				$value = esc_url_raw( $value );
				break;

			case SanitizationRule::Text:
				$value = sanitize_text_field( $value );
				break;

			case SanitizationRule::Integer:
				if ( is_numeric( $value ) )
					$value = intval( $value );
				else
					$value = '';

				break;

			case SanitizationRule::Float:
				if ( is_numeric( $value ) )
					$value = floatval( $value );
				else
					$value = '';
				break;

			case SanitizationRule::Price:
				if ( is_numeric( $value ) )
					$value = floatval( $value );
				else
					$value = '';
				break;

			case SanitizationRule::Key:
				$value = sanitize_key( $value );
				break;

			case SanitizationRule::TextWithHtml:
			case SanitizationRule::SerializedObject:
				//TODO: Don't we need to validate anything here???
				$value = $value;
				break;

			case SanitizationRule::Date:
				$value = sanitize_text_field( $value );
				break;

			case SanitizationRule::Time:
				$value = sanitize_text_field( $value );
				break;

			case SanitizationRule::DateTime:
				$value = sanitize_text_field( $value );
				break;
			case SanitizationRule::TimeStamp:
				$value = sanitize_text_field( $value );
				break;
			case SanitizationRule::Slug:
				$value = sanitize_title( $value );
				break;

			case SanitizationRule::Boolean:

				if ( ! ( $value === 1 || $value === 0 || $value === false || $value === true || $value === 'false' || $value === 'true' ) )
					$value = null;

				break;
		}

		return $value;
	}

	public function getId() {
		return $this->id;
	}

	public function setId( $value ) {
		$this->id = $value;
	}

	public function isEmpty(){
		return Utils::isEmpty( $this->id );
	}
	
	private function toArrayMagic( $obj ){
		
		$return = array();

		$reflect = new \ReflectionClass( $obj );
		$props = $reflect->getProperties( \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_PROTECTED );

		foreach ( $props as $prop ) {

			$methodNameGet = "get" . $prop->getName();

			//This is for boolean properties
			$methodNameIs = "is" . $prop->getName();

			//Check if it is public, we won't add private/protected properties
			if ( is_callable( array( $obj, $methodNameGet ) ) ) {

				$value = $obj->{$methodNameGet}();

				if ( is_object( $value ) ){
					$return[ $prop->getName() ] = $this->toArrayMagic( $value );
				}
				elseif ( is_array( $value ) ) {
					
					$return[ $prop->getName() ] = array( );
					foreach ( $value as $newValue ) {
						
						if ( is_object( $newValue ) ) {
							
							$return[ $prop->getName() ][ ] = $this->toArrayMagic( $newValue );
							
						} elseif ( is_array( $value ) ) {
							
							$return[ $prop->getName() ] = array( );
							
							foreach ( $value as $newValue ) {
								
								if ( is_object( $newValue ) ) {
									
									$return[ $prop->getName() ][ ] = $this->toArrayMagic( $newValue );
									
								} elseif ( is_array( $value ) ) {
									
									$return[ $prop->getName() ] = array( );
									
									foreach ( $value as $newValue ) {
										
										if ( is_object( $newValue ) ) {
											
											$return[ $prop->getName() ][ ] = $this->toArrayMagic( $newValue );
										}
										else
											$return[ $prop->getName() ] = $value;
									}
								}
								else
									$return[ $prop->getName() ] = $value;
							}
						}
						else
							$return[ $prop->getName() ] = $value;
					}
				}
				else
					$return[ $prop->getName() ] = $value;
			}
			else if ( is_callable( array( $obj, $methodNameIs ) ) ) {
				//Cast the value to boolean
				$return[ $prop->getName() ] = ( bool ) $obj->{$methodNameIs}();
			}
		}
		
		//Not sure why it doesn't read the id property, so that's way we are adding it manually.
		if ( is_callable( array( $obj, 'getId' )  ) )
			$return[ 'id' ] = $obj->getId();

		return $return;
		
	}
	public function toArray() {
		
			
		$return = $this->toArrayMagic( $this );
		
		return $return;
	}
			

	public function load( $data ) {
		if ( ! is_array( $data ) )
			return false;

		FillerHelper::fillObject( $this, $data );
	}

	
	
	/**
	 * Just save what you want in here. It is just a temporary data
	 * @return type
	 */
	public function getTempData() {
		return $this->tempData;
	}

	public function setTempData( $tempData ) {
		$this->tempData = $tempData;
	}
	
	

}

class SanitizationRule {

	const Email = 'email';
	const URL = 'url';
	const Text = 'text';
	const Integer = 'integer';
	const Float = 'float';
	const Boolean = 'boolean';
	const Key = 'key';
	const TextWithHtml = 'textWithHtml';
	const Date = 'date';
	const Time = 'time';
	const DateTime = 'dateTime';
	const Slug = 'slug';
	const Price = 'price';
	const TimeStamp = 'timestamp';
	const SerializedObject = 'serializedObject';

}

