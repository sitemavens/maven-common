<?php

namespace Maven\Admin\Controllers;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Orders extends \Maven\Admin\Controllers\MavenAdminController implements \Maven\Core\Interfaces\iView {

	public function __construct () {
		parent::__construct();
	}

	public function registerRoutes ( $routes ) {

		$routes[ '/maven/orders' ] = array(
			array( array( $this, 'getOrders' ), \WP_JSON_Server::READABLE ),
				//array( array( $this, 'newOrder' ), \WP_JSON_Server::CREATABLE | \WP_JSON_Server::ACCEPT_JSON ),
		);
		$routes[ '/maven/orders/(?P<id>\d+)' ] = array(
			array( array( $this, 'getOrder' ), \WP_JSON_Server::READABLE ),
			array( array( $this, 'editOrder' ), \WP_JSON_Server::EDITABLE | \WP_JSON_Server::ACCEPT_JSON ),
			array( array( $this, 'deleteOrder' ), \WP_JSON_Server::DELETABLE ),
		);

		return $routes;
	}

	public function getOrders ( $filter = array(), $page = 1 ) {
		$manager = new \Maven\Core\OrderManager();
		$orderFilter = new \Maven\Core\Domain\OrderFilter();
		if ( key_exists( 'number', $_GET ) && $_GET[ 'number' ] ) {
			$orderFilter->setNumber( $_GET[ 'number' ] );
		}

		if ( key_exists( 'start', $_GET ) && $_GET[ 'start' ] ) {
			$startDate = $_GET[ 'start' ];
			$fixed = date( 'Y-m-d', strtotime( $startDate ) );
			$orderFilter->setOrderDateFrom( $fixed );
		}
		

		if ( key_exists( 'end', $_GET ) && $_GET[ 'end' ] ) {
			$endDate = $_GET[ 'end' ];
			$fixed = date( 'Y-m-d', strtotime( $endDate ) );
			$orderFilter->setOrderDateTo( $fixed );
		}

		if ( key_exists( 'status', $_GET ) && $_GET[ 'status' ] ) {
			$orderFilter->setStatusID( $_GET[ 'status' ] );
		}

		$perPage = 10; //$data[ 'per_page' ];
		$sortBy = 'order_date';
		/* if ( $data && key_exists( 'sort_by', $data ) ) {
		  $sortBy = \Maven\Core\Utils::unCamelize( $data[ 'sort_by' ], '_' );
		  } */

		$order = 'desc';
		/* if ( $data && key_exists( 'order', $data ) ) {
		  $order = $data[ 'order' ];
		  } */

		$orders = $manager->getOrders( $orderFilter, $sortBy, $order, (($page - 1) * $perPage ), $perPage );
		$count = $manager->getOrdersCount( $orderFilter );
		$ordersTotal = $manager->getOrdersTotal( $orders );
		/* foreach ( $orders as $row ) {
		  $temp = $row->toArray();

		  $temp[ 'number' ] = intval( $temp[ 'number' ] );
		  $temp[ 'total' ] = floatval( $temp[ 'total' ] );

		  $response[] = $temp;
		  }

		  $out[] = array( 'total_entries' => intval( $count ) );
		  $out[] = $response; */

		$response = array(
			"items" => $orders,
			"totalItems" => $count,
			"ordersTotal" => $ordersTotal
		);
		$this->getOutput()->sendApiResponse( $response );
	}

	public function newOrder ( $data ) {
		return new \WP_Error( 'maven_common_order_not_implemented_method', __( 'Not implemented.' ), array( 'status' => 500 ) );
	}

	public function getOrder ( $id ) {
		$manager = new \Maven\Core\OrderManager();

		$order = $manager->get( $id );

		$this->getOutput()->sendApiResponse( $order );
	}

	public function editOrder ( $id, $data ) {

		$manager = new \Maven\Core\OrderManager();

		$order = new \Maven\Core\Domain\Order();

		$order->load( $data );
		$addStatus = false;
		if ( key_exists( 'sendNotice', $data ) && $data[ 'sendNotice' ] ) {

			$status = $manager->sendShipmentNotice( $order );

			if ( $status ) {
				$order->setStatus( $status );
				$addStatus = true;
			} else {
				return new \WP_Error( 'maven_common_order_shipment', __( 'Error sending shipment notice.' ), array( 'status' => 500 ) );
			}
		}

		$order = $manager->addOrder( $order, $addStatus );

		//get the order again, to catch all update 
		//(this is wrong, but status change are not being updated on the orders
		$order = $manager->get( $id );

		$this->getOutput()->sendApiResponse( $order );
	}

	public function deleteOrder ( $id ) {
		$manager = new \Maven\Core\OrderManager();

		$manager->delete( $id );

		$this->getOutput()->sendApiResponse( new \stdClass() );
	}

	public function showForm () {

		$statusManager = new \Maven\Core\OrderStatusManager();
		$statuses = $statusManager->getAll();
		//$orderApi->getStatuses();
		//var_dump($statuses);
		$temp = array( array( 'label' => '', 'value' => '' ) );
		foreach ( $statuses as $name => $object ) {
			$temp[] = array( 'label' => $object->getName(), 'value' => $name );
		}

		$this->addJSONData( 'cachedStatuses', $temp );

		$this->addJSONData( 'completedStatusId', $statusManager->getCompletedStatus()->getId() );

		$this->addJSONData( 'shippedStatusId', $statusManager->getShippedStatus()->getId() );

		$this->getOutput()->setTitle( $this->__( "Orders" ) );

		$this->getOutput()->loadAdminView( "orders" );
	}

	public function cancel () {
		
	}

	public function save () {
		
	}

	public function showList () {
		
	}

	public function orderStatsEntryPoint () {
		try {
			$event = $this->getRequest()->getProperty( 'event' );

			//$registry = \Maven\Settings\MavenRegistry::instance();
			$orderManager = new \Maven\Core\OrderManager();
			$statusManager = new \Maven\Core\OrderStatusManager();
			switch ( $event ) {
				case 'create':
					$this->getOutput()->sendError( 'Not implemented' );
					break;

				case 'read':
					//always return the same object
					$filter = new \Maven\Core\Domain\OrderFilter();
					$orders = $orderManager->getOrders( $filter );
					$response[ 'count' ] = count( $orders );

					$completedFilter = new \Maven\Core\Domain\OrderFilter();
					$completedFilter->setStatusID( $statusManager->getCompletedStatus()->getName() );
					$completedOrders = $orderManager->getOrders( $completedFilter );
					$response[ 'completedCount' ] = count( $completedOrders );
					$sum = 0;
					foreach ( $completedOrders as $order ) {
						$sum+= $order->getTotal();
					}

					$response[ 'total' ] = $sum;

					$this->getOutput()->sendData( $response );

					break;

				case 'update':
					$this->getOutput()->sendError( 'Not implemented' );
					break;

				case 'delete':
					$this->getOutput()->sendError( 'Not implemented' );
					break;
			}
		} catch ( Exception $ex ) {
			$this->getOutput()->sendError( $ex->getMessage() );
		}
	}

	public function orderEntryPoint () {
		try {
			$event = $this->getRequest()->getProperty( 'event' );
			//$presenterManager = new \MavenEvents\Core\PresenterManager();
			//$registry = \MavenEvents\Settings\EventsRegistry::instance();
			$orderManager = new \Maven\Core\OrderManager( );
			switch ( $event ) {
				case 'create':
					$this->getOutput()->sendError( 'Not implemented' );
					break;

				case 'read':
					$modelId = $this->getRequest()->getProperty( 'id' );

					if ( $modelId ) {
						try {
							$order = $orderManager->get( $modelId );

							$this->getOutput()->sendData( $order->toArray() );
						} catch ( \Maven\Exceptions\MavenException $ex ) {
							$this->getOutput()->sendError( $ex->getMessage() );
						}
					} else {
						$data = $this->getRequest()->getProperty( 'data' );

						$filter = new \Maven\Core\Domain\OrderFilter();

						if ( key_exists( 'number', $data ) && $data[ 'number' ] ) {
							$filter->setNumber( $data[ 'number' ] );
						}

						if ( key_exists( 'start', $data ) && $data[ 'start' ] ) {
							$filter->setOrderDateFrom( $data[ 'start' ] );
						}

						if ( key_exists( 'end', $data ) && $data[ 'end' ] ) {
							$filter->setOrderDateTo( $data[ 'end' ] );
						}

						if ( key_exists( 'status', $data ) && $data[ 'status' ] ) {
							$filter->setStatusID( $data[ 'status' ] );
						}

						$page = $data[ 'page' ] - 1; //We use 0-based pages
						$perPage = $data[ 'per_page' ];
						$sortBy = 'order_date';
						if ( $data && key_exists( 'sort_by', $data ) ) {
							$sortBy = \Maven\Core\Utils::unCamelize( $data[ 'sort_by' ], '_' );
						}

						$order = '';
						if ( $data && key_exists( 'order', $data ) ) {
							$order = $data[ 'order' ];
						}

						$orders = $orderManager->getOrders( $filter, $sortBy, $order, ($page * $perPage ), $perPage );
						$count = $orderManager->getOrdersCount( $filter );

						$response = array();
						foreach ( $orders as $row ) {
							$temp = $row->toArray();

							$temp[ 'number' ] = intval( $temp[ 'number' ] );
							$temp[ 'total' ] = floatval( $temp[ 'total' ] );

							$response[] = $temp;
						}

						$out[] = array( 'total_entries' => intval( $count ) );
						$out[] = $response;

						$this->getOutput()->sendData( $out );
					}
					break;

				case 'update':
					$data = $this->getRequest()->getProperty( 'data' );

					$order = new \Maven\Core\Domain\Order();

					$order->load( $data );
					$addStatus = false;
					if ( key_exists( 'sendNotice', $data ) && $data[ 'sendNotice' ] === 'true' ) {

						$status = $orderManager->sendShipmentNotice( $order );

						if ( $status ) {
							$order->setStatus( $status );
							$addStatus = true;
						} else {
							$this->getOutput()->sendError( 'Error sending shipment notice' );
							return;
						}
					}

					$order = $orderManager->addOrder( $order, $addStatus );

					$this->getOutput()->sendData( $order->toArray() );
					break;

				case 'delete':
					$modelId = $this->getRequest()->getProperty( 'id' );

					$orderManager->delete( $modelId );

					//Empty response
					$this->getOutput()->sendData( 'deleted' );
					break;
			}
		} catch ( Exception $ex ) {
			$this->getOutput()->sendError( $ex->getMessage() );
		}
	}

	public function getView ( $view ) {
		switch ( $view ) {
			case "orders":
				$orderApi = new \Maven\Core\OrdersApi();
				$statuses = $orderApi->getStatuses();
				$this->addJSONData( "cachedStatuses", $statuses );
				return $this->getOutput()->getAdminView( "orders/{$view}" );
		}
		return $view;
	}

}
