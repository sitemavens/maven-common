<?php

namespace Maven\Session;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


use Maven\Settings\OptionType,
	Maven\Settings\Option;


class SessionNative extends SessionBase{
	
	public function __construct() {
		
		$this->setName('Native');
		
		
		$defaultOptions = array(
			new Option(
					"sessionName", "Session Name", '', '', OptionType::Input
			),
			new Option(
					"cookieName", "Cookie Name", '', '', OptionType::Input
			,
			new Option(
					"cookiePrefix", "Cookie Prefix", '', '', OptionType::Input
			),
			new Option(
					"cookieSecure", "Cookie Secure", false, '', OptionType::Input
			),
			new Option(
					"cookieHttpOnly", "Cookie Http Only", false, '', OptionType::Input
			),
			new Option(
					"expiration", "Expiration", '', '', OptionType::Input
			),
			new Option(
					"cookiePath", "Cookie Path", '/', '', OptionType::Input
			),
			new Option(
					"cookieDomain", "Cookie Domain", '', '', OptionType::Input
			),
			new Option(
					"expireOnClose", "Cookie Expire on Close", true, '', OptionType::Input
			))
			
		);

		$this->addSettings( $defaultOptions );
		
		
		// Set session name, if specified
		if ( $this->getSetting( 'sessionName' ) )
		{
			$name = $this->getSetting( 'cookieName' );
			
			if ($this->getSetting( 'cookiePrefix' ) )
				$name = $this->getSetting( 'cookiePrefix' ).$name;
			
			session_name( $name );
		}
		
		
		// Set expiration, path, and domain
		$expire = 7200;
		$secure = (bool) $this->getSetting( 'cookieSecure' );
		$httpOnly = (bool) $this->getSetting( 'cookieHttpOnly' );

		if ( $this->getSetting('expiration') !== FALSE )
		{
			// Default to 2 years if expiration is "0"
			$expire = ( $this->getSetting('expiration') == 0) ? (60*60*24*365*2) : $this->getSetting('expiration');
		}

		$expire = $this->getSetting( 'expireOnClose') ? 0 : $expire;
		
		$cookiePath = $this->getSetting( 'cookiePath' )?$this->getSetting( 'cookiePath' ):'/';
		
		
		if ( ! session_id() ){
		
			session_name("maven_sesion");
			
			session_set_cookie_params( $expire, $cookiePath, $this->getSetting( 'cookieDomain' ), $secure, $httpOnly);
			
			session_start();
			
		}
		
		
		// Make session ID available
		$_SESSION['session_id'] = session_id();
		
		
//var_dump($_SESSION);
	}
	
	public function getSesssionId(){
		return $_SESSION['session_id'];
	}
	
	
	/**
	 * Destroy the current session
	 *
	 * @return	void
	 */
	public function destroy()
	{
		// Cleanup session
		$_SESSION = array();
		$name = session_name();
		if (isset($_COOKIE[$name]))
		{
			// Clear session cookie
			$params = session_get_cookie_params();
			setcookie($name, '', time() - 42000, $this->getSetting( 'cookiePath'), $this->getSetting( 'cookieDomain' ), $this->getSetting( 'cookieSecure' ), $this->getSetting( 'cookieHttpOnly' ) );
			unset($_COOKIE[$name]);
		}
		session_destroy();
	}
	
	public function addData( $key, $value ){
		
		$_SESSION[$key] = $value;
		
	}
	
	public function removeData( $key ){
		
		if ( isset( $_SESSION[$key] ) )
			unset($_SESSION[$key]);
		
	}
	
	public function getData( $key ){
		
		if ( isset( $_SESSION[ $key ] ) )
			 return $_SESSION[ $key ];
		
		return null;
	}
	
}