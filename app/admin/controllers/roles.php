<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
    exit;

class Roles extends MavenAdminController {

    public function __construct () {

        parent::__construct();
    }

    public function registerRoutes ( $routes ) {

        $routes[ '/maven/roles' ] = array(
            array( array( $this, 'getRoles' ), \WP_JSON_Server::READABLE ),
            array( array( $this, 'newRol' ), \WP_JSON_Server::CREATABLE | \WP_JSON_Server::ACCEPT_JSON ),
        );
        $routes[ '/maven/roles/(?P<id>\D+)' ] = array(
            array( array( $this, 'getRol' ), \WP_JSON_Server::READABLE ),
            array( array( $this, 'editRol' ), \WP_JSON_Server::EDITABLE | \WP_JSON_Server::ACCEPT_JSON ),
            array( array( $this, 'deleteRol' ), \WP_JSON_Server::DELETABLE ),
        );

        return $routes;
    }

    public function getRoles () {
        $manager = new \Maven\Security\RoleManager();

        $roles = $manager->getRoles();

        $this->getOutput()->sendApiResponse( $roles );
    }

    public function newRol ( $data ) {
        $manager = new \Maven\Security\RoleManager();

        $role = new \Maven\Core\Domain\Role();

        $role->load( $data );
        
        $role = $manager->updateRole( $role );

        $this->getOutput()->sendApiResponse( $role );
    }

    public function getRol ( $id ) {
        $manager = new \Maven\Security\RoleManager();
        $role = $manager->get( $id );

        $this->getOutput()->sendApiResponse( $role );
    }

    public function editRol ( $id, $data ) {

        $manager = new \Maven\Security\RoleManager();

        $role = new \Maven\Core\Domain\Role();

        $role->load( $data );

        $role = $manager->updateRole( $role );

        $this->getOutput()->sendApiResponse( $role );
    }

    public function deleteRol ( $id ) {
        $manager = new \Maven\Security\RoleManager();

        $manager->delete( $id );

        $this->getOutput()->sendApiResponse( new \stdClass() );
    }

    public function getView ( $view ) {
        return $view;
    }

}
