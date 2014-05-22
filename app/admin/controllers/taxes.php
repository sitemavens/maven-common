<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class Taxes extends \Maven\Admin\Controllers\MavenAdminController {

	public function __construct() {
		parent::__construct();
	}

	public function admin_init() {
		
	}

	public function showForm() {
		$countryManager= new \Maven\Core\CountryManager();
		$countries = $countryManager->getAll();
		$col = array( );
		foreach ( $countries as $code => $data ) {
			$col[ $code ] = array( 'name' => $data[ 'name' ], 'value' => $code );
		}

		$this->addJSONData( 'cachedCountries', $col );

		$this->getOutput()->setTitle( $this->__( "Taxes" ) );

		$this->getOutput()->loadAdminView( "taxes" );
	}

	public function cancel() {
		
	}

	public function save() {
		
	}

	public function showList() {
		
	}

	public function taxEntryPoint() {
		try {
			$event = $this->getRequest()->getProperty( 'event' );

			$taxManager = new \Maven\Core\TaxManager( );
			switch ( $event ) {
				case 'create':
					$data = $this->getRequest()->getProperty( 'data' );

					$tax = new \Maven\Core\Domain\Tax();

					$tax->load( $data );

					$tax = $taxManager->addTax( $tax );

					$this->getOutput()->sendData( $tax->toArray() );

					break;

				case 'read':
					$modelId = $this->getRequest()->getProperty( 'id' );

					if ( $modelId ) {
						try {
							$tax = $taxManager->get( $modelId );
							$this->getOutput()->sendData( $tax->toArray() );
						} catch ( \Maven\Exceptions\MavenException $ex ) {
							$this->getOutput()->sendError( $ex->getMessage() );
						}
					} else {
						$data = $this->getRequest()->getProperty( 'data' );

						$filter = new \Maven\Core\Domain\TaxFilter();
						$filter->setAll( true );

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
						$intances = $taxManager->getTaxes( $filter, $sortBy, $order, ($page * $perPage ), $perPage );
						$count = $taxManager->getTaxesCount( $filter );

						$response = array( );
						foreach ( $intances as $row ) {
							$response[ ] = $row->toArray();
						}

						$out[ ] = array( 'total_entries' => intval( $count ) );
						$out[ ] = $response;

						$this->getOutput()->sendData( $out );
					}
					break;

				case 'update':
					$data = $this->getRequest()->getProperty( 'data' );

					$tax = new \Maven\Core\Domain\Tax();

					$tax->load( $data );

					$tax = $taxManager->addTax( $tax );

					$this->getOutput()->sendData( $tax->toArray() );

					break;

				case 'delete':
					$modelId = $this->getRequest()->getProperty( 'id' );

					$taxManager->delete( $modelId );

					//Empty response
					$this->getOutput()->sendData( 'deleted' );
					break;
			}
		} catch ( Exception $ex ) {
			$this->getOutput()->sendError( $ex->getMessage() );
		}
	}

}