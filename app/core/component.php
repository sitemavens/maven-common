<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class Component{
	
	private $title;
	private $type;
	private $defaultAction;
	private $slug;
	private $key;
	private $isAdmin;
	private $components = array();
	private $wpMedia = false;
	
	public function __construct( $isAdmin = true ) {
		$this->isAdmin = $isAdmin;
		
		//define initial erro messages
		$this->errorMessages=array(
			'required'	=> '{0} is required!',//This should be like this? _('required'),
			'acceptance'	=> '{0} must be accepted',
			'min'		=> '{0} must be greater than or equal to {1}',
			'max'		=> '{0} must be less than or equal to {1}',
			'range'		=> '{0} must be between {1} and {2}',
			'length'	=> '{0} must be {1} characters',
			'minLength'	=> '{0} must be at least {1} characters',
			'maxLength'	=> '{0} must be at most {1} characters',
			'rangeLength'	=> '{0} must be between {1} and {2} characters',
			'oneOf'		=> '{0} must be one of: {1}',
			'equalTo'	=> '{0} must be the same as {1}',
			'pattern'	=> '{0} must be a valid {1}'
		);
	}

	public function isAdmin() 
	{
		return $this->isAdmin;
	}

	public function setRelatedComponent( $key, Component $component ) {
		$this->components[ $key ] = $component;
	}

	public function getRelatedComponent( $key ) {
		if ( isset( $this->components[ $key ] ) )
			return $this->components[ $key ];

		return null;
	}

	public function getKey() 
	{
		return $this->key;
	}

	public function setKey( $value ) 
	{
		$this->key = $value;
	}
	
	/**
	 *
	 * @var \Maven\Core\Resource[]
	 */
	private $scriptResources = array();
	
	/**
	 *
	 * @var \Maven\Core\Resource[]
	 */
	private $styleResources = array();
	
	/**
	 *
	 * @var array
	 */
	private $localizations = array();
	
	/**
	 *
	 * @var array
	 */
	private $errorMessages = array();
	
	/**
	 *
	 * @var \Maven\Settings\Registry 
	 */
	private $registry;

	/**
	 * Get the component registry
	 * @return \Maven\Settings\Registry 
	 */
	public function getRegistry() 
	{
		return $this->registry;
	}

	/**
	 * Set the component registry
	 * @param \Maven\Settings\Registry  $value
	 */
	public function setRegistry( $value ) 
	{
		$this->registry = $value;
	}
	
	/**
	 *
	 * @var ComponentAction[] 
	 */
	private $actions = array();
	
	/**
	 *
	 * @var ComponentAction[] 
	 */
	private $ajaxActions = array();
	

	public function getSlug() 
	{
		return $this->slug;
	}

	public function setSlug($value) 
	{
		$this->slug = $value;
	}

	public function getDefaultAction() 
	{
		return $this->defaultAction;
	}

	public function setDefaultAction($value) 
	{
		$this->defaultAction = $value;
	}

	public function getType() 
	{
		return $this->type;
	}

	public function setType($value) 
	{
		$this->type = $value;
	}
	public function getTitle() 
	{
		return $this->title;
	}
	
	public function getUrl() 
	{
		return sanitize_title( $this->title );
	}

	public function setTitle($value) 
	{
		$this->title = $value;
	}
	
	public function addAction( $action, $alias = ''){
		 
		$this->actions[$action] = new ComponentAction( $action, $alias );
	}
	
	public function getActionString( $action ){
		
		if ( ! $this->actions[$action] )
			throw new \Exception("The action wasn't registered: {$action}");
			
		$actionName = \Maven\Constants::$mavenActionName;
		
		return "page={$this->slug}&$actionName={$action}";
		
	}
	
	public function isValidAction( $action ){
		
		//Check if it is a regular action
		if ( isset( $this->actions[ $action ] ) )
				return true;
		
		//Check if it is an ajax action
		if ( isset( $this->ajaxActions[ $action ] ) )
			return true;
		
		return false;
	}
	
	/**
	 * 
	 * @param string $name
	 * @param string $file
	 * @param array $deps
	 */
	public function addScriptResource (  $name, $file = "", $deps = array()  ){
		
		//TODO: This hardcoded paths here, doesn't like me too much.
		$fullPath = "";
		if ( $file )
			$fullPath = $this->getRegistry()->getPluginUrl()."/admin/assets/js/".$file;
		
		
		$resource = new \Maven\Core\Resource( $name, $fullPath, $this->getRegistry()->getPluginVersion(), $deps );
		$this->scriptResources[] = $resource;
		
	}
	
	/**
	 * 
	 * @param string $name
	 * @param string $file
	 * @param array $deps
	 */
	public function addGlobalScriptResource (  $name, $file = "", $deps = array()  ){
		
		//TODO: This hardcoded paths here, doesn't like me too much.
		$fullPath = "";
		if ( $file )
			$fullPath = $this->getRegistry()->getPluginUrl()."/assets/js/".$file;
		
		
		$resource = new \Maven\Core\Resource( $name, $fullPath, $this->getRegistry()->getPluginVersion(), $deps );
		$this->scriptResources[] = $resource;
		
	}
	
	/**
	 * 
	 * @param string $name
	 * @param string $file
	 * @param array $deps
	 */
	public function addUrlScriptResource (  $name, $file = "", $deps = array()  ){
		
		$resource = new \Maven\Core\Resource( $name, $file, $this->getRegistry()->getPluginVersion(), $deps );
		$this->scriptResources[] = $resource;
		
	}
	
	public function addWpMedia(){
		
		$this->wpMedia = true;
		
	}
	
	public function wpMedia(){
		return $this->wpMedia;
	}
	
	
	public function addStyleResource (  $name, $file = "", $deps = array()  ){
		
		$fullPath = "";
		if ( $file )
			$fullPath = $this->getRegistry()->getPluginUrl()."/admin/assets/css/".$file;
		
		$resource = new \Maven\Core\Resource( $name, $fullPath, $this->getRegistry()->getPluginVersion(), $deps );
		$this->styleResources[] = $resource;
		
	}
	
	public function addGlobalStyleResource (  $name, $file = "", $deps = array()  ){
		
		$fullPath = "";
		if ( $file )
			$fullPath = $this->getRegistry()->getPluginUrl()."/assets/css/".$file;
		
		$resource = new \Maven\Core\Resource( $name, $fullPath, $this->getRegistry()->getPluginVersion(), $deps );
		$this->styleResources[] = $resource;
		
	}
	
	/**
	 * 
	 * @param string $action
	 * @param string $alias
	 */
	public function addAjaxAction( $action, $alias = ""){
		$this->ajaxActions[ $action ] = new ComponentAction( $action, $alias );
	}
	
	/**
	 * 
	 * @return ComponentAction[]
	 */
	public function getAjaxActions(){
		return $this->ajaxActions;
	}
	
	/**
	 * 
	 * @return \Maven\Core\Resource
	 */
	public function getScriptResources(){
		return $this->scriptResources;
	}
	
	/**
	 * 
	 * @return \Maven\Core\Resource
	 */
	public function getStyleResources(){
		return $this->styleResources;
	}
	
	public function addLocalizations( $localizations ){
		$this->localizations = $localizations;
	}
	
	public function getTranslations( ){
		return $this->localizations;
	}
	
	public function addErrorMessages( $errorMessages ){
		$this->errorMessages = $errorMessages;
	}
	
	public function getErrorMessages( ){
		return $this->errorMessages;
	}
	
	public function addErrorMessage( $key, $message){
		$this->errorMessages[$key]=$message;
	}
	
	public function getErrorMessage( $key ){
		return $this->errorMessages[$key];
	}
	
}

class ComponentAction{
	
	private $name;
	private $alias;

	public function __construct( $name, $alias ){

		$this->name = $name;
		$this->alias = $alias;
		
	}
	
	public function getAlias() 
	{
		return $this->alias;
	}

	public function setAlias( $value ) 
	{
		$this->alias = $value;
	}
	
	public function getName() 
	{
		return $this->name;
	}

	public function setName($value) 
	{
		$this->name = $value;
	}
	
	
}