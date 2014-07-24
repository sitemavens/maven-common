<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Attributes extends \Maven\Admin\Controllers\MavenAdminController {

	public function __construct() {
		parent::__construct();
	}

	public function registerRoutes( $routes ) {

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

	public function getAttributes( $filter = array(), $page = 1 ) {
		$manager = new \Maven\Core\AttributeManager();
		$filter = new \Maven\Core\Domain\AttributeFilter();

		if ( key_exists( 'name', $filter ) && $filter[ 'name' ] ) {
			$filter->setName( $filter[ 'name' ] );
		}

		$sortBy = 'name';
		$order = 'desc';
		$perPage = 10;

		$attrs = $manager->getAll( $filter, $sortBy, $order, (($page - 1) * $perPage ), $perPage );
		$count = $manager->getCount( $filter );

		$response = array(
			"items" => $attrs,
			"totalItems" => $count
		);

		$this->getOutput()->sendApiResponse( $response );
	}

	public function newAttribute( $data ) {
		$manager = new \Maven\Core\AttributeManager();

		$attrs = new \Maven\Core\Domain\Attribute();

		$attrs->load( $data );
		$attrs = $manager->addAttribute( $attrs );

		$this->getOutput()->sendApiResponse( $attrs );
	}

	public function getAttribute( $id ) {
		try {
			$manager = new \Maven\Core\AttributeManager();
			$attrs = $manager->get( $id );

			$this->getOutput()->sendApiResponse( $attrs );
		} catch ( \Maven\Exceptions\NotFoundException $e ) {
			$this->getOutput()->sendApiError( $id, "Attribute Not Found" );
		} catch ( \Exception $e ) {
			//General exception, send general error
			$this->getOutput()->sendApiError( $id, "An error has ocurred" );
		}
	}

	public function editAttribute( $id, $data ) {
		try {
			$manager = new \Maven\Core\AttributeManager();

			$attrs = new \Maven\Core\Domain\Attribute();

			$attrs->load( $data );

			$attrs = $manager->addAttribute( $attrs );

			$this->getOutput()->sendApiSuccess( $attrs, 'Attribute saved' );
		} catch ( \Exception $e ) {
			//General exception, send general error
			$this->getOutput()->sendApiError( $data, $e->getMessage() );
		}
	}

	public function deleteAttribute( $id ) {
		$manager = new \Maven\Core\AttributeManager();

		$manager->delete( $id );

		$this->getOutput()->sendApiResponse( new \stdClass() );
	}

	public function getView( $view ) {
		return $view;
	}

}
