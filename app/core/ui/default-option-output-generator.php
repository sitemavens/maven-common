<?php

namespace Maven\Core\UI;
use \Maven\Settings\OptionType;

 class DefaultOptionOutputGenerator extends OptionOutputGenerator {
	
	 private $option;
	 
	 /**
	 * 
	 * @param \Maven\Settings\Option $option
	 */
	public function __construct( $option ) {
		 $this->option = $option;
	}
	 

	public function render( $echo = true ) {
		
		switch ( $this->option->getType() ){
			case OptionType::DropDown:
//				'show_option_all' => '', 'show_option_none' => '',
//            'echo' => true, 'selected' => '', 'name' => 'maven-dropdown',
//            'class' => 'mvn-dropdown', 'id' => '',
//            'values' => array(), 'show_field' => '', 'text_domain' => 'maven',
//            'extra_atts' => ''
				$args = array(
					'selected'	=> $this->option->getValue(), 
					'name'		=> $this->option->getName(), 
					'id'		=> $this->option->getName(), 
				);
					
				return HtmlComponent::dropdown( $args );
				break;
			case OptionType::Input:
				return HtmlComponent::input( $this->option->getName(), $this->option->getValue(), '', array(), $echo );
				break;
			case OptionType::TextArea:
				return HtmlComponent::textarea( $this->option->getName(), $this->option->getValue() );
				break;
			case OptionType::Password:
				return HtmlComponent::password( $this->option->getName(), $this->option->getValue() );
				break;
			case OptionType::WPDropDownPages:
				return HtmlComponent::wpDropDownPages( $this->option->getName(), $this->option->getValue() );
				break;
			case OptionType::WPEditor:
				return HtmlComponent::wpEditor( $this->option->getName(), $this->option->getValue() );
				break;
			default: 
				throw new \Exception ( "Option type not implemented: ".$this->option->getType());
		}
		
	}

	public function getRenderedCode() {
		
		return $this->render( false );
		
	}
}
