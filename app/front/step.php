<?php

namespace Maven\Front;

use \Maven\Front\Actions\Consts;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Step {

	private $action;
	private $items;
	private $requestKey;
	private $inputKey;
	private $promotions;
	private $shippingCountry;
	private $promotionCode;
	private $notes;

	/**
	 * It will save the action result
	 * @var \Maven\Core\Message\Message
	 */
	private $actionResult;

	/**
	 *
	 * @var \Maven\Front\StepActions\iStepAction 
	 */
	private $onComplete;

	/**
	 *
	 * @var \Maven\Front\StepActions\iStepAction 
	 */
	private $onError;

	/**
	 *
	 * @var \Maven\Front\Thing; 
	 */
	private $thing;

	/**
	 * it will host the data that need to be collected in the step
	 * @var Array 
	 */
	private $dataCollection = array();
	private $extraFields;

	/**
	 * 
	 */
	public function __construct ( $data = false ) {

		$this->onComplete = new StepActions\DoNothing();
		$this->onError = new StepActions\DoNothing();

		$this->thing = $this->readThingData( $data );

		if ( isset( $data[ Consts::Step ] ) ) {

			$default = array(
				Consts::Action => "",
				Consts::OnComplete => array( Consts::Redirect => "" ),
				Consts::OnError => array( Consts::Redirect => "", Consts::SendNotification => "" ),
				Consts::Collect => array(),
				Consts::PromotionCode => "",
				Consts::Notes => "",
				Consts::Items => array()
			);

			$step = $data[ Consts::Step ];

			$step = wp_parse_args( $step, $default );

			$this->setAction( $step[ Consts::Action ] );
			
			

			if ( $step[ Consts::OnComplete ][ Consts::Redirect ] ) {
				$this->setOnComplete( new StepActions\Redirect( $step[ Consts::OnComplete ][ Consts::Redirect ] ) );
			}

			if ( $step[ Consts::OnError ][ Consts::SendNotification ] ) {
				$this->setOnError( new StepActions\SendNotification( $this->thing ) );
			}


			if ( $step[ Consts::Collect ] ) {
				if ( !is_array( $step[ Consts::Collect ] ) ) {
					$this->addDataToCollect( $step[ Consts::Collect ], $step[ Consts::Collect ] );
				} else {
					$step[ Consts::Collect ] = array_combine( $step[ Consts::Collect ], $step[ Consts::Collect ] );
					$this->setDataCollection( $step[ Consts::Collect ] );
				}
			}

			if ( $step[ Consts::PromotionCode ] ) {
				$this->setPromotionCode( $step[ Consts::PromotionCode ] );
			}
			
			if ( $step[ Consts::Notes ] ) {
				$this->setNotes( $step[ Consts::Notes ] );
			}

			if ( $step[ Consts::Items ] ) {
				$this->setItems( $step[ Consts::Items ] );
			}


			//coupon' => '', 'applyCoupon'
		}
	}

	/**
	 * 
	 * @return \Maven\Front\Thing
	 * @throws \Maven\Exceptions\InvalidObjectTypeException
	 * @throws \Maven\Exceptions\RequiredException
	 */
	private function readThingData ( $data ) {

		if ( ! $data || ! isset( $data[Consts::Thing] ) ) {
			return null;
		}
		
		$data = $data[Consts::Thing];
		
		$defaults = array(
			Consts::Id => '',
			Consts::Name => '',
			Consts::Quantity => '',
			Consts::PluginKey => '',
			Consts::Price => '',
			Consts::Variation => array(),
			Consts::Attribute => array()
		);

		$defaultVariation = array(
			Consts::Id => '',
			Consts::Quantity => '',
			Consts::Name => '',
			Consts::Price => '',
			Consts::OptionId => ''
		);
		
		
		$defaultAttribute = array(
			Consts::Id => '',
			Consts::Name => '',
			Consts::Price => ''
		);
		

		$data = wp_parse_args( $data, $defaults );

//		if ( ! $data [ Consts::Id ] ) {
//			throw new \Maven\Exceptions\RequiredException( "Thing id is required" );
//		}
//
//		if ( !$data [ Consts::PluginKey ] ) {
//			throw new \Maven\Exceptions\RequiredException( "Plugin Key id is required" );
//		}

		$thing = new \Maven\Front\Thing( $data [ Consts::PluginKey ] );
		$thing->setId( $data[ Consts::Id ] );
		$thing->setQuantity( $data[ Consts::Quantity ] );
		$thing->setPrice( $data[ Consts::Price ] );
		$thing->setName( $data[ Consts::Name ] );


		foreach ( $data[ Consts::Variation ] as $key => $value ) {

			$value = wp_parse_args( $value, $defaultVariation );
			$variation = new \Maven\Front\ThingVariation();
			$variation->setId( $key );
			$variation->setQuantity( $value[ Consts::Quantity ] );
			$variation->setPrice( $value[ Consts::Price ] );
			$variation->setName( $value[ Consts::Name ] );
			$variation->setOptionId( $value[ Consts::OptionId ] );
			
			if ( ! $variation->getId() ){
				$variation->setId( $value[ Consts::Id ] );
			}
			$thing->addVariation( $variation );
			
			
		}
		
		foreach ( $data[ Consts::Attribute ] as $key => $value ) {

			if ( $key ){
				
				$value = wp_parse_args( $value, $defaultAttribute );
				$attribute = new \Maven\Front\ThingAttribute();
				$attribute->setId( $key );
				$attribute->setPrice( $value[ Consts::Price ] );
				$attribute->setName( $value[ Consts::Name ] );

				$thing->addAttribute( $attribute );
		
			}
		}

		return $thing;
	}

	/**
	 * 
	 * @param string $groupKey
	 * @param int $id
	 * @return \Maven\Front\Thing
	 */
	public function newThing ( $groupKey, $id ) {

		$this->thing = new Thing( $groupKey, $id );
		return $this->thing;
	}

	public function getAction () {
		return $this->action;
	}

	public function setAction ( $action ) {
		$this->action = $action;
	}

	public function setOnComplete ( \Maven\Front\StepActions\iStepAction $stepAction ) {
		$this->onComplete = $stepAction;
	}

	/**
	 * 
	 * @return \Maven\Front\StepActions\IStepAction
	 */
	public function getOnComplete () {
		return $this->onComplete;
	}

	/**
	 * 
	 * @return \Maven\Front\StepActions\iStepAction
	 */
	public function getOnError () {
		return $this->onError;
	}

	/**
	 * 
	 * @param \Maven\Front\StepActions\iStepAction $onError
	 */
	public function setOnError ( \Maven\Front\StepActions\iStepAction $onError ) {
		$this->onError = $onError;
	}

	/**
	 * 
	 * @return \Maven\Front\Thing
	 */
	public function getThing () {
		return $this->thing;
	}

	/**
	 * 
	 * @param \Maven\Front\Thing $thing
	 */
	public function setThing ( \Maven\Front\Thing $thing ) {
		$this->thing = $thing;
	}

	public function getDataCollection () {
		return $this->dataCollection;
	}

	public function hasToCollect ( $key = "" ) {
		if ( !$key ) {
			return count( $this->getDataCollection() );
		}

		return array_key_exists( $key, $this->getDataCollection() );
	}

	public function setDataCollection ( $dataCollection ) {
		$this->dataCollection = $dataCollection;
	}

	/**
	 * Add data keys to collect
	 * @param string $dataKey See: Maven\Front\DataToCollect for options
	 * @param string $name The input field name
	 */
	public function addDataToCollect ( $dataKey, $name = "" ) {
		$this->dataCollection[ $dataKey ] = array( "key" => $dataKey, "fieldName" => $name );
	}

	public function getDataToCollectFieldName ( $dataKey ) {

		if ( $this->hasToCollect( $dataKey ) ) {
			return $this->dataCollection[ $dataKey ][ 'fieldName' ];
		}

		return false;
	}

	protected function getRequestKey () {
		return $this->requestKey;
	}

	protected function getInputKey () {
		return $this->inputKey;
	}

	public function getExtraFields () {
		return $this->extraFields;
	}

	public function setExtraFields ( $extraFields ) {
		$this->extraFields = $extraFields;
	}

	public function getPromotions () {
		return $this->promotions;
	}

	public function setPromotions ( $promotions ) {
		$this->promotions = $promotions;
	}

	public function getShippingCountry () {
		return $this->shippingCountry;
	}

	public function setShippingCountry ( $shippingCountry ) {
		$this->shippingCountry = $shippingCountry;
	}

	public function getItems () {
		return $this->items;
	}

	public function setItems ( $items ) {
		$this->items = $items;
	}

	public function hasExtraFields () {
		return !\Maven\Core\Utils::isEmpty( $this->extraFields );
	}

	public function hasPromotions () {
		return !\Maven\Core\Utils::isEmpty( $this->promotions );
	}

	public function hasItems () {
		return !\Maven\Core\Utils::isEmpty( $this->items );
	}

	public function getPromotionCode () {
		return $this->promotionCode;
	}

	public function setPromotionCode ( $promotionCode ) {
		$this->promotionCode = $promotionCode;
	}

	/**
	 * Get action result
	 * @return \Maven\Core\Message\Message
	 */
	public function getActionResult () {
		return $this->actionResult;
	}

	/**
	 * Set action result
	 * @param \Maven\Core\Message\Message $actionResult
	 */
	public function setActionResult ( \Maven\Core\Message\Message $actionResult ) {
		$this->actionResult = $actionResult;
	}
	
	public function getNotes () {
		return $this->notes;
	}

	public function setNotes ( $notes ) {
		$this->notes = $notes;
	}



}
