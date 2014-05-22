<?php

namespace Maven\Core\Domain;

class AttributeFilter {

	private $name;
	
	private function protectField( $field ){
		
		if ( !( $field instanceof \Maven\Core\MavenDateTime ) ) {
			return esc_sql( sanitize_text_field( $field ) );
		}

		return $field;
	}

	
	public function __construct() {
		;
	}

	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}
}

