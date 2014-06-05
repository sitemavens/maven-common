<?php

namespace Maven\Core\UI;

class ActionStatus {
	//Solo dos estados: Redirect y Stay

	const Redirect = 0;
	const Stay = 1;

	private $status;
	
	//TODO: Is this really necesary? all the messages are now used throw the messageManager
	private $message;

	private function __construct($status = 0, $message = "") {

		$this->status = $status;
		$this->message = $message;
	}

	public static function redirect($message = "") {

		return new self(self::Redirect, $message);
	}

	public static function stay($message = "") {

		return new self(self::Stay, $message);
	}

	public function getStatus() {
		return $this->status;
	}

	public function getMessage() {
		return $this->message;
	}

}