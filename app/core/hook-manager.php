<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class HookManager {

	private $actions = array();
	private $filters = array();

	/**
	 * 
	 * @var \Maven\Settings\Registry  
	 */
	private $registry;
	private static $instance;

	public function __construct () {
		
	}

	/**
	 * 
	 * @return HookManager 
	 */
	static function instance () {
		if ( !isset( self::$instance ) ) {
			self::$instance = new self( );
		}

		return self::$instance;
	}

	/**
	 * 
	 * @return \Maven\Settings\Registry   
	 */
	public function getRegistry () {
		return $this->registry;
	}

	/**
	 *
	 * @param \Maven\Settings\Registry   $value 
	 */
	public function setRegistry ( \Maven\Settings\Registry $value ) {
		$this->registry = $value;
	}

	/**
	 * 
	 * @param type $action
	 * @param type $function
	 * @param type $priority
	 * @param type $acceptedArgs
	 */
	public function addAction ( $action, $function, $priority = 10, $acceptedArgs = 1 ) {

		$this->actions[ $action ][] = $function;
		add_action( $action, $function, $priority, $acceptedArgs );
	}
	
	/**
	 * 
	 * @param string $tag  The name of the filter hook.
	 * @param mixed $value The value on which the filters hooked to <tt>$tag</tt> are applied on.
	 * @return null Will return null if $tag does not exist in $wp_filter array
	 */
	public function doAction($tag, $value){
		$args = func_get_args();
		return call_user_func_array("do_action", $args);
	}

	/**
	 * 
	 * @param string $tag  The name of the filter hook.
	 * @param mixed $value The value on which the filters hooked to <tt>$tag</tt> are applied on.
	 * @param mixed $var   Additional variables passed to the functions hooked to <tt>$tag</tt>.
	 * @return mixed The filtered value after all hooked functions are applied to it.
	 */
	public function applyFilters ( $tag, $value ) {

		$args = func_get_args();
		return call_user_func_array("apply_filters", $args);
	}

	/**
	 * 
	 * @param string $filter
	 * @param string|array $function
	 * @param int $priority
	 * @param int $acceptedArgs
	 */
	public function addFilter ( $filter, $function, $priority = 10, $acceptedArgs = 1 ) {

		$this->filters[ $filter ][] = $function;
		add_filter( $filter, $function, $priority, $acceptedArgs );
	}

	public function addLoginFilter ( $function, $priority = 10, $acceptedArgs = 1 ) {

		$this->addFilter( 'wp_login', $function, $priority, $acceptedArgs );
	}

	public function addLoginFailedFilter ( $function, $priority = 10, $acceptedArgs = 1 ) {

		$this->addFilter( 'wp_login_failed', $function, $priority, $acceptedArgs );
	}
	

	/**
	 * 
	 * @param type $function
	 * @param int $priority
	 * @param int $acceptedArgs
	 */
	public function addInit ( $function, $priority = 10, $acceptedArgs = 1 ) {

		$this->addAction( 'init', $function, $priority, $acceptedArgs );
	}

	public function addWp ( $function, $priority = 10, $acceptedArgs = 1 ) {

		$this->addAction( 'wp', $function, $priority, $acceptedArgs );
	}

	public function addAdminInit ( $function, $priority = 10, $acceptedArgs = 1 ) {

		$this->addAction( 'admin_init', $function, $priority, $acceptedArgs );
	}

	public function addAdminEnqueueScripts ( $function, $priority = 10, $acceptedArgs = 1 ) {

		$this->addAction( 'admin_enqueue_scripts', $function, $priority, $acceptedArgs );
	}
	
	/**
	 * 
	 * @param type $function
	 * @param type $priority
	 * @param type $acceptedArgs
	 */
	public function addEnqueueScripts ( $function, $priority = 10, $acceptedArgs = 1 ) {

		$this->addAction( 'wp_enqueue_scripts', $function, $priority, $acceptedArgs );
	}

	/**
	 * Add lost password action
	 * @param string $function
	 * @param int $priority
	 * @param int $acceptedArgs 
	 */
	public function addLostPasswordPost ( $function, $priority = 10, $acceptedArgs = 1 ) {

		$this->addAction( 'lostpassword_post', $function, $priority, $acceptedArgs );
	}

	/**
	 * Add passwrod reset action
	 * @param type $function
	 * @param int $priority
	 * @param int $acceptedArgs 
	 */
	public function addPasswordResetAction ( $function, $priority = 10, $acceptedArgs = 1 ) {

		$this->addAction( 'lostpassword_post', $function, $priority, $acceptedArgs );
	}
	
	/**
	 * 
	 * @param type $function
	 * @param int $priority
	 * @param int $acceptedArgs
	 */
	public function addLoginAction( $function, $priority = 10, $acceptedArgs = 1 ) {
		
		$this->addAction( 'wp_login', $function, $priority, $acceptedArgs );
	}
	
	/**
	 * 
	 * @param type $function
	 * @param int $priority
	 * @param int $acceptedArgs
	 */
	public function addLogoutAction( $function, $priority = 10, $acceptedArgs = 1 ) {
		
		$this->addAction( 'wp_logout', $function, $priority, $acceptedArgs );
	}

	/**
	 * Add passwrod reset action
	 * @param type $function
	 * @param type $priority
	 * @param type $acceptedArgs 
	 */
	public function addProfileUpdateAction ( $function, $priority = 10, $acceptedArgs = 1 ) {

		$this->addAction( 'profile_update', $function, $priority, $acceptedArgs );
	}

	public function addActivation ( $file, $function ) {

		register_activation_hook( $file, $function );
	}

	public function addDeactivation ( $file, $function ) {

		register_deactivation_hook( $file, $function );
	}

	/**
	 * Add passwrod reset action
	 * @param type $function
	 * @param type $priority
	 * @param type $acceptedArgs 
	 */
	public function addXmlrpcMethods ( $function, $priority = 10, $acceptedArgs = 1 ) {

		$this->addFilter( 'xmlrpc_methods', $function, $priority, $acceptedArgs );
	}

	public function addAjaxAction ( $action, $function, $priority = 10, $acceptedArgs = 1 ) {
		$this->addAction( "wp_ajax_{$action}", $function, $priority, $acceptedArgs );
	}

	public function addPublicAjaxAction ( $action, $function, $priority = 10, $acceptedArgs = 1 ) {
		$this->addAction( "wp_ajax_nopriv_{$action}", $function, $priority, $acceptedArgs );
	}

	public function addQueryVarsFilter ( $function, $priority = 10, $acceptedArgs = 1 ) {
		$this->addFilter( 'query_vars', $function, $priority, $acceptedArgs );
	}

	public function addParseRequest ( $function, $priority = 10, $acceptedArgs = 1 ) {
		$this->addAction( 'parse_request', $function, $priority, $acceptedArgs );
	}

	public function addMetaBox ( $function, $priority = 10, $acceptedArgs = 1 ) {
		$this->addAction( 'add_meta_box', $function, $priority, $acceptedArgs );
	}

	public function addLoadPost ( $function, $priority = 10, $acceptedArgs = 1 ) {
		$this->addAction( 'load-post.php', $function, $priority, $acceptedArgs );
	}

}
