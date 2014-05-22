<?php

namespace Maven\Front\StepActions;


// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Redirect implements iStepAction{
	
	private $slug;
	
	/**
	 * Create a Redirection step
	 * @param string $slug Page/Post slug
	 */
	public function __construct ( $slug ) {
		$this->slug = $slug;
	}
	
	public function getSlug () {
		return $this->slug;
	}

	public function setSlug ( $slug ) {
		$this->slug = $slug;
	}

	public function doAction(){
		
		wp_redirect(  get_site_url( null, $this->slug ) );
		die();
	}
}