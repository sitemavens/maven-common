<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class FillerHelper {

	public static function fillObject( $object, $row ) {
		if ( $row ) {

			self::fill( $object, $row );
		}
	}

	private static function fill( $object, $row ) {

		if ( ! $row )
			return;



		$reflection = new \ReflectionClass( $object );

		foreach ( $row as $key => $value ) {
			$propName = \Maven\Core\Utils::toCamelCase( $key, true );
			$setter = 'set' . $propName;
			$getter = 'get' . $propName;
			$isGetter = 'is' . $propName;

			if ( is_callable( array( $object, $isGetter ) ) ) {

				if ( ! is_bool( $value ) ) {
					if ( $value === 'false' ) {
						$value = false;
					} elseif ( $value === 'true' ) {
						$value = true;
					} elseif ( $value === '1' ) {
						$value = true;
					}elseif ( $value === '0' ) {
						$value = false;
					}
				}

				$object->{$setter}( $value );
				continue;
			}

			if ( is_callable( array( $object, $getter ) ) ) {
				//TODO: It's not the best way to do it, but it's something.
				//We need to get the value in order to know the type.
				$proValue = $object->{$getter}();

				// We need to get the method, so then we can read the doc
				$method = $reflection->getMethod( $getter );

				$comment = $method->getDocComment();
				$collectionType = '';

				if ( $comment ) {
					// Check if it is a collection
					preg_match_all( '/@collectionType:?\s+([^\s]+)/', $comment, $matches );

					if ( ! empty( $matches[ 1 ] ) ) {
						$collectionType = $matches[ 1 ][ 0 ];

						// If it is a collection, we need to iterate the items
						if ( $collectionType ) {

							$itemsObjs = array();

							// If the object isn't instantite lets do it
							if ( ! $value )
								$value = array();
							//throw new \Maven\Exceptions\MavenException('You need to instantiate the object:'.$collectionType);

							foreach ( $value as $item ) {

								$itemObj = new $collectionType;

								self::fill( $itemObj, $item );

								$itemsObjs[] = $itemObj;
							}

							$object->{$setter}( $itemsObjs );

							continue;
						}
					} else {

						// Check if it is a serialized object
						preg_match_all( '/@serialized:?\s+([^\s]+)/', $comment, $matches );

						if ( ! empty( $matches[ 1 ] ) ) {
							if ( is_array( $value ) ) {
								//Already unserialized, assign values directly
								if ( is_object( $proValue ) ) {
									if ( $value )
										self::fill( $proValue, $value );
								}
								else {
									if ( method_exists( $object, $setter ) ) {
										$object->{$setter}( $value );
									}
								}
							} else {
								//TODO: Is this ok? if the database value is null. 
								$data = @unserialize( $value );
								if ( $data != false ) {
									$object->{$setter}( $data );
								}
							}
							continue;
						}
					}
				}

				if ( is_object( $proValue ) ) {
					if ( $value )
						self::fill( $proValue, $value );
				}
				else if ( method_exists( $object, $setter ) ) {
					$object->{$setter}( $value );
				}
			} else if ( method_exists( $object, $setter ) ) {
				$object->{$setter}( $value );
			}
		}
	}

}
