<?php

namespace Maven\Core\UI;

class HtmlComponent {

	/**
	 * 
	 * @param type $args
	 * @return type
	 */
	public static function label( $args = array( ) ) {

		$defaults = array(
			'value' => '', 'for' => '',
			'class' => '', 'echo' => true,
		);

		$r = wp_parse_args( $args, $defaults );
		extract( $r, EXTR_SKIP );


		if ( $for )
			$for = "for='" . esc_attr( $for ) . "'";

		if ( $class )
			$class = "class='" . esc_attr( $class ) . "'";

		// Translate the value
		$value = _( $value );

		$output = "<label {$for} >{$value}</label>";

		if ( $echo )
			echo $output;

		return $output;
	}

	/**
	 * Render an html hidden input
	 * @param type $id
	 * @param type $value
	 * @param type $class
	 */
	public static function hidden( $id, $value, $class = "" ) {

		self::_input( $id, $value, 'hidden', $class, false );
	}

	/**
	 * Render an hidden input with the action setted.
	 * @param type $value
	 */
	public static function actionHidden( $value ) {

		self::_input( \Maven\Constants::$mavenActionName, $value, 'hidden', false, false );
	}

	/**
	 * Create an action button
	 * @param string $value
	 * @param string $action
	 * @param string $class
	 * @param boolean $attr 
	 */
	public static function actionButton( $value, $action, $class = '', $attr = false ) {

		$actionName = \Maven\Constants::$mavenActionName;

		self::submit( "{$actionName}-{$action}", $value, $class, $attr );
	}

	public static function actionCancel( $class = "button" ) {


		self::actionButton( 'Cancel', "cancel", $class );
	}
	
	public static function actionSave( $class = "button-primary" ) {

		self::actionButton( 'Save', "save", $class );
	}

	public static function password( $id, $value, $class = "", $attr = false ) {

		self::_input( $id, $value, 'password', $class = "", $attr );
	}

	/**
	 *
	 * @param type $id
	 * @param type $value
	 * @param type $class
	 * @param type $attr 
	 */
	public static function submit( $id, $value, $class = '', $attr = false ) {

		self::_input( $id, $value, 'submit', $class, $attr );
	}

	/**
	 *
	 * @param type $id
	 * @param type $value
	 * @param type $class
	 * @param type $attr 
	 * @param type $echo 
	 */
	public static function input( $id, $value = '', $class = '', $attr = false, $echo = true ) {

		return self::_input( $id, $value, 'text', $class, $attr, $echo );
	}

	public static function fileUpload( $id, $value = '', $class = '', $attr = false ) {

		self::_input( $id, $value, 'file', $class, $attr );
	}

	public static function check( $id, $value, $checked, $class = "", $attr = false ) {

		if ( $value == $checked ) {
			if ( is_array( $attr ) )
				$attr[ 'checked' ] = 'checked';
			else
				$attr .= 'checked="checked"';
		}

		self::_input( $id, $checked, 'checkbox', $class, $attr );
	}

	/**
	 * 
	 * @param string $id
	 * @param string $value
	 * @param string $class
	 * @param int $columns
	 * @param int $rows
	 * @param string $attrs
	 */
	public static function textArea( $id, $value, $class = "", $columns = "70", $rows = "4", $attrs = false ) {

		if ( $_POST && isset( $_POST[$id ] ) ) 
			$value = $_POST[$id ];
		
		$atts = self::getAttr( $attrs );

		if ( $class )
			$class = "class='{$class}'";
			
			

		echo "<textarea name='{$id}' id='{$id}' columns='{$columns}' $class rows='{$rows}' {$atts}>{$value}</textarea>";
	}

	/**
	 * Retrieve or display a dropdown of the values list sent.
	 *
	 * @since 0.1.0
	 *
	 * $args = array(
	  'show_option_all' => '', 'show_option_none' => '',
	  'echo' => true, 'selected' => '', 'name' => 'maven-dropdown',
	  'class' => '', 'id' => '',
	  'values' => array(), 'show_field' => '', 'text_domain' => 'maven'
	  );
	 * 
	 * @param array|string $args Optional. Override default arguments.
	 * @return string HTML content, if not displaying.
	 * @deprecated 1
	 */
	public static function _dropdown( $args = '' ) {

		$defaults = array(
			'show_option_all' => '', 'show_option_none' => '',
			'echo' => true, 'selected' => '', 'name' => 'maven-dropdown',
			'class' => 'mvn-dropdown', 'id' => '',
			'values' => array( ), 'show_field' => '', 'text_domain' => 'maven',
			'extra_atts' => ''
		);

		$r = wp_parse_args( $args, $defaults );
		extract( $r, EXTR_SKIP );

		//$extra_atts = esc_attr($extra_atts);
		$output = '';
		if ( !empty( $values ) && count( $values ) > 0 ) {
			$name = esc_attr( $name );
			$id = $id ? " id='" . esc_attr( $id ) . "'" : " id='$name'";

			$output = "<select name='{$name}'{$id} class='{$class}' {$extra_atts}>\n";

			if ( $show_option_all )
				$output .= "\t<option value='0'>" . esc_html( $show_option_all ) . "</option>\n";

			if ( $show_option_none ) {
				$output .= "\t<option value='' >" . esc_html( $show_option_none ) . "</option>\n";
			}

			foreach ( ( array ) $values as $key => $value ) {
				$method_selected = selected( $key, $selected, false );
				$text = '';
				if ( is_string( $value ) ) {
					$text = $value;
				} else {
					if ( !empty( $show_field ) && is_array( $value ) && isset( $value[ $show_field ] ) )
						$text = $value[ $show_field ];
					if ( !empty( $show_field ) && is_object( $value ) && isset( $value->$show_field ) )
						$text = $value->$show_field;
				}

				$output .= "\t<option value='" . esc_attr( $key ) . "'$method_selected>" . esc_html__( $text, $text_domain ) . "</option>\n";
			}

			$output .= "</select>";
		}

		if ( $echo )
			echo $output;

		return $output;
	}
	
	public function monthsDropDown( $id){
		
		$mavenDate  =new Maven\Core\MavenDateTime();
		$months = $mavenDate->getMonths();
		
		
		if ( $_POST && isset( $_POST[$id ] ) ) 
			$value = $_POST[$id ];
		
		$atts = self::getAttr( $attrs );

		if ( $class )
			$class = "class='{$class}'";
			
			

		echo "<textarea name='{$id}' id='{$id}' columns='{$columns}' $class rows='{$rows}' {$atts}>{$value}</textarea>";
		
		
	}
	
	public static function dropDown( $id, $values, $shopEmptyOption, $class, $attrs){
		
		$output = "<select name='{$name}'{$id} class='{$class}' {$extra_atts}>\n";
		
		if ( $shopEmptyOption ) {
				$output .= "\t<option value='' >" . esc_html( $shopEmptyOption ) . "</option>\n";
			}
			
		foreach ( $values as $key=>$value ){
			
		}
	}
	
	public function yearsDropDown(){
		
	}

//	public static function dropdown( $id, $values, $selected=false,$class='',$attrs=false ){
//		
//		$atts = self::getAttr($attrs);
//		
//		if ( $class )
//			$class = "class='{$class}'";
//		
//		$select = "<select name='{$id}' id='{$id}' $class {$atts}>";
//		
//		foreach ( $values as $key => $value ){
//			if ($key == $selected)
//				$select.= "<option value='{$key}' selected='selected'>{$value}</option>";
//			else
//				$select.= "<option value='{$key}'>{$value}</option>";
//		}
//		
//		$select.='</select>';
//		
//		echo $select;
//	}

	public static function dropdownMultiple( $id, $values, $selected_values = array( ), $class = '', $attrs = false ) {

		$atts = self::getAttr( $attrs );

		if ( $class )
			$class = "class='{$class}'";

		$select = "<select name='{$id}' id='{$id}' $class {$atts}>";

		if ( !is_array( $selected_values ) )
			$selected_values = array( );

		foreach ( $values as $key => $value ) {
			if ( in_array( $key, $selected_values ) )
				$select.= "<option value='{$key}' selected='selected'>{$value}</option>";
			else
				$select.= "<option value='{$key}'>{$value}</option>";
		}

		$select.='</select>';

		echo $select;
	}

	public static function button( $id, $value, $label, $class ) {

		if ( $class )
			$class = "class='{$class}'";

		$label = _( $label );
		echo "<button {$class} value='{$value}' name='{$id}' id='{$id}' type='submit'><span>{$label}</span></button>";
	}

	public static function wpDropDownPages( $id, $value ) {


		wp_dropdown_pages( array( 'show_option_none' => '&nbsp;', 'name' => $id, 'id' => $id, 'selected' => $value ) );
	}

	public static function wpEditor( $id, $value ) {
		wp_editor( $value, $id );
	}

	private static function getAttr( $attrs ) {
		$atts = "";

		if ( $attrs ) {
			if ( is_array( $attrs ) ) {
				foreach ( $attrs as $key => $value ) {
					$atts .= " {$key}='$value'";
				}
			}
			else
				$atts = $attrs;
		}

		return $atts;
	}

	 
	private static function _input( $id, $value, $type, $class, $attrs, $echo = true ) {

		if ( $_POST && isset( $_POST[$id ] ) ) 
			$value = $_POST[$id ];
		
		$atts = self::getAttr( $attrs );

		if ( $class )
			$class = "class='{$class}'";

		$html = "<input type='{$type}' $class name='{$id}' id='{$id}' value='{$value}' $atts />";
		if ( $echo )
			echo $html;
		else 
			return $html;
		
	}
	
	public static function jSonComponent($variable, $values){
		?>
	
		<script type="text/javascript">
			/* <![CDATA[ */
			var <?php echo $variable ?> = <?php echo $values; ?>;
		/* ]]> */

		</script>
		<?php
	}

//	private function create_label($css) {
//		return "<label id='{$this->id}' {$css} name='{$this->id}' >{$this->value}</label>";
//	}
//
//	private function create_password($css) {
//		return "<input type='password' id='{$this->id}' {$css} name='{$this->id}' value='{$this->value}' />";
//	}
//
//	private function create_readonly($css) {
//		return "<input id='{$this->id}' readonly='readonly' type='text' {$css} name='{$this->id}' value='{$this->value}' />";
//	}
//
//	private function create_input($css) {
//		return "<input id='{$this->id}' type='text' {$css} name='{$this->id}' value='{$this->value}' />";
//	}
//
//	private function create_hidden() {
//		return "<input id='{$this->id}' type='hidden'  name='{$this->id}' value='{$this->value}' />";
//	}
//	
//	private function create_checkbox($css) {
//		$checked = $this->value == "1" ? "checked='checked'" : "";
//		return "<input type='checkbox'  id='{$this->id}' {$css} name='{$this->id}' value='1' {$checked} />";
//	}
//
//	private function create_dropdown($css) {
//
//		$drop = "<select name='{$this->id}' id='{$this->id}' {$css}>";
//
//		if (is_array($this->value)) {
//			foreach ($this->value as $val) {
//				$drop.="<option value='{$val}'>{$val}</option>";
//			}
//		} else if (is_a($this->value, "maven_addon_option_list_value")) {
//			foreach ($this->value->values as $val) {
//				$selected = $this->value->selected_value == $val ? "selected='selected'" : "";
//				$drop.="<option {$selected} value='{$val}'>{$val}</option>";
//			}
//		}
//		else
//			$drop.="<option value='{$this->value}'>{$this->value}</option>";
//
//		$drop.="</select>";
//
//		return $drop;
//	}
//
//	function get_value($default="") {
//		if ($this->value === false)
//			return $this->value;
//
//		return $default;
//	}
}