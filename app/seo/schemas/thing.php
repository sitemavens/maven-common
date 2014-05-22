<?php

namespace Maven\Seo\Schemas;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class Thing {
	
	
	/**
	 * An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. This is a relationship between something and a class that the thing is in. In RDFa syntax, it is better to use the native RDFa syntax - the 'typeof' attribute - for multiple types. Schema.org tools may have only weaker understanding of extra types, in particular those defined externally.
	 * @var string 
	 */
	private $additionalType;
	
	/**
	 * A short description of the item.
	 * @var string 
	 */
	private $description;
	
	/**
	 * URL of an image of the item.
	 * @var string 
	 */
	private $image;
	
	/**
	 * The name of the item.
	 * @var string 
	 */
	private $name;
	
	/**
	 * URL of the item.
	 * @var string 
	 */
	private $url;
	
	
		
	public function __construct() {
		
	}
	
	/**
	 * An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. This is a relationship between something and a class that the thing is in. In RDFa syntax, it is better to use the native RDFa syntax - the 'typeof' attribute - for multiple types. Schema.org tools may have only weaker understanding of extra types, in particular those defined externally.
	 * @return string
	 */
	public function getAdditionalType() {
		return $this->additionalType;
	}

	
	public function setAdditionalType( $additionalType ) {
		$this->additionalType = $additionalType;
	}

	/**
	 * A short description of the item.
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	public function setDescription( $description ) {
		$this->description = $description;
	}

	/**
	 * URL of an image of the item.
	 * @return string
	 */
	public function getImage() {
		return $this->image;
	}

	public function setImage( $image ) {
		$this->image = $image;
	}

	/**
	 * The name of the item.
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}

	/**
	 * URL of the item.
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	public function setUrl( $url ) {
		$this->url = $url;
	}

	
}