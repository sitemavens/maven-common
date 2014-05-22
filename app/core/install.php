<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 *
 * @author Emiliano Jankowski
 */
abstract class Install {
	
	/**
	 * Run when plugin is activated
	 */
	public abstract function on_activate();
	
	
	/**
	 * Run when plugin is deactivated
	 */
	public abstract function on_deactivate() ;
	
	/**
	 * Run when plugin is unistalled
	 * @return NULL|true return null if it is not registered as unistall file or true if it runs
	 */
	public abstract function on_uninstall();
	
}