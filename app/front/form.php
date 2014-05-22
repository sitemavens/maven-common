<?php

namespace Maven\Front;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Form {

	public function writeField( \Maven\Front\FormField $formField ){
		echo $formField->getHtml();
	}


}

