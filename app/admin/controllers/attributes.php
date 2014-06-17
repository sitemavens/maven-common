<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Attributes extends \Maven\Admin\Controllers\MavenAdminController {

	public function __construct () {
		parent::__construct();
	}

    public function registerRoutes ( $routes ) {

        $routes[ '/maven/attributes' ] = array(
            array( array( $this, 'getAttributes' ), \WP_JSON_Server::READABLE ),
            array( array( $this, 'newAttribute' ), \WP_JSON_Server::CREATABLE | \WP_JSON_Server::ACCEPT_JSON ),
        );
        $routes[ '/maven/attributes/(?P<id>\d+)' ] = array(
            array( array( $this, 'getAttribute' ), \WP_JSON_Server::READABLE ),
            array( array( $this, 'editAttribute' ), \WP_JSON_Server::EDITABLE | \WP_JSON_Server::ACCEPT_JSON ),
            array( array( $this, 'deleteAttribute' ), \WP_JSON_Server::DELETABLE ),
        );

        return $routes;
    }

    public function getAttributes () {
        $manager = new \Maven\Core\AttributeManager();
        $filter = new \Maven\Core\Domain\AttributeFilter();
        $attrs = $manager->getAll($filter);
        
        $this->getOutput()->sendApiResponse( $attrs );
    }

    public function newAttribute ( $data ) {
        $manager = new \Maven\Core\AttributeManager();

        $attrs = new \Maven\Core\Domain\Attribute();

        $attrs->load( $data );
        
        $attrs = $manager->addAttribute( $attrs );

        $this->getOutput()->sendApiResponse( $attrs );
    }

    public function getAttribute ( $id ) {
        $manager = new \Maven\Core\AttributeManager();
        $attrs = $manager->get( $id );

        $this->getOutput()->sendApiResponse( $attrs );
    }

    public function editAttribute ( $id, $data ) {

        $manager = new \Maven\Core\AttributeManager();

        $attrs = new \Maven\Core\Domain\Attribute();

        $attrs->load( $data );

        $attrs = $manager->addAttribute( $attrs );

        $this->getOutput()->sendApiResponse( $attrs );
    }

    public function deleteAttribute ( $id ) {
        $manager = new \Maven\Core\AttributeManager();

        $manager->delete( $id );

        $this->getOutput()->sendApiResponse( new \stdClass() );
    }

    public function getView ( $view ) {
        return $view;
    }

}
