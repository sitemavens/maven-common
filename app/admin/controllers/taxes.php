<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Taxes extends \Maven\Admin\Controllers\MavenAdminController implements \Maven\Core\Interfaces\iView {

	public function __construct () {
		parent::__construct();
	}

	public function registerRoutes ( $routes ) {

		$routes[ '/maven/taxes' ] = array(
			array( array( $this, 'getTaxes' ), \WP_JSON_Server::READABLE ),
			array( array( $this, 'newTax' ), \WP_JSON_Server::CREATABLE | \WP_JSON_Server::ACCEPT_JSON ),
		);
		$routes[ '/maven/taxes/(?P<id>\d+)' ] = array(
			array( array( $this, 'getTax' ), \WP_JSON_Server::READABLE ),
			array( array( $this, 'editTax' ), \WP_JSON_Server::EDITABLE | \WP_JSON_Server::ACCEPT_JSON ),
			array( array( $this, 'deleteTax' ), \WP_JSON_Server::DELETABLE ),
		);

		return $routes;
	}

	public function getTaxes () {
		$manager = new \Maven\Core\TaxManager();

		$filter = new \Maven\Core\Domain\TaxFilter();
		$filter->setAll( TRUE );

		$taxes = $manager->getTaxes( $filter );

		$this->getOutput()->sendApiResponse( $taxes );
	}

	public function newTax ( $data ) {
		$manager = new \Maven\Core\TaxManager();

		$tax = new \Maven\Core\Domain\Tax();

		$tax->load( $data );

		$tax = $manager->addTax( $tax );

		$this->getOutput()->sendApiResponse( $tax );
	}

	public function getTax ( $id ) {
		try {
			$manager = new \Maven\Core\TaxManager();
			$tax = $manager->get( $id );

			$this->getOutput()->sendApiResponse( $tax );
		} catch ( \Maven\Exceptions\NotFoundException $e ) {
			$this->getOutput()->sendApiError( $id, "Tax Not Found" );
		} catch ( \Exception $e ) {
			//General exception, send general error
			$this->getOutput()->sendApiError( $id, "An error has ocurred" );
		}
	}

	public function editTax ( $id, $data ) {
		try {
			$manager = new \Maven\Core\TaxManager();

			$tax = new \Maven\Core\Domain\Tax();

			$tax->load( $data );

			$tax = $manager->addTax( $tax );

			$this->getOutput()->sendApiSuccess( $tax, 'Tax saved' );
		} catch ( \Exception $e ) {
			//General exception, send general error
			$this->getOutput()->sendApiError( $data, $e->getMessage() );
		}
	}

	public function deleteTax ( $id ) {
		$manager = new \Maven\Core\TaxManager();

		$manager->delete( $id );

		$this->getOutput()->sendApiSuccess( new \stdClass(), 'Tax Deleted' );
	}

	public function getView ( $view ) {
		switch ( $view ) {
			case "taxes-edit":
				$countries = \Maven\Core\CountriesApi::getAllCountries();
				$this->addJSONData( "cachedCountries", $countries );
				return $this->getOutput()->getAdminView( "taxes/{$view}" );
		}
		return $view;
	}

}
