<?php

namespace Maven\Core\Domain;

class BaseFilter {
	
	public function __construct () {
		;
	}

	protected function protectField ( $field ) {

		if ( !( $field instanceof \Maven\Core\MavenDateTime ) ) {
			return esc_sql( sanitize_text_field( $field ) );
		}

		return $field;
	}

}
