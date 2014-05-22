<?php

namespace Maven\Core\Message;

class Message {
	
	const Successful	= 1;
	const Error		= 2;
	const Warning	= 3;
	const None		= 4;
	
	private $isError		= false;
	private $isSuccessful	= false;
	private $isWarning		= false;
	
	private $content	= "";
	private $code		= "";
	
	/**
	 * The data property, will let you save any object/information, you need to send 
	 * @var any 
	 */
	private $data = null;
	
	
	
	/**
	 *
	 * @param string $content
	 * @param const $type
	 */
	public function __construct( $content, $type, $data = null ){
		
		$this->content = $content;
		$this->data = $data;
		
		switch( $type ){
			case self::Successful: 
				$this->setIsSuccessful	( true );
				break;
			case self::Error: 
				$this->setIsError	( true );
				break;
			case self::Warning: 
				$this->setIsWarning	( true );
				break;
		}
	}
	
	public function getData() {
		return $this->data;
	}
	
	public function getContent(){
		return $this->content;
	}
	
	public function isWarning() 
	{
		return $this->isWarning;
	}

	private function setIsWarning($value) 
	{
		$this->isWarning = $value;
	}
	
    
	/**
     * @deprecated 0.4.2.1 use isSuccessful instead
     * @return boolean
     */
    public function isRegular() 
	{
		return $this->isSuccessful;
	}
    
    /**
     * 
     * @return boolean
     */
    public function isSuccessful() 
	{
		return $this->isSuccessful;
	}

    /**
     * 
     * @param boolean $value
     */
    private function setIsSuccessful( $value ) 
	{
		$this->isSuccessful = $value;
	}
    
    /**
     * @deprecated 0.4.2.1 use setIsSuccessful insted
     * @param boolean $value
     */
	private function setIsRegular( $value ) 
	{
		$this->isSuccessful = $value;
	}
	
	public function isError() 
	{
		return $this->isError;
	}

	private function setIsError($value) 
	{
		$this->isError = $value;
	}
	
	public function getCode() {
		return $this->code;
	}

	public function setCode( $code ) {
		$this->code = $code;
	}

}
