<?php

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class maven_html_control {

	const input = "input";
	const dropdown = "dropdown";
	const checkbox = "checkbox";
	const password = "password";
	const label = "label";
	const readonly = "readonly";
	
	
	/**
	 * Label of the control
	 * @var string 
	 */
	public $label;

	/**
	 * More details about the option
	 * @var string
	 */
	public $description;

	/**
	 * Value of the option
	 * @var object/array
	 */
	public $value;

	/**
	 * Id of the option, it is used as id and name html attributes
	 * @var string 
	 */
	public $id;

	/**
	 * Choose the html type
	 * @var string: input,textarea,checkbox,dropdown,password
	 */
	public $type;
	public $css_class;

	/**
	 *
	 * @param string $id
	 * @param string $label
	 * @param string $type input,textarea,checkbox,dropdown,password
	 * @param string $value
	 * @param string $description 
	 */
	public function __construct($id="", $label="", $type="", $value="", $description="") {
		$this->label = $label;
		$this->description = $description;
		$this->value = $value;
		$this->id = $id;
		$this->type = $type;
	}

	/**
	 * Return the html for an option
	 * @param string $css class names
	 * @param boolean generate <label>
	 * @return string 
	 */
	public function get_html($css_class="") {
		//Override the setted class
		$this->css_class = $css_class;

		$css = $this->css_class ? "class='{$this->css_class}'" : "";
		switch ($this->type) {
			case "input":
				return $this->create_input($css);
			case "dropdown":
				return $this->create_dropdown($css);
			case "checkbox":
				return $this->create_checkbox($css);
			case "password":
				return $this->create_password($css);
			case "label":
				return $this->create_label($css);
			case "readonly":
				return $this->create_readonly($css);
		}

		return "<strong>" . __('Invalid html. Check your option type') . "</strong>";
	}

	public function get_label() {
		return "<label for='{$this->id}' >{$this->label}</label>";
	}

	private function create_label($css) {
		return "<label id='{$this->id}' {$css} name='{$this->id}' >{$this->value}</label>";
	}

	private function create_password($css) {
		return "<input type='password' id='{$this->id}' {$css} name='{$this->id}' value='{$this->value}' />";
	}

	private function create_readonly($css) {
		return "<input id='{$this->id}' readonly='readonly' type='text' {$css} name='{$this->id}' value='{$this->value}' />";
	}

	private function create_input($css) {
		return "<input id='{$this->id}' type='text' {$css} name='{$this->id}' value='{$this->value}' />";
	}

	private function create_hidden() {
		return "<input id='{$this->id}' type='hidden'  name='{$this->id}' value='{$this->value}' />";
	}
	
	private function create_checkbox($css) {
		$checked = $this->value == "1" ? "checked='checked'" : "";
		return "<input type='checkbox'  id='{$this->id}' {$css} name='{$this->id}' value='1' {$checked} />";
	}

	private function create_dropdown($css) {

		$drop = "<select name='{$this->id}' id='{$this->id}' {$css}>";

		if (is_array($this->value)) {
			foreach ($this->value as $val) {
				$drop.="<option value='{$val}'>{$val}</option>";
			}
		} else if (is_a($this->value, "maven_addon_option_list_value")) {
			foreach ($this->value->values as $val) {
				$selected = $this->value->selected_value == $val ? "selected='selected'" : "";
				$drop.="<option {$selected} value='{$val}'>{$val}</option>";
			}
		}
		else
			$drop.="<option value='{$this->value}'>{$this->value}</option>";

		$drop.="</select>";

		return $drop;
	}

	function get_value($default="") {
		if ($this->value === false)
			return $this->value;

		return $default;
	}
	

}




?>
