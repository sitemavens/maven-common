<?php

namespace Maven\Front;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
    exit;

class FormField {

    private $type = 'text';
    private $label = '';
    private $placeholder = '';
    private $maxLenght = false;
    private $required = false;
    private $class = array();
    private $labelClass = array();
    private $return = false;
    private $options = array();
    private $customAttributes = array();
    private $validate = array();
    private $default = '';
    private $key;
    private $value;

    public function getHtml () {

        if ( $this->isRequired() ) {
            $this->customAttributes[] = 'data-required="true"';
            $required = " <span class = 'req'>*</span>";
        } else {
            $required = '';
        }


        $this->setMaxLenght( ( $this->getMaxLenght() ) ? 'maxlength="' . absint( $this->getMaxLenght() ) . '"' : ''  );

        $value = '';

        if ( is_null( $this->getValue() ) )
            $value = $this->getDefault();

        // Custom attribute handling
        $customAttributes = array();

        if ( !empty( $this->getCustomAttributes() ) && is_array( $this->getCustomAttributes() ) )
            foreach ( $this->getCustomAttributes() as $attribute => $attribute_value )
                $customAttributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';


        switch ( $this->getType() ) {
            case 'text':
                $field = "<div class = 'field-input'>";

                if ( $this->getLabel() )
                    $field .= '<label for="' . esc_attr( $this->getKey() ) . '" class="' . implode( ' ', $this->getLabelClass() ) . '">' . $this->getLabel() . $required . '</label>';

                $field .= '<input type="text" class="input-text" name="' . esc_attr( $this->getKey() ) . '" id="' . esc_attr( $this->getKey() ) . '" placeholder="' . $this->getPlaceholder() . '" ' . $this->getMaxLenght() . ' value="' . esc_attr( $value ) . '" ' . implode( ' ', $this->getCustomAttributes() ) . ' />';

                $field = "</div>";

                break;

            default:
                break;
        }


        return $field;
    }

    public function getType () {
        return $this->type;
    }

    public function setType ( $type ) {
        $this->type = $type;
    }

    public function getLabel () {
        return $this->label;
    }

    public function setLabel ( $label ) {
        $this->label = $label;
    }

    public function getPlaceholder () {
        return $this->placeholder;
    }

    public function setPlaceholder ( $placeholder ) {
        $this->placeholder = $placeholder;
    }

    public function getMaxLenght () {
        return $this->maxLenght;
    }

    public function setMaxLenght ( $maxLenght ) {
        $this->maxLenght = $maxLenght;
    }

    public function isRequired () {
        return $this->required;
    }

    public function setRequired ( $required ) {
        $this->required = $required;
    }

    public function getClass () {
        return $this->class;
    }

    public function setClass ( $class ) {
        $this->class = $class;
    }

    public function getLabelClass () {
        return $this->labelClass;
    }

    public function setLabelClass ( $labelClass ) {
        $this->labelClass = $labelClass;
    }

    public function getReturn () {
        return $this->return;
    }

    public function setReturn ( $return ) {
        $this->return = $return;
    }

    public function getOptions () {
        return $this->options;
    }

    public function setOptions ( $options ) {
        $this->options = $options;
    }

    public function getCustomAttributes () {
        return $this->customAttributes;
    }

    public function setCustomAttributes ( $customAttributes ) {
        $this->customAttributes = $customAttributes;
    }

    public function getValidate () {
        return $this->validate;
    }

    public function setValidate ( $validate ) {
        $this->validate = $validate;
    }

    public function getDefault () {
        return $this->default;
    }

    public function setDefault ( $default ) {
        $this->default = $default;
    }

    public function getKey () {
        return $this->key;
    }

    public function setKey ( $key ) {
        $this->key = $key;
    }

    public function getValue () {
        return $this->value;
    }

    public function setValue ( $value ) {
        $this->value = $value;
    }

}
