<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Attributes extends \Maven\Admin\Controllers\MavenAdminController {

	public function __construct () {
		parent::__construct();
	}

	public function admin_init () {
		
	}

	public function showForm () {


		$this->getOutput()->setTitle( $this->__( "Attributes" ) );

		$this->getOutput()->loadAdminView( "attributes" );
	}

	public function cancel () {
		
	}

	public function save () {
		
	}

	public function showList () {
		
	}

	public function attributeEntryPoint () {
		try {
			$event = $this->getRequest()->getProperty( 'event' );

			$manager = new \Maven\Core\AttributeManager();

			switch ( $event ) {
				case 'create':
					$data = $this->getRequest()->getProperty( 'data' );

					$attribute = new \Maven\Core\Domain\Attribute();

					$attribute->load( $data );

					$manager->addAttribute( $attribute );

					$this->getOutput()->sendData( $attribute->toArray() );

					break;

				case 'read':

					$modelId = $this->getRequest()->getProperty( 'id' );

					if ( $modelId ) {
						try {
							$attribute = $manager->get( $modelId );
							$this->getOutput()->sendData( $attribute->toArray() );
						} catch ( \Maven\Exceptions\MavenException $ex ) {
							$this->getOutput()->sendError( $ex->getMessage() );
						}
					} else {
						$data = $this->getRequest()->getProperty( 'data' );

						$filter = new \Maven\Core\Domain\AttributeFilter();

						if ( key_exists( 'name', $data ) && $data[ 'name' ] )
							$filter->setName( $data[ 'name' ] );
						
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
						$intances = $manager->getAll( $filter, $sortBy, $order, ($page * $perPage ), $perPage );
						$count = $manager->getCount( $filter );

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

					$attribute = new \Maven\Core\Domain\Attribute();
					$attribute->load($data);
					
					$attribute = $manager->addAttribute( $attribute );

					$this->getOutput()->sendData( $attribute->toArray() );

					break;

				case 'delete':
					$modelId = $this->getRequest()->getProperty( 'id' );

					$manager->delete( $modelId );

					//Empty response
					$this->getOutput()->sendData( 'deleted' );

					break;
			}
		} catch ( Exception $ex ) {
			$this->getOutput()->sendError( $ex->getMessage() );
		}
	}

}
