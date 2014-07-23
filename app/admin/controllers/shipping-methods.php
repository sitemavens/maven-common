<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class ShippingMethods extends \Maven\Admin\Controllers\MavenAdminController {

	public function __construct() {
		parent::__construct();
	}

	public function registerRoutes( $routes ) {

		$routes[ '/maven/shipping-methods' ] = array(
			array( array( $this, 'getShippingMethods' ), \WP_JSON_Server::READABLE ),
			array( array( $this, 'newItem' ), \WP_JSON_Server::CREATABLE | \WP_JSON_Server::ACCEPT_JSON ),
		);
		$routes[ '/maven/shipping-methods/(?P<id>\d+)' ] = array(
			array( array( $this, 'get' ), \WP_JSON_Server::READABLE ),
			array( array( $this, 'edit' ), \WP_JSON_Server::EDITABLE | \WP_JSON_Server::ACCEPT_JSON ),
			array( array( $this, 'delete' ), \WP_JSON_Server::DELETABLE ),
		);

		return $routes;
	}

	public function getShippingMethods( $filter = array(), $page = 1 ) {
		$manager = new \Maven\Core\ShippingMethodManager();
		$filter = new \Maven\Core\Domain\ShippingMethodFilter();

		if ( key_exists( 'name', $filter ) && $filter[ 'name' ] ) {
			$filter->setName( $filter[ 'name' ] );
		}

		$sortBy = 'name';
		$order = 'desc';
		$perPage = 10;

		$items = $manager->getAll( $filter, $sortBy, $order, (($page - 1) * $perPage ), $perPage );
		$count = $manager->getCount( $filter );

		$response = array(
			"items" => $items,
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

	public function get( $id ) {
		try {
			$manager = new \Maven\Core\ShippingMethodManager();
			$item = $manager->get( $id );

			$this->getOutput()->sendApiResponse( $item );
		} catch ( \Maven\Exceptions\NotFoundException $e ) {
			//Specific exception
			$this->getOutput()->sendApiError( $id, "Shipping Method Not found" );
		} catch ( \Exception $e ) {
			//General exception, send general error
			$this->getOutput()->sendApiError( $id, "An error has ocurred" );
		}
	}

	public function edit( $id, $data ) {
		try {
			$manager = new \Maven\Core\ShippingMethodManager();

			$item = new \Maven\Core\Domain\ShippingMethod();

			$item->load( $data );

			$item = $manager->addShippingMethod( $item );

			$this->getOutput()->sendApiResponse( $item, 'Shipping Method saved' );
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
