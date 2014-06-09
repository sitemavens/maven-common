<?php

namespace Maven\Loggers;

class DefaultLog extends Log {

	public function message ( $message ) {
		if ( $this->isEnabled() ) {

			if ( is_object( $message ) ) {
				$obj = serialize( $message );
				error_log( $obj );
			} else if ( is_array( $message ) ) {
				error_log( print_r($message,true) );
			} else {
				error_log( $message );
			}
		}
	}

}
