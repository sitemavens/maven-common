<?php

namespace Maven\Core\Message;

class MessageManager {
	/**
	 * This is used as parameter name in url
	 */

	const message_slug = "message";

	/**
	 *
	 * @var \Maven\Core\Message\MessageKeyGenerator
	 */
	private $keyGenerator;

	/**
	 *
	 * @var type array
	 */
	private $messages = array( );

	/**
	 * Constructor
	 * 
	 * @param \Maven\Core\Message\MessageKeyGenerator $keyGenerator
	 */
	public function __construct( \Maven\Core\Message\MessageKeyGenerator $keyGenerator ) {
		//Set the key generator
		$this->keyGenerator = $keyGenerator;
	}

	/**
	 * Set the KeyGenerator.
	 *
	 * @param \Maven\Core\Message\MessageKeyGenerator $keyGenerator 
	 */
	public function setKeyGenerator( \Maven\Core\Message\MessageKeyGenerator $keyGenerator ) {

		$this->keyGenerator = $keyGenerator;
	}

	/**
	 * Create a new Error Message
	 * 
	 * @param string $message
	 * @return \Maven\Core\Message\Message
	 */
	public static function createErrorMessage( $message ) {

		return self::createMessageObj( $message, Message::Error );
	}

	/**
	 * Create a new Warning Message
	 * 
	 * @param string $message
	 * @return \Maven\Core\Message\Message
	 */
	public static function createWarningMessage( $message ) {

		return self::createMessageObj( $message, Message::Warning );
	}

	
	
	
	/**
	 * Create a new Success Message
	 * @param string $message
	 * @return \Maven\Core\Message\Message
	 */
	public static function createSuccessfulMessage( $message, $data = null ) {
		return self::createMessageObj( $message, Message::Successful, $data );
	}
	
	/**
	 * Create an empty message. It's usful for initializing 
	 * @return \Maven\Core\Message\Message
	 */
	public static function createEmptyMessage(  ) {
		return self::createMessageObj( '', Message::None, null );
	}

	/**
	 * Create a new Error Message and add it to the collection
	 * 
	 * @param string $message
	 */
	public function addErrorMessage( $message ) {

		$this->messages[ ] = self::createMessageObj( $message, Message::Error );
	}

	/**
	 * Create a new Warning Message and add it to the collection
	 * 
	 * @param string $message
	 */
	public function addWarningMessage( $message ) {

		$this->messages[ ] = self::createMessageObj( $message, Message::Warning );
	}

	/**
	 * Create a new Regular Message and add it to the collection
	 * 
	 * @param string $message
	 */
	public function addRegularMessage( $message ) {

		$this->addSuccessfulMessage($message);
	}
	
	public function addSuccessfulMessage( $message ) {

		$this->messages[ ] = self::createMessageObj( $message, Message::Successful );
	}

	/**
	 * Create a new message
	 * 
	 * @param string $message
	 * @param const $type
	 * @return \Maven\Core\Message\Message
	 */
	private static function createMessageObj( $message, $type, $data = null ) {

		$messageObj = new \Maven\Core\Message\Message( $message, $type, $data );

		return $messageObj;
	}

	/**
	 * Add an existing message to the collection.
	 * 
	 * @param \Maven\Core\Message\Message $message
	 */
	public function addMessageObj( \Maven\Core\Message\Message $message ) {
		$this->messages[ ] = $message;
	}

	/**
	 * Save the message collection in the transient. In case of success its
	 * return the key used to store the data.
	 * If it fails, it returns false.
	 * 
	 * @param type $expiration
	 * @return mixed
	 */
	public function saveMessages( $expiration = 30 ) {
		$key = $this->keyGenerator->getKey();

		if ( \set_transient( $key, $this->messages, $expiration ) ) {
			return $key;
		} else {
			return false;
		}
	}

	/**
	 * Load the saved messages in the transient, into the messages collection.
	 * Once the data its loaded, it delete the transient data.
	 * 
	 * If the transient does not exist or does not have a value, then the return value
	 * will be false.
	 * 
	 * @param mixed $key
	 */
	public function loadMessages( $key ) {

		if ( $this->messages = get_transient( $key ) ) {
			delete_transient( $key );
		} else {
			$this->messages = array( );
		}
	}

	/**
	 * Return an array of <b>Regular</b> messages from the message collection.
	 * 
	 * @return array
	 */
	public function getRegularMessages() {
		return $this->getSuccessfulMessages();
	}
	
	public function getSuccessfulMessages() {
		return array_filter( $this->messages, function ( \Maven\Core\Message\Message $message ) {
					return $message->isSuccessful();
				} );
	}

	/**
	 * Return an array of <b>Warning</b> messages from the message collection.
	 * 
	 * @return array
	 */
	public function getWarningMessages() {
		return array_filter( $this->messages, function ( \Maven\Core\Message\Message $message ) {
					return $message->isWarning();
				} );
	}

	/**
	 * Return an array of <b>Error</b> messages from the message collection.
	 * 
	 * @return array
	 */
	public function getErrorMessages() {
		return array_filter( $this->messages, function ( \Maven\Core\Message\Message $message ) {
					return $message->isError();
				} );
	}

}
