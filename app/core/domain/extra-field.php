<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class ExtraField extends \Maven\Core\DomainObject {

	const BooleanExtrafield = 'boolean';
	const TextExtraField = 'text';
	const DefaultGroupName= 'Extra Information';

	protected $label;
	protected $value;
	protected $group;
	protected $type;
	
	public function __construct( $label = '', $value = '', $id = false ) {

		parent::__construct( $id );

		$this->label = $label;
		$this->value = $value;
		//set a group by default
		$this->group = self::DefaultGroupName;
		$this->type = self::TextExtraField;

		$rules = array(
		    'label' => \Maven\Core\SanitizationRule::Text,
		    'value' => \Maven\Core\SanitizationRule::Text,
		    'group' => \Maven\Core\SanitizationRule::Text
		);

		$this->setSanitizationRules( $rules );
	}

	public function getLabel() {
		return $this->label;
	}

	public function setLabel( $label ) {
		$this->label = $label;
	}

	public function getValue() {
		switch ( $this->getType() ) {
			case self::BooleanExtrafield:
				if ( $this->value )
					return 'Yes';
				return 'No';

				break;

			case self::TextExtraField:
			default:return $this->value;
				break;
		}
		return $this->value;
		//return $this->value;
	}

	public function getValueFormatted() {
		
	}

	public function setValue( $value ) {
		$this->value = $value;
	}

	public function getHash() {
		
	}

	public function getGroup() {
		if(isset($this->group))
			return $this->group;
		
		return self::DefaultGroupName;
	}

	public function setGroup( $group ) {
		$this->group = $group;
	}

	public function getType() {
		return $this->type;
	}

	public function setType( $type ) {
		$this->type = $type;
	}

}
