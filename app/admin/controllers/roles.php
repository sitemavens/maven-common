<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class Roles extends MavenAdminController {

	public function __construct() {

		parent::__construct();
	}

	function save() {
		
	}

	public function cancel() {
		
	}

	public function showForm() {
		
		$roleManager = new \Maven\Security\RoleManager();
		$roles = $roleManager->getRoles();
		
		$this->addJSONData( 'roles', $roles );
		
		$this->getOutput()->setTitle( "Roles" );

		$this->getOutput()->loadAdminView( "roles" );
		
	}

	public function showList() {
		
	}
	
	
	public function entryPoint() {
		
		$event = $this->getRequest()->getProperty( "event" );
		
		try {
			$event = $this->getRequest()->getProperty( 'event' );

			$manager = new \Maven\Security\RoleManager();

			switch ( $event ) {
				case 'create':
				case 'update':
					$data = $this->getRequest()->getProperty( 'data' );
					
					$role = new \Maven\Core\Domain\Role();
					$role->load($data);
					
					$role = $manager->updateRole( $role );

					$this->getOutput()->sendData( $role->toArray() );


					break;

				case 'read':

					$modelId = $this->getRequest()->getProperty( 'id' );

					if ( $modelId ) {
						try {
							$role = $manager->get($modelId);
							$this->getOutput()->sendData( $role->toArray() );
						} catch ( \Maven\Exceptions\MavenException $ex ) {
							$this->getOutput()->sendError( $ex->getMessage() );
						}
					} 
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



			