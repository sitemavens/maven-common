<?php

namespace Maven\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class TaxManager {


	public function __construct(  ) {

	}

	/**
	 * Add a tax to database
	 * @param \Maven\Core\Domain\Tax $tax
	 * @param string $key
	 * @return \Maven\Core\Domain\Tax
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function addTax( \Maven\Core\Domain\Tax $tax, $key = "" ) {

		if ( ! ($tax->getName()) )
			throw new \Maven\Exceptions\MissingParameterException( "Tax Name is required" );
		//TODO: Add other validations

		$tax->setPluginKey( $key );

		$mapper = new Mappers\TaxMapper();

		return $mapper->save( $tax );
	}

	/**
	 * Return a Tax searching by id
	 * @param int $taxId
	 * @return \Maven\Core\Domain\Tax The object will be empty (id=0), if not found
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function get( $taxId ) {

		if ( ! $taxId )
			throw new \Maven\Exceptions\MissingParameterException( "Tax id is required" );

		$mapper = new Mappers\TaxMapper();

		return $mapper->get( $taxId );
	}

	
	public function getByPlugin( $pluginKey ) {

		if ( ! $pluginKey )
			throw new \Maven\Exceptions\MissingParameterException( "Plugin Key is required" );

		$mapper = new Mappers\TaxMapper();

		return $mapper->getByPlugin( $pluginKey );
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\TaxFilter  $filter
	 * @return \Maven\Core\Domain\Tax[]
	 */
	public function getTaxes( \Maven\Core\Domain\TaxFilter $filter, $orderBy = 'name', $orderType = 'desc', $start=0, $limit=1000 ) {

		/*if ( ! $filter->getPluginKey() )
			throw new \Maven\Exceptions\MissingParameterException( "Plugin Key is required" );*/

		$mapper = new Mappers\TaxMapper();

		return $mapper->getTaxes( $filter , $orderBy, $orderType, $start, $limit);

		//if ( $filter->getNumber() )
		//	return $this->getEventsByNumber( $filter->getNumber(), $filter->getPluginKey() );
	}
	
	public function getTaxesCount( \Maven\Core\Domain\TaxFilter $filter ) {

		/*if ( ! $filter->getPluginKey() )
			throw new \Maven\Exceptions\MissingParameterException( "Plugin Key is required" );*/

		$mapper = new Mappers\TaxMapper();

		return $mapper->getTaxesCount( $filter);

		//if ( $filter->getNumber() )
		//	return $this->getEventsByNumber( $filter->getNumber(), $filter->getPluginKey() );
	}

	public function delete( $taxId ) {
		$mapper = new Mappers\TaxMapper();

		return $mapper->delete( $taxId );
	}

	/* public static function getTypes() {
	  return Mappers\PromotionMapper::getTypes();
	  }

	  public static function getSections() {
	  return Mappers\PromotionMapper::getSections();
	  } */

	public function applyTaxes( Domain\Order $order ) {

		return $this->executeTaxes( $order );
		
	}
	

	/**
	 * 
	 * @param \Maven\Core\Domain\Order $order
	 * @param boolean $apply
	 * @return boolean
	 */
	public function executeTaxes( Domain\Order $order ) {
		
			
		// First we need to remove the existings taxes in case the items has changed
		$taxes = $order->getTaxes();

		if ( $taxes ){
			foreach( $taxes as $tax ){

				$order->setTotal( $order->getTotal() - $tax->getTaxAmount() );
				$order->removeTax( $tax );

			}
		}
		
		
		//Get the taxes applicable to the order
		$country = $order->getShippingContact()->getPrimaryAddress()->getCountry();
		$state = $order->getShippingContact()->getPrimaryAddress()->getState();
		
		$filter=new Domain\TaxFilter();
		$filter->setEnabled(true);
		$filter->setCountry($country);
		$filter->setState($state);
		
		return;
		
		//TODO: Esto hay que cambiar para que los taxes sean aplicados por items, si hay 
		$filter->setPluginKey( $order->getRegistry()->getPluginKey() );
		
		$taxes = $this->getTaxes($filter);
		
		$price = $order->getSubtotal();
		
		foreach( $taxes as $tax ){
			 
			if ( $tax->isForShipping() )
				throw new \Maven\Exceptions\MavenException('Shipping taxes are not ready yet!');
			
			$taxAmount = ( ($price * $tax->getValue() ) / 100.00);

			$tax->setTaxAmount( $taxAmount );

			//Apply the discount to total 
			$order->addTax( $tax );
			

		}
		
	}

}

