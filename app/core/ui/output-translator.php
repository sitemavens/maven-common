<?php

namespace Maven\Core\UI;

class OutputTranslator {

	public function __construct () {
		;
	}

	public function sendApiResponse ( $object, $status = '200', $statusText = 'OK' ) {
		header( "HTTP/1.0 {$status} {$statusText}" );

		wp_send_json( $this->convert( $object ) );
	}

	public function sendApiSuccess ( $object, $message = 'OK' ) {
		$this->sendApiResponse( $object, 200, $message );
	}

	public function sendApiError ( $object, $message ) {
		$this->sendApiResponse( $object, 500, $message );
	}

	public function sendData ( $object ) {

		wp_send_json_success( $this->convert( $object ) );
	}

	public function sendError ( $object ) {

		wp_send_json_error( $this->convert( $object ) );
	}

//	public function convert( $object ) {
//
//		$objToSend = null;
//
//		if ( $object instanceof \Maven\Core\DomainObject ) {
//
//			return $object->toArray();
//			
//		} else if ( is_array( $object ) ) {
//
//			foreach ( $object as $domainObj ) {
//
//				if ( $domainObj instanceof \Maven\Core\DomainObject ) {
//					$objToSend[] = $domainObj->toArray();
//				} 
//					
//			}
//		}
//
//		return $objToSend ? $objToSend : $object;
//	}
//	

	private function toArrayMagic ( $obj ) {

		$return = array();

		if ( is_array( $obj ) ) {
			foreach ( $obj as $key => $value ) {
				if ( !is_object( $value ) && !is_array( $value ) ) {
					$return[ $key ] = $value;
				} else {
					$return[ $key ] = $this->toArrayMagic( $value );
				}
			}

			return $return;
		}



		$reflect = new \ReflectionClass( $obj );
		$props = $reflect->getProperties( \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_PROTECTED );

		foreach ( $props as $prop ) {

			$methodNameGet = "get" . $prop->getName();

			//This is for boolean properties
			$methodNameIs = "is" . $prop->getName();

			//Check if it is public, we won't add private/protected properties
			if ( is_callable( array( $obj, $methodNameGet ) ) ) {

				$value = $obj->{$methodNameGet}();

				if ( is_object( $value ) ) {
					$return[ $prop->getName() ] = $this->toArrayMagic( $value );
				} elseif ( is_array( $value ) ) {

					$return[ $prop->getName() ] = array();
					foreach ( $value as $newValue ) {

						if ( is_object( $newValue ) ) {

							$return[ $prop->getName() ][] = $this->toArrayMagic( $newValue );
						} elseif ( is_array( $value ) ) {

							$return[ $prop->getName() ] = array();

							foreach ( $value as $newValue ) {

								if ( is_object( $newValue ) ) {

									$return[ $prop->getName() ][] = $this->toArrayMagic( $newValue );
								} elseif ( is_array( $value ) ) {

									$return[ $prop->getName() ] = array();

									foreach ( $value as $newValue ) {

										if ( is_object( $newValue ) ) {

											$return[ $prop->getName() ][] = $this->toArrayMagic( $newValue );
										} else
											$return[ $prop->getName() ] = $value;
									}
								} else
									$return[ $prop->getName() ] = $value;
							}
						} else
							$return[ $prop->getName() ] = $value;
					}
				} else
					$return[ $prop->getName() ] = $value;
			}
			else if ( is_callable( array( $obj, $methodNameIs ) ) ) {
				//Cast the value to boolean
				$return[ $prop->getName() ] = ( bool ) $obj->{$methodNameIs}();
			}
		}

		//Not sure why it doesn't read the id property, so that's way we are adding it manually.
		if ( is_callable( array( $obj, 'getId' ) ) ) {
			$return[ 'id' ] = $obj->getId();
		}

		if ( empty( $return ) ) {
			//the array is empty, convert to empty object
			$return = new \stdClass();
		}

		return $return;
	}

	public function convert ( $object ) {

		if ( !is_object( $object ) && !is_array( $object ) ) {
			return $object;
		}

		 

		$return = $this->toArrayMagic( $object );


		return $return;
	}

}
