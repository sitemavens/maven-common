<?php


namespace Maven\Core\Message;

class UserMessageKeyGenerator extends \Maven\Core\Message\MessageKeyGenerator{
	
	public function getKey() {

		$userId = get_current_user_id();
		$key = "mvn-{$userId}-".$userId;
		
		return $key;
		
	}

	
}

?>
