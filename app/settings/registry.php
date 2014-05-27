<?php

namespace Maven\Settings;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


abstract class Registry {

	private $pluginUrl;
	private $pluginDir;
	private $pluginVersion;
	private $pluginName;
	private $pluginDirectoryName = "";
	private $options = array( );
	private $pluginKey = false;
	private $pluginShortName = false;
	private $timeZone = "";
	private $dateFormat = "";
	private $registries = array( );

	/**
	 * 
	 * @var \Maven\Core\Request $value  
	 */
	private $request = null;

	/**
	 * 
	 * @var \Maven\Core\Mail 
	 */
	private $mail = null;

	/**
	 * 
	 * @var \Maven\Core\Language 
	 */
	private $language = null;

	private function _construct() {
		
	}

	public function getPluginName() {
		return $this->pluginName;
	}

	public function setPluginName( $value ) {
		$this->pluginName = $value;
	}

	/**
	 * 
	 * @param \Maven\Core\Language $language
	 */
	public function setLanguage( \Maven\Core\Language $language ) {
		$this->language = $language;
	}

	/**
	 * 
	 * @return \Maven\Core\Language
	 */
	public function getLanguage() {
		return $this->language;
	}

	public function getLicense() {
		return $this->getValue( 'license' );
	}

	/**
	 * 
	 * @return \Maven\Core\Mail  
	 */
	public function getMail() {
		return $this->mail;
	}

	/**
	 *
	 * @param \Maven\Core\Mail $value 
	 */
	public function setMail( \Maven\Core\Mail $value ) {
		$this->mail = $value;
	}

	public function getTimeZone() {
		return $this->timeZone;
	}

	public function setTimeZone( $timeZone ) {
		$this->timeZone = $timeZone;
	}

	public function getDateFormat() {
		return $this->dateFormat;
	}

	public function setDateFormat( $dateFormat ) {
		$this->dateFormat = $dateFormat;
	}

	/**
	 * 
	 * @return \Maven\Core\Request  
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 *
	 * @param \Maven\Core\Request $value 
	 */
	public function setRequest( \Maven\Core\Request $value ) {
		$this->request = $value;
	}

	public function getPluginVersion() {
		return $this->pluginVersion;
	}

	public function setPluginVersion( $value ) {
		$this->pluginVersion = $value;
	}

	public function getPluginShortName() {
		return $this->pluginShortName;
	}

	public function setPluginShortName( $value ) {
		$this->pluginShortName = $value;
	}

	public function concatShortName( $value, $beginning = true, $separator = "-" ) {

		return $beginning ? $this->getPluginShortName() . $separator . $value : $value . $separator . $this->getPluginShortName();
	}

	public function getPluginDirectoryName() {
		return $this->pluginDirectoryName;
	}

	public function setPluginDirectoryName( $pluginDirectoryName ) {
		$this->pluginDirectoryName = $pluginDirectoryName;
	}

	public function getPluginDir() {
		return $this->pluginDir;
	}

	public function setPluginDir( $value ) {
		$this->pluginDir = $value;
	}

	public function getPluginUrl() {
		return $this->pluginUrl;
	}

	public function setPluginUrl( $value ) {
		$this->pluginUrl = $value;
	}

	/**
	 * Return the values
	 * @return \Maven\Settings\Option[]
	 */
	public function getOptions() {
		return $this->options;
	}
	 
	
	public function getKeys() {
		return array_keys( $this->options );
	}

	/**
	 * This method must be used JUST to initialize the default settings
	 * @param Option[] $values
	 * @return Option[] 
	 */
	protected function setOptions( $options ) {

		// We need to add a default license option
		if ( !isset( $this->options[ "license" ] ) ) {
			$this->options[ "license" ] = new Option(
					"license", "License", '', '', OptionType::Input
			);
		}

		foreach ( $options as $option )
			$this->options[ $option->getName() ] = $option;
	}

	/**
	 * Return a setting
	 * @param string $key
	 * @return \Maven\Settings\Option 
	 */
	public function get( $key ) {

		if ( isset( $this->options[ $key ] ) )
			return $this->options[ $key ];

		return null;
	}

	/**
	 * Return a setting
	 * @param string $key
	 * @return null 
	 */
	public function getValue( $key ) {

		if ( isset( $this->options[ $key ] ) )
			return $this->options[ $key ]->getValue();

		return null;
	}

	public function getPluginKey() {

		return $this->pluginKey;
	}

	public function setPluginKey( $value ) {

		$this->pluginKey = $value;
	}

	/**
	 * Set a setting
	 * @param string $key
	 * @param string $value 
	 */
	public function set( $key, $value ) {

		if ( isset( $this->options[ $key ] ) )
			$this->options[ $key ]->setValue( $value );
	}

	/**
	 * Save settings
	 * @param \Maven\Settings\Option[] $options
	 */
	public abstract function saveOptions( $options );

	public abstract function init();

	public abstract function reset();

	protected function addRegistry( \Maven\Settings\Registry $registry ) {
		$this->registries[ $registry->getPluginKey() ] = $registry;
	}

	/**
	 * Get registered plugin registry by key
	 * @param string $pluginKey
	 * @return \Maven\Settings\Registry | boolean
	 */
	public function getPluginRegistry( $pluginKey ) {

		if ( isset( $this->registries[ $pluginKey ] ) )
			return $this->registries[ $pluginKey ];

		return false;
	}
	
 
	
	public function getAssetsUrl(){
		return $this->getPluginUrl()."assets/";
		
	}
	
	public function getAssetsPath(){
		return $this->getPluginDir()."assets/";
		
	}
	
	public function getTemplatePath( ){
		return $this->getPluginDir()."assets/templates/";
		
	}
	
	public function getTemplateUrl(){
		return $this->getPluginUrl()."assets/templates/";
		
	}
	
	public function loadTemplate( $templateName, $data ){
		
		return \Maven\Core\Loader::load($this->getTemplatePath(), $templateName, $data, false, true);
		
	}
	
	
	public function getImagesUrl(){
		return $this->getPluginUrl()."images/";
		
	}
	
	
	public function getAdminAssetsUrl(){
		return $this->getPluginUrl()."admin/assets/";
		
	}
	
	public function getAdminScriptsUrl(){
		return $this->getPluginUrl()."admin/assets/js/";
		
	}
	
	public function getAdminWpScriptsUrl(){
		return $this->getPluginUrl()."admin/assets/js/wp/";
		
	}
	
	public function getAdminImagesUrl(){
		return $this->getAssetsUrl()."images/";
		
	}
	
	public function isDevEnv(){
		return defined( 'DEV_ENV' ) && DEV_ENV;
	}
	
	public function getBowerComponentUrl() {
		return $this->getPluginUrl() . "bower_components/";
	}

	public function getScriptsUrl() {
		return $this->getPluginUrl() . "scripts/";
	}
	
	public function getScriptsDir() {
		return $this->getPluginDir() . "scripts/";
	}

	public function getStylesUrl() {
		return $this->getPluginUrl() . "styles/";
	}

	public function getViewsUrl(){
		return $this->getPluginUrl() . "views/";
	}
	
	abstract function getEmailNotificationsTo();

	public function getRegistries () {
		return $this->registries;
	}
 

	
}
