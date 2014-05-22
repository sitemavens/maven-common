<?php

namespace Maven\Front\StepActions;


// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class DoNothing implements iStepAction{
	
	public function doAction(){
		
		return;
	}
}