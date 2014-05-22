<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class TaxesApi {

	private $registry;

	public function __construct( \Maven\Settings\Registry $registry ) {

		$this->registry = $registry;
	}

	public function getAllTaxes() {

		$manager = new TaxManager( $this->registry );

		$filter = new Domain\TaxFilter();

		$filter->setPluginKey( $this->registry->getPluginKey() );

		return $manager->getTaxes( $filter );
	}

	public function getTaxes( \Maven\Core\Domain\TaxFilter $filter, $orderBy = 'name', $orderType = 'desc', $start=0, $limit=1000) {

		$manager = new TaxManager( $this->registry );

		$filter->setPluginKey( $this->registry->getPluginKey() );

		return $manager->getTaxes( $filter ,$orderBy, $orderType, $start, $limit);
	}
	
	public function getTaxesCount( \Maven\Core\Domain\TaxFilter $filter ) {

		$manager = new TaxManager( $this->registry );

		$filter->setPluginKey( $this->registry->getPluginKey() );

		return $manager->getTaxesCount( $filter );
	}

	/**
	 * 
	 * @return \Maven\Core\Domain\Tax
	 */
	public function newTax() {

		return new \Maven\Core\Domain\Tax();
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Tax $tax
	 * @return type
	 */
	public function addTax( \Maven\Core\Domain\Tax $tax ) {

		$manager = new TaxManager( $this->registry );

		return $manager->addTax( $tax );
	}

	/**
	 * 
	 * @param int/object $taxId
	 */
	public function getTax( $taxId ) {

		if ( ! $taxId )
			throw new \Maven\Exceptions\MissingParameterException( 'Tax ID is required.' );

		$manager = new \Maven\Core\TaxManager( $this->registry );

		$tax = $manager->get( $taxId );

		return $tax;
	}

	/**
	 * 
	 * @param int/object $taxId
	 */
	public function delete( $taxId ) {

		$manager = new \Maven\Core\TaxManager( $this->registry );

		return $manager->delete( $taxId );
	}


	public function applyTaxes( Domain\Order $order ) {
		
		$manager = new \Maven\Core\TaxManager( $this->registry );

		return $manager->applyTaxes( $order );
	}

	
	public function removeTaxes( Domain\Order $order ) {
		
		$manager = new \Maven\Core\TaxManager( $this->registry );
		
		return $manager->removeTaxes( $order );
		
	}
}