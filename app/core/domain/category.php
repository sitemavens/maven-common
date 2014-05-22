<?php

namespace Maven\Core\Domain;

class Category extends \Maven\Core\DomainObject {

	protected $name;
	protected $description;
	protected $termId;
	protected $termTaxonomyId;
	protected $slug;
	
	/**
	 *  
	 * @var \Maven\Core\Domain\Category[]
	 */
	protected $childCategories;
	
	public function __construct( $id = false ) {
		
		parent::__construct( $id );
		
		$rules = array(
			
			'name'				=> \Maven\Core\SanitizationRule::Text,
			'description'		=> \Maven\Core\SanitizationRule::TextWithHtml,
			'termId'			=> \Maven\Core\SanitizationRule::Integer,
			'termTaxonomyId'	=> \Maven\Core\SanitizationRule::Integer,
			'slug'				=> \Maven\Core\SanitizationRule::Slug
			
		);
		
		$this->setSanitizationRules( $rules );
		
	}
	
	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription( $description ) {
		$this->description = $description;
	}

	public function getTermId() {
		return $this->termId;
	}

	public function setTermId( $termId ) {
		$this->termId = $termId;
	}

	public function getTermTaxonomyId() {
		return $this->termTaxonomyId;
	}

	public function setTermTaxonomyId( $termTaxonomyId ) {
		$this->termTaxonomyId = $termTaxonomyId;
	}

	public function getSlug() {
		return $this->slug;
	}

	public function setSlug( $slug ) {
		$this->slug = $slug;
	}
	
	/**
	 * 
	 * @return \Maven\Core\Domain\Category[]
	 */
	public function getChildCategories() {
		return $this->childCategories;
	}

	public function setChildCategories(  $childCategories ) {
		$this->childCategories = $childCategories;
	}




}
