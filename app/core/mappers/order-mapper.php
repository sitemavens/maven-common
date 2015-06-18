<?php

namespace Maven\Core\Mappers;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class OrderMapper extends \Maven\Core\Db\WordpressMapper {

	private $orderItemTable = "mvn_orders_items";

	public function __construct () {

		parent::__construct( "mvn_orders" );
	}

	public function getAll ( $orderBy = "order_id" ) {
		$instances = array();
		$results = $this->getResults( $orderBy );

		foreach ( $results as $row ) {
			$instance = new \Maven\Core\Domain\Order();
			$this->fillObject( $instance, $row );
			$instances[] = $instance;
		}

		return $instances;
	}

	public function getRevenue ( $status, $from = false, $to = false ) {

		$from = '';

		if ( !$from ) {
			$fromDate = new \Maven\Core\MavenDateTime();
			$fromDate->subFromInterval( 'P14D' );
			$from = $fromDate->mySqlFormatDate();
		} else {
			$fromDate = new \Maven\Core\MavenDateTime( $from );
			$from = $fromDate->mySqlFormatDate();
		}


		if ( !$to ) {
			$today = $to;
			$today = new \Maven\Core\MavenDateTime();
			$to = $today->mySqlFormatDate();
		} else {
			$today = new \Maven\Core\MavenDateTime( $to );
			$to = $today->mySqlFormatDate();
		}

		$toTime = ' 23:59:59';
		$fromTime = ' 00:00:00';

		$where = " and order_date >= '{$from}{$fromTime}' and order_date <= '{$to}{$toTime}'";

		//TODO: Validate if it is a valid status
		if ( $status === "total" ) {
			$query = $this->prepare( "SELECT SUM(total) FROM {$this->tableName} WHERE ( status_id = 'completed' OR status_id='received') {$where}" );
		} else {
			$query = $this->prepare( "SELECT SUM(total) FROM {$this->tableName} WHERE  status_id = %s {$where}", $status );
		}

		return $this->getVar( $query );
	}

	public function getCount ( $status, $from = false, $to = false ) {

		$from = '';

		if ( !$from ) {
			$fromDate = new \Maven\Core\MavenDateTime();
			$fromDate->subFromInterval( 'P14D' );
			$from = $fromDate->mySqlFormatDate();
		} else {
			$fromDate = new \Maven\Core\MavenDateTime( $from );
			$from = $fromDate->mySqlFormatDate();
		}


		if ( !$to ) {
			$today = $to;
			$today = new \Maven\Core\MavenDateTime();
			$to = $today->mySqlFormatDate();
		} else {
			$today = new \Maven\Core\MavenDateTime( $to );
			$to = $today->mySqlFormatDate();
		}


		$toTime = ' 23:59:59';
		$fromTime = ' 00:00:00';


		$where = $this->prepare( " and order_date >= %s and order_date <= %s", $from . $fromTime, $to . $toTime );

		//TODO: Validate if it is a valid status
		if ( $status === "total" ) {
			$query = "SELECT COUNT('E') FROM {$this->tableName} WHERE  ( status_id = 'completed' OR status_id='received') {$where}";
		} else {
			$query = $this->prepare( "SELECT COUNT('E') FROM {$this->tableName} WHERE  status_id = %s {$where}", $status );
		}

		return $this->getVar( $query );
	}

	public function getOrderLastUpdate ( $orderId ) {

		$query = "select last_update from {$this->tableName} where id=%d";

		$query = $this->prepare( $query, array( $orderId ) );

		return $this->getVar( $query );
	}

	/**
	 * Return an Order object
	 * @param int $id
	 * @return \Maven\Core\Domain\Order
	 */
	public function get ( $id ) {

		$order = $this->getOnlyOrder( $id );

		$items = $this->getOrderItems( $order->getId() );

		$order->setItems( $items );

		return $order;
	}

	public function getOrderItems ( $orderId ) {

		$items = array();

		//Get items
		$itemsRows = $this->getResultsBy( 'order_id', $orderId, 'id', 'asc', '%s', $this->orderItemTable );

		if ( $itemsRows ) {

			foreach ( $itemsRows as $itemRow ) {
				//TODO: Check if passing the plugin key on creation is correct
				$item = new \Maven\Core\Domain\OrderItem( $itemRow->plugin_key );
				$this->fillObject( $item, $itemRow );

				$items[] = $item;
			}
		}

		return $items;
	}

	/**
	 * Return the last pending order
	 * @param int $userId
	 * @return \Maven\Core\Domain\Order
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function getLastPendingOrder ( $userId ) {

		$order = new \Maven\Core\Domain\Order();

		if ( !$userId ) {
			throw new \Maven\Exceptions\MissingParameterException( 'User Id: is required' );
		}

		$query = $this->prepare( "SELECT * FROM mvn_orders WHERE  ( status_id <> 'completed' and status_id <> 'shipped' ) and user_id = %d order by order_date DESC limit 1", $userId );

		$row = $this->getQueryRow( $query );

		if ( !$row ) {
			return $order;
		}

		$this->fillObject( $order, $row );

		$items = $this->getOrderItems( $order->getId() );

		$order->setItems( $items );

		if ( $order->getStatusId() ) {
			$orderStatusMapper = new OrderStatusMapper();
			$order->setStatus( $orderStatusMapper->get( $order->getStatusId() ) );
		}

		return $order;
	}

	/**
	 * Check if order id exist in the database
	 * 
	 * @param int $orderId
	 * @return boolean
	 */
	public function orderExist ( $orderId ) {
		$row = $this->getRowById( ( int ) $orderId );

		if ( !$row ) {
			return false;
		}

		return true;
	}

	private function getOnlyOrder ( $id ) {

		$order = new \Maven\Core\Domain\Order();

		if ( !$id ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );
		}

		$row = $this->getRowById( ( int ) $id );

		if ( !$row ) {
			throw new \Maven\Exceptions\NotFoundException();
		}


		$this->fillObject( $order, $row );

		//Set Status
		if ( $row->status_id ) {
			$statusMapper = new OrderStatusMapper();

			$order->setStatus( $statusMapper->get( $row->status_id ) );
		} else {
			//TODO: Maybe we should get a default status, or a 'empty' status
		}

		$contactMapper = new ContactMapper();
		//update contact photo
		if ( $order->getContactId() ) {
			$contact = $contactMapper->get( $order->getContactId() );

			$order->getContact()->setProfileImage( $contact->getProfileImage() );
		}
		if ( $order->getBillingContactId() ) {
			$billingContact = $contactMapper->get( $order->getBillingContactId() );

			$order->getBillingContact()->setProfileImage( $billingContact->getProfileImage() );
		}
		if ( $order->getShippingContactId() ) {
			$shipingContact = $contactMapper->get( $order->getShippingContactId() );

			$order->getShippingContact()->setProfileImage( $shipingContact->getProfileImage() );
		}

		return $order;
	}

	private function addItems ( \Maven\Core\Domain\Order $order ) {

		$items = $order->getItems();

		$existingId = array();

		if ( $items ) {
			foreach ( $items as $item ) {
				//TODO: Move this validation to manager (on add item)
				if ( !$item->getPluginKey() ) {
					throw new \Maven\Exceptions\RequiredException( "Plugin key is required: " . $item->getName() );
				}

				$data = array(
					'name' => $item->getName(),
					'quantity' => $item->getQuantity(),
					'price' => $item->getPrice(),
					'order_id' => $order->getId(),
					'thing_id' => $item->getThingId(),
					'sku' => $item->getSku(),
					'plugin_key' => $item->getPluginKey(),
					'thing_variation_id' => $item->getThingVariationId(),
					'attributes' => serialize( $item->getAttributes() )
				);

				$format = array(
					'%s', //name
					'%d', //quantity
					'%f', //price
					'%d', //order_id
					'%d', //id
					'%s', //sku
					'%s', //plugin_key
					'%d', //thing_variation_id
					'%s' //attributes
				);

				if ( !$item->getId() ) {
					$insertedItemId = $this->insert( $data, $format, $this->orderItemTable );
					$item->setId( $insertedItemId );
				} else {
					$this->updateById( $item->getId(), $data, $format, $this->orderItemTable );
				}

//				$query = $this->prepare( "INSERT INTO {$this->orderItemTable} (name,quantity,price,order_id, thing_id,sku, plugin_key,thing_variation_id) "
//						. "VALUES (%s,	%d,		%f,		%d,		%d,		 %s,	%s,		 %d)", $data );
//
//
//				$dataUpdate = $this->prepare( "ON DUPLICATE KEY UPDATE name=%s, quantity=%d , price=%f , sku=%s, order_id=%d , thing_id=%d, plugin_key=%s, thing_variation_id=%d;", $data );
//
//				$query .= $dataUpdate;
//				$insertedItemId = $this->executeQuery( $query );
//				
//				if ( ! $item->getId() ) {
//					$id = $this->insert( $data, $format, $this->orderItemTable );
//					$item->setId( $id );
//				}
//				else
//					$this->updateById( $item->getId(), $data, $format, $this->orderItemTable );

				$existingId[] = $item->getId();
			}
		}

		if ( count( $existingId ) == 0 ) {
			$query = $this->prepare( "DELETE FROM {$this->orderItemTable} WHERE order_id = %d", $order->getId() );
			return;
		}

		$items = implode( ',', $existingId );

		//Delete the removed items.
		$query = $this->prepare( "DELETE FROM {$this->orderItemTable} WHERE id NOT IN ({$items}) AND order_id = %d", $order->getId() );

		$this->executeQuery( $query );
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Order $order
	 * @return \Maven\Core\Domain\Order
	 */
	public function save ( \Maven\Core\Domain\Order $order ) {

		$order->sanitize();

		$creditCard = '';

		//Check if the order has Credit Card
		if ( $order->hasCreditCard() ) {

			// We clone the CC to remove the "sensitive" data.
			$creditCard = clone( $order->getCreditCard() );

			// Remove the security code
			$creditCard->setSecurityCode( '' );

			// Save just the last 4 digits
			$creditCard->setNumber( $creditCard->getLast4Digits() );

			$creditCard = serialize( $creditCard );
		}


		$data = array(
			'description' => $order->getDescription(),
			'order_date' => $order->getOrderDate(),
			'subtotal' => $order->getSubtotal(),
			'total' => $order->getTotal(),
			'shipping_method' => serialize( $order->getShippingMethod() ),
			'shipping_amount' => $order->getShippingAmount(),
			'discount_amount' => $order->getDiscountAmount(),
			'plugin_key' => $order->getPluginId(),
			'contact_id' => $order->getContactId(),
			'contact' => serialize( $order->getContact() ),
			'billing_contact_id' => $order->getBillingContactId(),
			'billing_contact' => serialize( $order->getBillingContact() ),
			'shipping_contact_id' => $order->getShippingContactId(),
			'shipping_contact' => serialize( $order->getShippingContact() ),
			'extra_fields' => serialize( $order->getExtraFields() ),
			'status_id' => $order->getStatusId() ? $order->getStatusId() : $order->getStatus()->getId(),
			'promotions' => serialize( $order->getPromotions() ),
			'credit_card' => $creditCard,
			'transaction_id' => $order->getTransactionId(),
			'user' => '',
			'user_id' => '',
			'last_update' => \Maven\Core\MavenDateTime::getWPCurrentDateTime(),
			'shipping_carrier' => $order->getShippingCarrier(),
			'shipping_tracking_code' => $order->getShippingTrackingCode(),
			'shipping_tracking_url' => $order->getShippingTrackingUrl()
		);

		$format = array(
			'%s', //description
			'%s', //order_date
			'%f', //subtotal
			'%f', //total
			'%s', //shipping_method
			'%f', //shipping_amount
			'%f', //discount_amount
			'%s', //plugin_key
			'%s', //contact_id
			'%s', //contact
			'%d', //contact_billing_id
			'%s', //contact_billing
			'%d', //contact_shipping_id
			'%s', //contact_shipping
			'%s', //extra_fields
			'%s', //status_id
			'%s', //promotions
			'%s', //credit_card
			'%s', //transaction_id
			'%s', // user
			'%d', // user_id
			'%s', //last_update
			'%s', //shipping_carrier
			'%s', //shipping_tracking_code
			'%s' //shipping_tracking_url
		);


		if ( $order->getUser() && $order->getUser()->getEmail() ) {
			$data['user'] = serialize( $order->getUser() );
			$data['user_id'] = $order->getUser()->getId();
		}

		if ( !$order->getId() ) {

//			$query = "INSERT INTO {$this->tableName}
//							(
//								description,
//								order_date,
//								subtotal,
//								total,
//								shipping_method,
//								shipping_amount,
//								discount_amount,
//								plugin_key,
//								contact_id,
//								contact,
//								billing_contact_id,
//								billing_contact,
//								shipping_contact_id,
//								shipping_contact,
//								extra_fields,
//								status_id,
//								promotions,
//								credit_card,
//								transaction_id,
//								user, 
//								user_id
//							)
//							SELECT
//								%s, /* description */
//								%s, /* order_date */
//								%f, /* subtotal */
//								%f, /* total */
//								%s, /* shipping_method */
//								%f, /* shipping_amount */
//								%f, /* discount_amount */
//								%s, /* plugin_key */
//								%d, /* contact_id */
//								%s, /* contact */
//								%d, /* billing_contact_id */
//								%s, /* billing_contact */
//								%d, /* shipping_contact_id */
//								%s, /* shipping_contact */
//								%s, /* extra_fields */
//								%s, /* status_id */
//								%s, /* promotions */
//								%s, /* credit_card */
//								%s, /* transaction_id */
//								%s, /* user */
//								%d /* user_id */
//							 from {$this->tableName}";
			//$query = $this->prepare( $query, $data );

			$id = $this->insert( $data, $format );

//			// We need to get the new number 
//			$number = $this->getVar( $this->prepare( "SELECT number FROM  {$this->tableName} WHERE id= %d", $id ) );

			$order->setId( $id );

//			$order->setNumber( $number );
		} else {

			$this->updateById( $order->getId(), $data, $format );
		}

		//Update items
		$this->addItems( $order );

		return $order;
	}

	/**
	 * Increase the order number by 1
	 * @param int $id
	 * @return int The new order number
	 */
	public function updateOrderNumber ( $id ) {


		$query = $this->prepare( "UPDATE {$this->tableName}  SET number = ( SELECT x.newNumber
					FROM (

					SELECT (
					IFNULL( MAX( number ) , 0 ) ) +1 AS newNumber
					FROM {$this->tableName} 
					) AS x
					)
					WHERE id = %d", $id );
		$this->executeQuery( $query );

		// We need to get the new number 
		$number = $this->getVar( $this->prepare( "SELECT number FROM  {$this->tableName} WHERE id= %d", $id ) );

		return $number;
	}

	public function getOrders ( \Maven\Core\Domain\OrderFilter $filter, $orderBy = 'id', $orderType = 'desc', $start = 0, $limit = 1000 ) {
		$profileTable = ProfileMapper::getTableName();
		$where = '';
		$values = array();
		//first value is plugin key
		//$values[] = $filter->getPluginKey();

		$number = $filter->getNumber();
		if ( $number ) {
			$values[] = $number;
			$where.=" AND number =%d";
		}

		$customer = $filter->getCustomer();
		if ( $customer ) {
			$values[] = '%'.$customer.'%';
			$where.=" AND CONCAT(pro.first_name, ' ', pro.last_name) LIKE %s";
		}

		$statusId = $filter->getStatusID();
		if ( $statusId ) {
			if ( is_array( $statusId ) ) {
				$in = "";
				foreach ( $statusId as $status ) {
					if ( strlen( $in ) == 0 ) {
						$in = "%s";
					} else {
						$in.=" ,%s";
					}

					$values[] = $status;
				}
				$where.=" AND status_id IN ( {$in} )";
			} else {
				$values[] = $statusId;
				$where.=" AND status_id = %s";
			}
		}

		$orderDateFrom = $filter->getOrderDateFrom();
		if ( $orderDateFrom ) {
			$values[] = $orderDateFrom;
			$where.=" AND order_date >= %s";
		}

		$orderDateTo = $filter->getOrderDateTo();
		if ( $orderDateTo ) {
			$values[] = $orderDateTo;
			$where.=" AND order_date <= %s";
		}

		$userId = $filter->getUserID();
		if ( $userId ) {
			$values[] = $userId;
			$where.=" AND user_id = %s";
		}

		if ( !$orderBy )
			$orderBy = 'id';


		$query = "select {$this->tableName}.*
					from {$this->tableName} 
					INNER JOIN {$profileTable} as pro ON pro.id = contact_id
					where 1=1 
					{$where} order by {$orderBy} {$orderType}
					LIMIT %d , %d;";

		//other values
		//$values[ ] = $orderBy;
		//$values[ ] = $orderType;
		$values[] = $start;
		$values[] = $limit;
		//$query = $this->prepare( $query, $filter->getPluginKey(), $orderBy, $orderType, $start, $limit );
		$query = $this->prepare( $query, $values );
		$results = $this->getQuery( $query );
		$statusMapper = new OrderStatusMapper();
		$instances = array();
		foreach ( $results as $row ) {
			$instance = new \Maven\Core\Domain\Order();
			$this->fillObject( $instance, $row );
			$items = $this->getOrderItems( $instance->getId() );

			$instance->setItems( $items );
			//Set Status
			if ( $instance->getStatusId() ) {
				$instance->setStatus( $statusMapper->get( $instance->getStatusId() ) );
			} else {
				//TODO: Maybe we should get a default status, or a 'empty' status
			}

			$instances[] = $instance;
		}

		return $instances;
	}

	public function getProfileOrders ( $profileId ) {

		$results = $this->getResultsBy( 'contact_id', $profileId );
		$statusMapper = new OrderStatusMapper();

		$instances = array();
		foreach ( $results as $row ) {
			$instance = new \Maven\Core\Domain\Order();
			$this->fillObject( $instance, $row );

			$items = $this->getOrderItems( $instance->getId() );

			$instance->setItems( $items );

			//Set Status
			if ( $instance->getStatusId() ) {
				$instance->setStatus( $statusMapper->get( $instance->getStatusId() ) );
			} else {
				//TODO: Maybe we should get a default status, or a 'empty' status
			}

			$instances[] = $instance;
		}

		return $instances;
	}

	public function getOrdersCount ( \Maven\Core\Domain\OrderFilter $filter ) {

		$where = '';
		$values = array();
		//first value is plugin key
		//$values[] = array();

		$number = $filter->getNumber();
		if ( $number ) {
			$values[] = $number;
			$where.=" AND number =%d";
		}

		$statusId = $filter->getStatusID();
		if ( $statusId ) {
			if ( is_array( $statusId ) ) {
				$in = "";
				foreach ( $statusId as $status ) {
					if ( strlen( $in ) == 0 ) {
						$in = "%s";
					} else {
						$in.=" ,%s";
					}

					$values[] = $status;
				}
				$where.=" AND status_id IN ( {$in} )";
			} else {
				$values[] = $statusId;
				$where.=" AND status_id = %s";
			}
		}

		$orderDateFrom = $filter->getOrderDateFrom();
		if ( $orderDateFrom ) {
			$values[] = $orderDateFrom;
			$where.=" AND order_date >= %s";
		}

		$orderDateTo = $filter->getOrderDateTo();
		if ( $orderDateTo ) {
			$values[] = $orderDateTo;
			$where.=" AND order_date <= %s";
		}

		$userId = $filter->getUserID();
		if ( $userId ) {
			$values[] = $userId;
			$where.=" AND user_id = %s";
		}

		$query = "select count(*)
					from {$this->tableName} 
					where 1=1
					{$where}";

		//$query = $this->prepare( $query, $filter->getPluginKey(), $orderBy, $orderType, $start, $limit );
		$query = $this->prepare( $query, $values );

		return $this->getVar( $query );
	}

	public function deleteOrder ( $orderId ) {
		$orderItemtable = $this->orderItemTable;

		//delete the items
		$query = "DELETE FROM {$orderItemtable} where order_id=%d";
		$query = $this->prepare( $query, $orderId );
		$this->executeQuery( $query );


		//delete statuses
		$query = "DELETE FROM mvn_orders_status where order_id=%d";
		$query = $this->prepare( $query, $orderId );
		$this->executeQuery( $query );

		//delete the order
		return parent::deleteRow( $orderId );
	}

}
