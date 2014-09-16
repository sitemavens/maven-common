<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class TemplateProcessor {

	const MissingValue = "<strong>MISSING VALUE</strong>";
	const DefaultEmailReceipt = "email-receipt.html";
	const BillingShippingEmailReceipt = "email-receipt-billing-shipping.html";

	private $template;
	private $variables;
	private $variableKeys;

	public function __construct ( $template, $variables ) {

		$this->template = $template;
		$this->variables = $variables;
		$this->variableKeys = array_keys( $variables );
	}

	private function addVariables () {

		foreach ( $this->variableKeys as $variable ) {
			add_shortcode( $variable, array( &$this, "processTemplate" ) );
		}
	}

	public function updateVariabe ( $key, $value ) {
		$this->variables[$key] = $value;
	}

	private function removeVariables () {
		foreach ( $this->variableKeys as $key ) {
			remove_shortcode( $key );
		}
	}

	public function processTemplate ( $atts, $content, $tag ) {

		return isset( $this->variables[$tag] ) ? $this->variables[$tag] : self::MissingValue;
	}

	public function getProcessedTemplate ( $template = "" ) {

		$template = $template ? $template : $this->template;

		//Process the form
		$this->addVariables();

		// First we process the message
		$processedTemplate = do_shortcode( $template );
		$this->removeVariables();

		return $processedTemplate;
	}

}
