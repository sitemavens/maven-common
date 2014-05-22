<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Promotions extends \Maven\Admin\Controllers\MavenAdminController {

	public function __construct() {
		parent::__construct();
	}

	public function admin_init() {
		
	}

	public function showForm() {
		//Promotion Types
		$promotionTypes = \Maven\Core\PromotionManager::getTypes();

		$cachedPromotions = array( array( 'label' => '', 'value' => '', 'symbol' => '' ) );
		foreach ( $promotionTypes as $value => $type ) {

			$cachedPromotions[] = array( 'label' => $type[ 'name' ], 'symbol' => $type[ 'symbol' ], 'value' => $value );
		}

		$this->addJSONData( 'cachedPromotionTypes', $cachedPromotions );

		//Promotion Sections
		$promotionSections = \Maven\Core\PromotionManager::getSections();

		$cachedSections = array( array( 'label' => '', 'value' => '' ) );
		foreach ( $promotionSections as $value => $section ) {

			$cachedSections[] = array( 'label' => $section[ 'name' ], 'value' => $value );
		}

		$this->addJSONData( 'cachedPromotionSections', $cachedSections );

		$this->getOutput()->setTitle( $this->__( "Promotions" ) );

		$this->getOutput()->loadAdminView( "promotions" );
	}

	public function cancel() {
		
	}

	public function save() {
		
	}

	public function showList() {
		
	}

	public function exportEntryPoint() {
		try {
			$event = $this->getRequest()->getProperty( 'event' );
			switch ( $event ) {
				case 'create':
				case 'update':
					$promotions = array();

					$promotionManager = new \Maven\Core\PromotionManager();
					$data = $this->getRequest()->getProperty( 'data' );

					$filter = new \Maven\Core\Domain\PromotionFilter();
					if ( $data && key_exists( 'code', $data ) && $data[ 'code' ] ) {
						$filter->setCode( $data[ 'code' ] );
					}
					if ( $data && key_exists( 'date', $data ) && $data[ 'date' ] ) {
						$filter->setDate( $data[ 'date' ] );
					}

					$sortBy = '';
					if ( $data && key_exists( 'sort_by', $data ) )
						$sortBy = \Maven\Core\Utils::unCamelize( $data[ 'sort_by' ], '_' );

					$order = '';
					if ( $data && key_exists( 'order', $data ) )
						$order = $data[ 'order' ];
					/* if ( $data && key_exists( 'event', $data ) && $data[ 'event' ] ) {
					  $attendees = $attendeeManager->getEventAttendees( $data[ 'event' ] );
					  } else {
					  $attendees = $attendeeManager->getAll();
					  } */
					$promotions = $promotionManager->getPromotions( $filter, $sortBy, $order, 0, 10000 );

					// 'browser' tells the library to stream the data directly to the browser.
					// other options are 'file' or 'string'
					// 'test.xls' is the filename that the browser will use when attempting to 
					// save the download
					$exporter = new \Maven\Libs\Export\ExportDataExcel( 'browser', 'promotions.xls' );
					//$exporter->filename = ;

					$exporter->initialize(); // starts streaming data to web browser
					// pass addRow() an array and it converts it to Excel XML format and sends 
					// it to the browser
					$exporter->addHeadingRow( array(
					    "Status",
					    "Name",
					    "Code",
					    "Section",
					    "Amount",
					    "From",
					    "To",
					    "Uses",
					    "Limit of Use"
					) );

					foreach ( $promotions as $promotion ) {
						$status = 'Disabled';
						if ( $promotion->isEnabled() ) {
							$status = 'Enabled';
						}
						$operand='';
						switch ($promotion->getType()){
							case 'amount':$operand='$';
								break;
							case 'percentage':$operand='%';
								break;
						}
						
						$exporter->addRow( array(
						    $status,
						    $promotion->getName(),
						    $promotion->getCode(),
						    $promotion->getSection(),
						    $promotion->getValue().$operand,
						    $promotion->getFrom(),
						    $promotion->getTo(),
						    $promotion->getUses(),
						    $promotion->getLimitOfUse()
						) );
					}

					$exporter->finalize(); // writes the footer, flushes remaining data to browser.

					break;
			}
		} catch ( \Exception $ex ) {
			$this->getOutput()->sendError( $ex->getMessage() );
		}
	}

	public function multiPromotionEntryPoint() {
		try {
			$event = $this->getRequest()->getProperty( 'event' );

			$promotionManager = new \Maven\Core\PromotionManager( );
			switch ( $event ) {
				case 'create':
					$data = $this->getRequest()->getProperty( 'data' );

					$quantity = NULL;
					if ( is_array( $data ) && key_exists( 'quantity', $data ) && $data[ 'quantity' ] ) {
						$quantity = $data[ 'quantity' ];
					}

					$promotion = new \Maven\Core\Domain\Promotion();

					$promotion->load( $data );

					$response = $promotionManager->addMultiplePromotions( $promotion, $quantity );

					if ( $response ) {
						$this->getOutput()->sendData( 'created' );
					} else {
						$this->getOutput()->sendError( 'Too much errors.' );
					}

					break;
			}
		} catch ( \Exception $ex ) {
			$this->getOutput()->sendError( $ex->getMessage() );
		}
	}

	public function promotionEntryPoint() {
		try {
			$event = $this->getRequest()->getProperty( 'event' );

			$promotionManager = new \Maven\Core\PromotionManager( );
			switch ( $event ) {
				case 'create':
					$data = $this->getRequest()->getProperty( 'data' );

					$promotion = new \Maven\Core\Domain\Promotion();

					$promotion->load( $data );

					$promotion = $promotionManager->addPromotion( $promotion );

					$this->getOutput()->sendData( $promotion->toArray() );

					break;

				case 'read':
					$modelId = $this->getRequest()->getProperty( 'id' );

					if ( $modelId ) {
						try {
							$promotion = $promotionManager->get( $modelId );
							$this->getOutput()->sendData( $promotion->toArray() );
						} catch ( \Maven\Exceptions\MavenException $ex ) {
							$this->getOutput()->sendError( $ex->getMessage() );
						}
					} else {
						$data = $this->getRequest()->getProperty( 'data' );

						$filter = new \Maven\Core\Domain\PromotionFilter();
						if ( is_array( $data ) && key_exists( 'code', $data ) && $data[ 'code' ] )
							$filter->setCode( $data[ 'code' ] );

						$top = $this->getRequest()->getProperty( 'top' );
						$skip = $this->getRequest()->getProperty( 'skip' );

						$page = $data[ 'page' ] - 1; //We use 0-based pages
						$perPage = $data[ 'per_page' ];
						$sortBy = '';
						if ( $data && key_exists( 'sort_by', $data ) )
							$sortBy = \Maven\Core\Utils::unCamelize( $data[ 'sort_by' ], '_' );

						$order = '';
						if ( $data && key_exists( 'order', $data ) )
							$order = $data[ 'order' ];
						//var_dump($data);
						$intances = $promotionManager->getPromotions( $filter, $sortBy, $order, ($page * $perPage ), $perPage );
						$count = $promotionManager->getPromotionsCount( $filter );

						$response = array();
						foreach ( $intances as $row ) {
							$response[] = $row->toArray();
						}

						$out[] = array( 'total_entries' => intval( $count ) );
						$out[] = $response;

						$this->getOutput()->sendData( $out );
					}
					break;

				case 'update':
					$data = $this->getRequest()->getProperty( 'data' );

					$promotion = new \Maven\Core\Domain\Promotion;

					$promotion->load( $data );

					$promotion = $promotionManager->addPromotion( $promotion );

					$this->getOutput()->sendData( $promotion->toArray() );

					break;

				case 'delete':
					$modelId = $this->getRequest()->getProperty( 'id' );

					$promotionManager->delete( $modelId );

					//Empty response
					$this->getOutput()->sendData( 'deleted' );
					break;
			}
		} catch ( \Exception $ex ) {
			$this->getOutput()->sendError( $ex->getMessage() );
		}
	}

}
