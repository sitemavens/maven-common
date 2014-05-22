<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 *
 * @author Emiliano Jankowski
 */
class Language {

	private $languageDomain="";
	
	public function __construct( $domain ) {
		$this->languageDomain = $domain;
	}
	
	
	/**
	 * Retrieves the translation of $text. If there is no translation, or
	 * the domain isn't loaded, the original text is returned.
	 *
	 * @param string $text Text to translate
	 * @return string Translated text
	 */
	public function __( $text ) {
		return __( $text, $this->languageDomain );
	}
	
	
	/**
	 * Displays the returned translated text from translate().
	 *
	 * @see translate() Echoes returned translate() string
	 *
	 * @param string $text Text to translate
	 */
	function _e($text) {
		_e($text, $this->languageDomain);
	}
	
	/**
	* Retrieve translated string with gettext context
	*
	* Quite a few times, there will be collisions with similar translatable text
	* found in more than two places but with different translated context.
	*
	* By including the context in the pot file translators can translate the two
	* strings differently.
	*
	*
	* @param string $text Text to translate
	* @param string $context Context information for the translators
	* @return string Translated context string without pipe
	*/
	function _x($text, $context) {
		return _x($text, $context, $this->languageDomain);
	}
	
	function _ex($text, $context) {
		_ex($text, $context);
	}
	
}