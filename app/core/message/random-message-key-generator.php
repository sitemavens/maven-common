<?php

namespace Maven\Core\Message;

class RandomMessageKeyGenerator extends \Maven\Core\Message\MessageKeyGenerator {

	/**
	 * The lenght of the key generated, in bytes
	 * 
	 * @var \integer
	 */
	private $lenght;

	public function __construct($lenght = 16) {
		$this->lenght = $lenght;
	}

	public function getKey() {

		$key = bin2hex(openssl_random_pseudo_bytes($this->lenght));

		return $key;
	}

}

?>
