<?php

namespace Maven\Gateways;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

interface iExternalProcess{
	
	function executeExternalCall();
	
	function isExternalTransaction();
	
	function doExternalPost();
	
	function getNotifyUrl();
	
	function setNotifyUrl( $notifyUrl );
	
	function getCancelUrl();
	
	function setCancelUrl( $cancelUrl );
	
	function getReturnUrl();
	
	function setReturnUrl( $returnUrl );
	
}
