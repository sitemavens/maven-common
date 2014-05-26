<?php

namespace Maven\Core\Ui;

class OutputTranslator {

	public function __construct() {
		;
	}

	public function sendApiResponse( $object ) {
		wp_send_json( $this->convert( $object ) );
	}

	public function sendData( $object ) {

		wp_send_json_success( $this->convert( $object ) );
	}

	public function sendError( $object ) {

		wp_send_json_error( $this->convert( $object ) );
	}

	public function convert( $object ) {

		$objToSend = null;

		if ( $object instanceof \Maven\Core\DomainObject ) {

			return $object->toArray();
		} else if ( is_array( $object ) ) {

			foreach ( $object as $domainObj ) {

				if ( $domainObj instanceof \Maven\Core\DomainObject ) {
					$objToSend[] = $domainObj->toArray();
				}
			}
		}

		return $objToSend ? $objToSend : $object;
	}

}
