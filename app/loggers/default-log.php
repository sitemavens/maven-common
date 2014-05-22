<?php

namespace Maven\Loggers;

class DefaultLog extends Log {

	public function message ( $message ) {
		if ( $this->isEnabled() ) {

			if ( is_object( $message ) ) {
				$obj = serialize( $message );
				error_log( $obj );
			} else {
				error_log( $message );
			}
		}
	}

}
