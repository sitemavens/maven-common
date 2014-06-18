<?php

namespace Maven\Settings;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class Option extends \Maven\Core\DomainObject{

	private $name;
	private $label;
	private $type;
	private $value;
	private $defaultValue;
	private $group;
	private $options = array();

	/**
	 *
	 * @var \Maven\Core\UI\OptionOutputGenerator
	 */
	private $outputGenerator;

	/**
	 * @param string $name
	 * @param string $label
	 * @param mixed $value
	 * @param mixed $defaultValue
	 * @param \Maven\Settings\OptionType $type
	 * @param \Maven\Core\UI\OptionOutputGenerator $outputGenerator Object that deal with how the option will be render. By default it use \Maven\Core\UI\DefaultOptionOutputGenerator
	 */
	public function __construct( $name, $label, $value = "", $defaultValue = "", $type = OptionType::Input, $group = "General", $outputGenerator = null ) {

		$this->setId( $name );
		$this->name = $name;
		$this->label = $label;
		$this->type = $type;
		$this->value = $value;
		$this->defaultValue = $defaultValue;
		$this->group = $group;

		//TODO: The output generator shoudn't be in the constructor.
		//Check if there is a custom render, or we need to use the default
		if ( !$outputGenerator ) {
			$this->outputGenerator = new \Maven\Core\UI\DefaultOptionOutputGenerator( $this );
		}
	}

	public function getOutputGenerator() {
		return $this->outputGenerator;
	}

	public function setOutputGenerator( $value ) {
		$this->outputGenerator = $value;
	}

	public function getDefaultValue() {
		return $this->defaultValue;
	}

	public function setDefaultValue( $value ) {
		$this->defaultValue = $value;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue( $value ) {
		$this->value = $value;
	}

	public function getLabel() {
		return $this->label;
	}

	public function setLabel( $value ) {
		$this->label = $value;
	}

	public function getName() {
		return $this->name;
	}

	public function setName( $value ) {
		$this->name = $value;
	}

	public function getType() {
		return $this->type;
	}

	public function setType( $value ) {
		$this->type = $value;
	}

	public function getGroup() {
		return $this->group;
	}

	public function setGroup( $group ) {
		$this->group = $group;
	}

	public function render() {
		$this->outputGenerator->render();
	}

	public function getRenderedCode() {
		return $this->outputGenerator->getRenderedCode();
	}
	
	public function getOptions () {
		return $this->options;
	}

	public function setOptions ( $options ) {
		$this->options = $options;
	}



}

class OptionType {

	const Input = 'input';
	const TextArea = 'textarea';
	const Password = 'password';
	const DropDown = 'dropdown';
	const WPDropDownPages = 'wpdropdownpages';
	const WPEditor = 'wpeditor';
	const ReadOnly = 'readonly';
	const CheckBox = 'checkbox';

}