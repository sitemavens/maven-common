<?php

namespace Maven\Core\Interfaces;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;


interface iView{
	public function getView( $view );
}