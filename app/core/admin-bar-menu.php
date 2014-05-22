<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class AdminBarMenu {

	private $name;
	private $subMenues = array();
	private $key = "";
	private $slug = "";

	public function __construct ( $name, $slug ) {
		add_action( 'admin_bar_menu', array( $this, "registerMenu" ), 400 );

		$this->name = $name;
		$this->slug = $slug;
		$this->key = sanitize_key( $name );
	}

	private function addMenu () {

		global $wp_admin_bar;
		
		$siteUrl = site_url( $this->slug );

		$wp_admin_bar->add_menu( array(
			'id' => $this->key,
			'meta' => array(),
			'title' => $this->name,
			'href' => $siteUrl ) );
	}

	public function addSubMenu ( $name, $slug ) {
		
		$this->subMenues[] = array('name'=>$name, 'slug'=>$slug);
		
	}

	public function registerMenu () {
		
		if ( !is_super_admin() || !is_admin_bar_showing() ) {
			return;
		}

		$this->addMenu();

		global $wp_admin_bar;
		foreach( $this->subMenues as $subMenu ){
			$wp_admin_bar->add_menu( array(
				'parent' => $this->key,
				'id' => sanitize_key( $subMenu['name'] ),
				'title' => $subMenu['name'],
				'href' => $subMenu['slug']
			) );
		} 
		
		
		
	}

}

