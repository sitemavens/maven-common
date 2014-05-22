<?php

namespace Maven\Core\Db;

abstract class WpOptionMapper extends Mapper{

	private $optionKey;
	
	function __construct( $option_key ) {

		$this->optionKey = $option_key;
	}
	
	/**
	 *
	 * @param string $value
	 * @return bool False if value was not updated and true if value was updated. 
	 */
	public function updateOption( $value ){
		
		return update_option( $this->optionKey, $value );
		
	}
	
	/**
	 *
	 * @param mixed $default Optional. Default value to return if the option does not exist.
	 * @return mixed Value set for the option.
	 */
	public function getOption( $default = false ){
		return get_option( $this->optionKey, $default );
				
	}
	
	/**
	 * 
	 * @return bool True, if option is successfully deleted. False on failure. 
	 */
	public function deleteOption( ){
		
		return delete_option($this->optionKey);
	}
	
	protected function setOptionKey( $key ){
		$this->optionKey = $key;
	}

}