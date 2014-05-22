<?php

namespace Maven\OrderTracking;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

use Maven\Settings\OptionType,
	Maven\Settings\Option,
		Maven\Core\Utils;

/**
 * Description 
 *
 * @author Emiliano Jankowski
 * 
 */
final class StoneEdge  {
	const VERSION = '1.000';

	const CATALOG_TYPE_STRUCTURED=1;
	const CATALOG_TYPE_FLAT      =2;

	private $skuSeparator = null;
	private $lastOrderNumber  = 0;
	/**
	 *
	 * @var \Maven\Core\Domain\Order[] 
	 */
	private $orders = array();
	
	public function __construct() {
		//parent::__construct();
		//$this->skuSeparator = StoreRegistry::getInstance()->getSkuSeparator();
	}
	
	public function getSkuSeparator() {
		return $this->skuSeparator;
	}

	public function setSkuSeparator( $skuSeparator ) {
		$this->skuSeparator = $skuSeparator;
	}

	
	protected function outputError($errorCode, $errorMesg) {
		echo 'SETIError: ' . $errorMesg;
	}

	protected function preprocess() {

	}
	protected function postprocess() {

	}

	public function processAction($action, $echo = true) {
		switch($action) {
			case 'sendversion':
			case 'ordercount';
			case 'getproductscount':
				$this->handleTextResponse($action);
				break;
			case 'downloadorders':
			case 'downloadcustomers':
			case 'downloadprods':
			case 'downloadqoh':
				return $this->handleXMLResponse($action, $echo);
				break;
			case 'qohreplace':
			case 'invupdate':
			case 'updatestatus':
				$this->handleXMLRequest($action);
				break;
		}
	}

	private function handleTextResponse($action) {
		switch($action) {
			case 'sendversion':
				$this->echoVersion();
				break;
			case 'ordercount':
				$this->echoOrderCount();
				break;
			case 'getproductscount':
				$this->echoProductCount();
				break;
		}
	}
	private function handleXMLResponse($action, $echo = true) {
		
		if (!$this->validateLogin()) {
			return;
		}
		
		$xml = new \XMLWriter();
		$xml->openMemory();
		$xml->startDocument('1.0','UTF-8');

		switch($action) {
			case 'downloadorders':
				$this->buildOrdersXML($xml);
				break;
			case 'downloadcustomers':
				$this->buildCustomersXML($xml);
				break;
			case 'downloadprods':
				$this->buildProductsXML($xml);
				break;
			case 'downloadqoh':
				$this->buildProductInventoryXML($xml);
				break;
		}
		if ( $echo )
			echo $xml->outputMemory(true);
		else
			return $xml->outputMemory(true);
		
	}

	private function handleXMLRequest($action) {
		if (!$this->validateLogin()) {
			return;
		}
		
			
		$updateXML = $_REQUEST['update'];
		if (Utils::isEmpty($updateXML)) {
			return;
		}
		$updateXML = $this->stripComments($updateXML);

		$xml = simplexml_load_string(trim($updateXML));

		switch($action) {
			case 'qohreplace':
				$this->processBulkInventoryUpdate($xml);
				break;
			case 'invupdate';
				$this->realTimeInventoryUpdate($xml);
				break;
			case 'updatestatus':
				$this->updateOrderStatus($xml);
				break;
		}
	}

	private function stripComments($updateXML) {
		$keepGoing = true;

		while ($keepGoing) {
			$comments = strpos($updateXML, '<Comments>');
			if ($comments !== false) {
				$end = strpos($updateXML, '</Comments>');
				if ($end !== false) {
					$updateXML = substr($updateXML, 0, $comments) . substr($updateXML, $end+11);
				} else {
					$keepGoing = false;
				}
			} else {
				$keepGoing = false;
			}
		}
		return $updateXML;
	}

	private function updateOrderStatus(SimpleXMLElement $xml) {
		$manager = new OrderAdministration();

		$orders = $xml->children();
		if (isset($orders) && count($orders) > 0) {
			foreach($orders as $order) {
				$orderID = intval($order->OrderNumber);
				if ($orderID <= 0) {
					continue;
				}
				$status  = strtolower($order->Status);

				if (!Utils::isEmpty($order->Comments)) {
					$manager->addNoteToOrder($orderID, $this->userID, ((string)$order->Comments));
				}

				if ($status == 'shipped') {
					$this->markOrderShipped($manager, $orderID, $order);
				} else if ($status == 'order canceled') {
					$manager->updateOrderStatus($orderID, $this->userID, StoreDefs::STATUS_CANCELLED);
				}
			}
		}
		echo 'SETIResponse: update=OK;Notes=';
	}

	private function markOrderShipped(OrderAdministration $manager, $orderID, SimpleXMLElement $order) {
		$manager->updateOrderStatus($orderID, $this->userID, StoreDefs::STATUS_SHIPPED, true);

		if (!isset($order->Packages)) {
			return;
		}
		$haveTrackingNumber = false;

		$packages = $order->Packages->children();
		if (!isset($packages) || count($packages) == 0) {
			return;
		}
		foreach ($packages as $pkg) {
			$trackingNum = null;
			if (isset($pkg->TrackNum)) {
				$trackingNum = (string)$pkg->TrackNum;
			}
			$packageID = null;
			if (isset($pkg->PackageID)) {
				$packageID = (string)$pkg->PackageID;
			}
			$shipper = null;
			if (isset($pkg->Shipper)) {
				$shipper = (string)$pkg->Shipper;
			}
			$method = null;
			if (isset($pkg->Method)) {
				$method = (string)$pkg->Method;
			}

			$mesg = array('Package Shipped.');
			if (!Utils::isEmpty($packageID)) {
				$mesg[] = ' Pkg ID: ' . $packageID;
			}
			if (!Utils::isEmpty($shipper) || !Utils::isEmpty($method)) {
				$mesg [] = ', Method: ' . $shipper;
				if (!Utils::isEmpty($method)) {
					$mesg[] = ' (' . $method . ')';
				}
			}
			if (!Utils::isEmpty($trackingNum)) {
				$mesg[] = ', Tracking # ' . $trackingNum;
				if (!$haveTrackingNumber) {
					$manager->setTrackingNumber($orderID, $trackingNum);
					$haveTrackingNumber = true;
				}
			}

			$text = implode('', $mesg);

			$count = $this->db->getInt('select count(*) from store_order_history where ohs_parent_id=\'' . $orderID . '\' and ohs_type=' . StoreDefs::NOTE_ORDER . ' and ohs_comments=\'' . DBUtils::addSlashes($text) . '\'');
			if ($count == 0) {
				$manager->addNoteToOrder($orderID, $this->userID, $text);
			}
		}
	}

	private function realTimeInventoryUpdate(SimpleXMLElement $xml) {
		$isInventoryEnabled = StoreRegistry::getInstance()->isInventoryEnabled();

		$rows = -1;
		$quantity = 0;
		$sku = '';

		if (isset($xml->Product)) {
			$sku = (string)$xml->Product->SKU;
			$qoh = intval($xml->Product->QOH);

			if ($isInventoryEnabled && !Utils::isEmpty($sku)) {
				list($rows,$quantity) = $this->adjustInventoryForSKU($qoh, $sku);
			}
		}
		$response = 'SETIResponse=';
		if ($rows == 1) {
			$response .= 'OK;SKU=' . $sku . ';QOH=' . $quantity . ';NOTE=';
		} else if ($rows == 0) {
			$response .= 'FALSE;SKU=' . $sku . ';QOH=NA;NOTE=NotFound';
		} else if ($rows == -1) {
			$response .= 'FALSE;SKU=' . $sku . ';QOH=NA;NOTE=NotTracking';
		}
		echo $response . "\r\n";
	}

	private function processBulkInventoryUpdate(SimpleXMLElement $xml) {
		$isInventoryEnabled = StoreRegistry::getInstance()->isInventoryEnabled();

		$response = array('SETIResponse');

		$list = $xml->children();
		if (isset($list) && count($list) > 0) {
			foreach ($list as $product) {
				$sku = $product->SKU;
				if ($sku instanceof SimpleXMLElement) {
					$sku = (string)$sku;
				}
				$qoh = intval($product->QOH);

				$rows = -1;
				if ($isInventoryEnabled && !Utils::isEmpty($sku)) {
					$rows = $this->replaceInventoryForSKU($qoh, $sku);
				}
				if ($rows == 1) {
					$response[] = $sku . '=OK';
				} else if ($rows == 0) {
					$response[] = $sku . '=NF';
				} else if ($rows == -1) {
					$response[] = $sku . '=NA';
				}
			}
		}
		$response[] = 'SETIEndOfData';
		echo (implode("\r\n", $response)) . "\r\n";
	}

	private function replaceInventoryForSKU($newQuantity, $sku) {
		$sku = DBUtils::addSlashes($sku);

		$row = $this->db->get('select pro_id, pro_quantity from store_products where pro_sku=\'' . $sku . '\' limit 1');
		if (isset($row) && isset($row['quantity'])) {
			if ($row['quantity'] != $newQuantity) {
				$this->db->execute('update store_products set pro_quantity=\'' . $newQuantity . '\' where pro_id=' . $row['id']);
			}
			return 1;
		}
		// depending upon how StoneEdge is set up, they can use SKU's like this:
		// A-12-1...where A represents the parent product, and 12-1 is the option sku
		$row = $this->db->get('select prv_id, prv_quantity from store_product_variations,store_products where prv_pro_id=pro_id and (concat(pro_sku, \'' . $this->skuSeparator . '\', prv_sku)=\'' . $sku . '\' or prv_sku=\'' . $sku . '\') limit 1');
		if (isset($row) && isset($row['quantity'])) {
			if ($row['quantity'] != $newQuantity) {
				$this->db->execute('update store_product_variations set prv_quantity=\'' . $newQuantity . '\' where prv_id=' . $row['id']);
			}
			return 1;
		}
		return 0;
	}

	private function adjustInventoryForSKU($quantityAdjustment, $sku) {
		$sku = DBUtils::addSlashes($sku);

		$row = $this->db->get('select pro_id, pro_quantity from store_products where pro_sku=\'' . $sku . '\' limit 1');
		if (isset($row) && isset($row['quantity'])) {
			$newQuantity = intval($row['quantity']) + intval($quantityAdjustment);
			$this->db->execute('update store_products set pro_quantity=\'' . $newQuantity . '\' where pro_id=' . $row['id']);
			return array(1, $newQuantity);
		}
		// depending upon how StoneEdge is set up, they can use SKU's like this:
		// A-12-1...where A represents the parent product, and 12-1 is the option sku
		$row = $this->db->get('select prv_id, prv_quantity from store_product_variations,store_products where prv_pro_id=pro_id and (concat(pro_sku, \'' . $this->skuSeparator . '\', prv_sku)=\'' . $sku . '\' or prv_sku=\'' . $sku . '\') limit 1');
		if (isset($row) && isset($row['quantity'])) {
			$newQuantity = intval($row['quantity']) + intval($quantityAdjustment);
			$this->db->execute('update store_product_variations set prv_quantity=\'' . $newQuantity . '\' where prv_id=' . $row['id']);
			return array(1, $newQuantity);
		}
		return array(0,0);
	}

	private function buildProductInventoryXML(\XMLWriter $xml) {
		$isInventoryEnabled = StoreRegistry::getInstance()->isInventoryEnabled();

		list($startNum, $batchSize) = $this->getStartNumBatchSize();

		$sql = 'select pro_id,pro_sku,pro_quantity,pro_inventory_tracking from store_products order by pro_id';
		if ($startNum > -1 && $batchSize > -1) {
			$sql .= ' limit ' . $startNum . ', ' . $batchSize;
		}
		$list = $this->db->parseToArray($sql);
		unset($sql);

		$responseCode = 1;
		if (isset($list) && count($list) == 0) {
			$responseCode = 2;
		} else if (!isset($list)) {
			$responseCode = 3;
		}
		$xml->startElement('SETIProducts');
			$xml->startElement('Response');
				$xml->writeElement('ResponseCode', $responseCode);
				$xml->writeElement('ResponseDescription', $responseCode == 3 ? 'error' : 'success');
			$xml->endElement(); // Response

		if (isset($list) && count($list) > 0) {
			foreach ($list as $row) {
				if ($row['inventory_tracking'] == Product::INVENTORY_TRACKING_PRODUCT) {
					$xml->startElement('Product');
						$xml->writeElement('Code', $row['sku']);
						$xml->writeElement('WebID', $row['id']);
						$xml->writeElement('QOH',  $isInventoryEnabled ? $row['quantity'] : 'NA');
					$xml->endElement(); // Product
				} else if ($row['inventory_tracking'] == Product::INVENTORY_TRACKING_OPTIONS) {
					$this->buildProductInventoryForOptions($xml, $row['id'], $row['sku']);
				}
			}
		}
		$xml->endElement(); // SETIProducts
	}

	private function buildProductInventoryForOptions(\XMLWriter $xml, $productID, $productSKU) {
		$list = $this->db->parseToArray('select prv_id,prv_quantity,prv_sku from store_product_variations where prv_active=1 and prv_pro_id=' . $productID);
		if (isset($list)) {
			foreach ($list as $row) {
				$xml->startElement('Product');
					$xml->writeElement('Code', $this->getFullSKU($productSKU, $row['sku']));
					$xml->writeElement('WebID', $this->getVariationID($row['id']));
					$xml->writeElement('QOH',  $row['quantity']);
				$xml->endElement(); // Product
			}
		}
	}

	private function buildCustomersXML(\XMLWriter $xml) {
		$list = $this->getCustomerIdList();

		$responseCode = 1;
		if (isset($list) && count($list) == 0) {
			$responseCode = 2;
		} else if (!isset($list)) {
			$responseCode = 3;
		}
		$xml->startElement('SETICustomers');
			$xml->startElement('Response');
				$xml->writeElement('ResponseCode', $responseCode);
				$xml->writeElement('ResponseDescription', $responseCode == 3 ? 'error' : 'success');
			$xml->endElement(); // Response

			if (isset($list) && count($list) > 0) {
				$manager = new CustomerAdministration();

				foreach ($list as $id) {
					$customer =	$manager->getCustomerByID($id);
					if (!isset($customer)) {
						continue;
					}
					$xml->startElement('Customer');
						$xml->writeElement('WebID', $customer->getRefID());

						$card = $customer->getDefaultPaymentMethod();
						if (isset($card) && $card->hasAddress()) {
							$addy = $card->getAddress();
							$xml->startElement('BillAddr');
								$this->writeCustomerAddress($xml, $addy, $customer->getEmail(), $customer->getTaxID());
							$xml->endElement(); // BillAddr
						}
						$addressList = $customer->getAddresses();
						if (isset($addressList) && count($addressList) > 0) {
							foreach ($addressList as $addy) {
								$xml->startElement('ShipAddr');
									$this->writeCustomerAddress($xml, $addy, $customer->getEmail(), null);
								$xml->endElement(); // ShipAddr
							}
						}
					$xml->endElement(); // Customer
				}
			}
		$xml->endElement();	// SETICustomers
	}

	private function writeCustomerAddress(\XMLWriter $xml, CustomerAddress $addy, $email, $taxID=null) {
		$xml->writeElement('FirstName',  $addy->getFirstName());
		$xml->writeElement('LastName',   $addy->getLastName());
		$xml->writeElement('Company',    $addy->getCompany());
		$xml->writeElement('Phone',      TextUtils::formatPhoneNumber($addy->getPhone()));
		$xml->writeElement('Email',      $email);
		if (isset($taxID)) {
			$xml->writeElement('TaxIDNumber',$taxID);
		}
		$xml->startElement('Address');
			$xml->writeElement('Addr1',  $addy->getAddress1());
			$xml->writeElement('Addr2',  $addy->getAddress2());
			$xml->writeElement('City',   $addy->getCity());
			$xml->writeElement('State',  $this->chop2($addy->getStateCode()));
			$xml->writeElement('Zip',    $addy->getPostalCode());
			$xml->writeElement('Country',$this->chop2($addy->getCountryCode()));
		$xml->endElement(); // Address
	}

	private function chop2($val) {
		if (isset($val) && strlen($val) > 2) {
			return substr($val, 0, 2);
		}
		return $val;
	}

	private function buildProductsXML(\XMLWriter $xml) {
		$list = $this->getProductIdList();

		$responseCode = 1;
		if (isset($list) && count($list) == 0) {
			$responseCode = 2;
		} else if (!isset($list)) {
			$responseCode = 3;
		}
		$catalogHelper = $this->getCatalogHelper();

		$xml->startElement('SETIProducts');
			$xml->startElement('Response');
				$xml->writeElement('ResponseCode', $responseCode);
				$xml->writeElement('ResponseDescription', $responseCode == 3 ? 'error' : 'success');
			$xml->endElement(); // Response

		if (isset($list) && count($list) > 0) {
			$manager = new StoreAdministration();
			$config  = Sumo::getConfig();

			foreach ($list as $id) {
				$product = $manager->getProduct($id);
				if (!isset($product)) {
					continue;
				}
				$catalogHelper->writeProduct($xml, $product, $config);
			}
		}
		$xml->endElement();	// SETIProducts
	}

	/**
	 * Enter description here...
	 *
	 * @return SEOMCatalogHelper
	 */
	private function getCatalogHelper() {
		$registry = StoreRegistry::getInstance();
		$catalogType = $registry->getStoneEdgeCatalogType();

		$catalogHelper = null;
		if ($catalogType == self::CATALOG_TYPE_FLAT) {
			$catalogHelper = new SEOMFlatCatalogHelper($this);
		} else {
			$catalogHelper = new SEOMStructuredCatalogHelper($this);
		}
		return $catalogHelper;
	}

	private function getProductIdList() {
		list($startNum, $batchSize) = $this->getStartNumBatchSize();

		$sql = 'select pro_id from store_products order by pro_id';
		if ($startNum > -1 && $batchSize > -1) {
			$sql .= ' limit ' . $startNum . ', ' . $batchSize;
		}
		return $this->db->columnToArray($sql);
	}

	private function getCustomerIdList() {
		list($startNum, $batchSize) = $this->getStartNumBatchSize();

		$sql = 'select cus_id from store_customers where cus_approved=1 and cus_deleted=0 order by cus_id';
		if ($startNum > -1 && $batchSize > -1) {
			$sql .= ' limit ' . $startNum . ', ' . $batchSize;
		}
		return $this->db->columnToArray($sql);
	}

	
	
	public function getOrders() {
		return $this->orders;
	}

	public function setOrders( $orders ) {
		$this->orders = $orders;
	}

	private function buildOrdersXML(\XMLWriter $xml) {
		//$manager = new OrderAdministration();

		$lastOrder = $this->getLastOrderNumber();
		list($startNum, $batchSize) = $this->getStartNumBatchSize();

		//$orderRefs = $this->getOrderIDsSinceLastOrderID($lastOrder, $startNum, $batchSize);

		$responseCode = 1;
		if (isset($this->orders) && count($this->orders) == 0) {
			$responseCode = 2;
		} else if (!isset($this->orders)) {
			$responseCode = 3;
		}
		$xml->startElement('SETIOrders');
			$xml->startElement('Response');
				$xml->writeElement('ResponseCode', $responseCode);
				$xml->writeElement('ResponseDescription', $responseCode == 3 ? 'error' : 'success');
			$xml->endElement(); // Response

		if (isset($this->orders) && count($this->orders) > 0) {
			//$catalogHelper = $this->getCatalogHelper();

//			$registry    = StoreRegistry::getInstance();
//
//			$taxBasis    = $registry->getTaxBasis();
//			
//			// right now the only customer using this mapping is May Arts
//			$mapSalesRep = $registry->getBoolean('stone.edge.associate.mapping');
//			$associateID = $registry->get('stone.edge.associate.id');
//
//			$fieldsManager = null;
//			if ($mapSalesRep) {
//				$fieldsManager = new FieldsManager();
//			}
			
			foreach($this->orders as $order) {
				//$order = $manager->getOrderByOrderID($orderID);
				//$order->calculateTotals();

				$xml->startElement('Order');
					$xml->writeElement('OrderNumber', $order->getNumber());
					//$xml->writeElement('OrderDate', $this->toDate($order->getOrderDate()));
					$xml->writeElement('OrderDate', $order->getOrderDate());

					$xml->startElement('Billing');
						$this->writeContact($xml, $order->getBillingContact(), \Maven\Core\Domain\AddressType::Billing);
					$xml->endElement();
					$xml->startElement('Shipping');
						$this->writeContact($xml, $order->getShippingContact(), \Maven\Core\Domain\AddressType::Shipping);

						//$catalogHelper->writeOrderItems($xml, $order);

					$xml->endElement(); // Shipping

					$xml->startElement('Payment');
						$this->writePaymentDetails($xml, $order);
					$xml->endElement(); // Payment

					// $this->writeOrderTotals($xml, $order, $taxBasis); 
					$this->writeOrderTotals($xml, $order);

//					$xml->startElement('Other');
//						if ($mapSalesRep) {
//							$fields = $fieldsManager->getFieldsForEntity(CustomField::TYPE_STORE_CUSTOMER, $order->getCustomerID(), true);
//							if (isset($fields)) {
//								foreach($fields as $f) {
//									if ($f->getRefID() == $associateID) {
//										$xml->writeElement('Associate', $f->getValue());
//									}
//								}
//							}
//						}
//						if (!Utils::isEmpty($order->getComments())) {
//							$xml->writeElement('Comments', $order->getComments());
//						}
//						$xml->writeElement('WebCustomerID', $order->getCustomerID());
//					$xml->endElement(); // Other

				$xml->endElement(); // Order
			}
		}
		$xml->endElement();	// SETIOrders
		
		
	}

	private function getOrderIDsSinceLastOrderID($lastOrderID, $startNum=-1, $batchSize=-1) {
		$lines = array();
		$lines[] = 'select ord_id from store_orders ord where (ord_deleted=0 and ';
		$lines[] = 'ord_ost_id in (' . $this->getOrderStatusList() . ')';
		$lines[] = ') and ord_id > ' . $lastOrderID . ' order by ord_last_modified asc ';
		if ($startNum > -1 && $batchSize > -1) {
			$lines[] = 'limit ' . $startNum . ', ' . $batchSize;
		}
		return $this->db->columnToArray( implode('', $lines) );
	}

	/**
	 * Enter description here...
	 *
	 * @param \XMLWriter $xml
	 * @param Contact $contact
	 * @param bool $writeEmail
	 */
	private function writeContact(\XMLWriter $xml, \Maven\Core\Domain\Contact $contact, $addressType) {
		if (isset($contact)) {
			$xml->writeElement('FullName',    $contact->getFullName());
			$xml->writeElement('Company',     $contact->getCompany());
			$xml->writeElement('Phone',       $contact->getPhone());
			$xml->writeElement('Email',       $contact->getEmail());
			$xml->startElement('Address');
			
				$address = $contact->getAddress( $addressType );
				
				$xml->writeElement('Street1', $address->getFirstLine());
				$xml->writeElement('Street2', $address->getSecondLine());
				$xml->writeElement('City',    $address->getCity());
				$xml->writeElement('State',   $address->getState());
				$xml->writeElement('Code',    $address->getZipcode());
				$xml->writeElement('Country', $address->getCountry());
			$xml->endElement(); // Address
		}
	}

	private function writePaymentDetails(\XMLWriter $xml, \Maven\Core\Domain\Order $order) {
		//if ($order->isCreditCardPayment()) {
			//$cc = $order->getCreditCard();

			$xml->startElement('CreditCard');
				$xml->writeElement('Issuer', $order->getCreditCard()->getType());
				$xml->writeElement('Number', $order->getCreditCard()->getNumber());
				$xml->writeElement('ExpirationDate', $order->getCreditCard()->getExpirationDate());
				//$xml->writeElement('VerificationValue', '');
				$xml->writeElement('FullName', $order->getCreditCard()->getHolderName());
				//$xml->writeElement('Company', '');
				//$xml->writeElement('BankName', '');
				//$xml->writeElement('OrderProcessingInfo', '');
				//$xml->writeElement('AVS', '');
				$xml->writeElement('TransID', $order->getTransactionID());
				//$xml->writeElement('AuthCode', $order->getAuthCode());
				//$xml->writeElement('ProcessLevel', $order->isFulfillmentCaptured() ? 'Captured' : 'Auth Only');
				$xml->writeElement('Amount', $order->getTotal());
			$xml->endElement(); // CreditCard
		//}
		
		
//		else if ($order->isCheckPayment()) {
//			$xml->startElement('Check');
//				//$xml->writeElement('RoutingNumber', '');
//				//$xml->writeElement('AccountNumber', '');
//				//$xml->writeElement('CheckNumber', '');
//			$xml->endElement(); // Check
//		}
//		else if ($order->isCODPayment()) {
//			$xml->writeElement('COD', '');
//		}
//		else if ($order->isPayPalPayment()) {
//			$paypal = $this->db->get('select * from store_orders_paypal where opp_ord_id=' . $order->getRefID() . ' limit 1');
//			if (isset($paypal)) {
//				$xml->startElement('PayPal');
//					$xml->startElement('Payer');
//						$xml->writeElement('ID', $paypal['payer_id']);
//						$xml->writeElement('PayerStatus', strtolower($paypal['payer_status']));
//						//$xml->writeElement('Prefix', ''); // Mr. Mrs., etc
//						$billing = $order->getBillingContact();
//						if (isset($billing)) {
//							$xml->writeElement('FirstName', $billing->getFirstName());
//							$xml->writeElement('LastName', $billing->getLastName());
//							$xml->writeElement('Email', $billing->getEmail());
//							$xml->writeElement('Company', $billing->getCompany());
//						}
//						$xml->writeElement('AddressStatus', strtolower($paypal['address_status']));
//					$xml->endElement(); // Payer
//
//					$xml->startElement('Transaction');
//						$xml->writeElement('TransID', $order->getTransactionID()); // PayPal transaction ID
//						$xml->writeElement('Status', strtolower($paypal['payment_status']));
//						$xml->writeElement('Amount', $order->getGrandTotal());
//						$xml->writeElement('TransDate', date('c', strtotime($paypal['payment_date'])));
//						$xml->writeElement('ProcessingFee', $paypal['fee_amount']);
//						$xml->writeElement('TaxAmount', $order->getTax());
//						$xml->writeElement('ReasonCode', strtolower($paypal['reason_code']));
//						$xml->writeElement('PendingReason', strtolower($paypal['pending_reason']));
//					$xml->endElement(); // Transaction
//
//				$xml->endElement(); // PayPal
//			}
//		}
	}

	private function getCardName(CreditCard $card) {
		$name = $card->getName();

		switch ($card->getType()) {
			case CreditCard::TYPE_AX:
    			$name = 'Amex';
    			break;
		}
		return $name;
	}

	private function writeOrderTotals(\XMLWriter $xml, \Maven\Core\Domain\Order $order, $taxBasis = false) {
		$xml->startElement('Totals');
			//$xml->writeElement('ProductTotal', TextUtils::formatCurrency($order->getProductTotal()));
			$xml->writeElement('ProductTotal', $order->getSubtotal());

//			if ($order->hasDiscounts()) {
//				$discounts = $order->getDiscounts();
//				if (isset($discounts) && count($discounts) > 0) {
//					foreach($discounts as $discount) {
//						$xml->startElement('Discount');
//							$xml->writeElement('Type', 'flat');
//
//							$desc = $discount->getDescription();
//							if ($discount->isPromotion()) {
//								$desc .= ' (' . $discount->getPromoCode() . ')';
//							}
//							$xml->writeElement('Description', $desc);
//							$xml->writeElement('Amount', TextUtils::formatCurrency($discount->getAmount()));
//							$xml->writeElement('ApplyDiscount', 'post');
//						$xml->endElement();
//					}
//				}
//			}

			$xml->writeElement('SubTotal', $order->getSubTotal());

//			$xml->startElement('Tax');
//				$xml->writeElement('TaxAmount', TextUtils::formatCurrency($order->getTax()));
//				$xml->writeElement('TaxRate', $order->getTaxRate());
//				$xml->writeElement('TaxShipping', $taxBasis==StoreDefs::TAX_BASIS_SUBTOTAL_SHIPPING ? 'Yes': 'No');
//				$xml->writeElement('TaxExempt', 'No');
//				$xml->writeElement('TaxID', '');
//			$xml->endElement(); // Tax

			$xml->writeElement('GrandTotal', $order->getTotal());

//			if ($order->hasAdditionalCharges()) {
//				$charges = $order->getAdditionalCharges();
//				foreach($charges as $charge) {
//					$xml->startElement('Surcharge');
//						$xml->writeElement('Total', TextUtils::formatCurrency($charge->getAmount()));
//						$xml->writeElement('Description', $charge->getDescription());
//					$xml->endElement(); // Surcharge
//				}
//			}
//			$xml->startElement('ShippingTotal');
//				$xml->writeElement('Total', TextUtils::formatCurrency($order->getShippingHandling()));
//				$xml->writeElement('Description', $this->getShippingMethod($order));
//			$xml->endElement(); // ShippingTotal
		$xml->endElement(); // Totals
	}

	private function getShippingMethod(Order $order) {
		// strip any possible tags from UPS shipping methods. SEOM can't handle the &reg; etc tags

		$method = strip_tags($order->getShippingMethod());
		return str_replace(array('&reg;','&amp;reg;'), '', $method);
	}

	private function echoVersion() {
		// params include:
		//   setifunction=sendversion
		//   omversion=5.504
		echo 'SetiResponse: version=' . self::VERSION;
	}

	private function echoOrderCount() {
		// setifunction=ordercount
		// setiuser=theuser
		// password=thepassword
		// lastdate=All
		// code=
		// lastorder=All
		// omversion=5.504
		if (!$this->validateLogin()) {
			return;
		}
		$lastOrder = $this->getLastOrder();

		$count = $this->db->getInt('select count(*) from store_orders where (ord_deleted=0 and ord_ost_id in (' . $this->getOrderStatusList()  . ')) and ord_id > ' . $lastOrder);
		echo 'SetiResponse: ordercount=' . $count;
	}

	private function echoProductCount() {
		// setifunction=getproductscount
		// setiuser=theuser
		// password=thepassword
		// lastdate=All
		// code=
		// lastorder=All
		// omversion=5.504
		if (!$this->validateLogin()) {
			return;
		}
		echo 'SetiResponse: itemcount=' . $this->db->getInt('select count(*) from store_products');
	}

	private function validateLogin() {
		
		return true;
		
		list($loginOK, $message) = $this->handleLogin(WebUtils::getParameter('setiuser'), WebUtils::getParameter('password'));
		if (!$loginOK) {
			$this->outputError(10, $message);
		}
		return $loginOK;
	}

	
	public function getLastOrderNumber() {
		return $this->lastOrderNumber;
	}

	public function setLastOrderNumber( $lastOrderNumber ) {
		$this->lastOrderNumber = $lastOrderNumber;
	}

			

	private function getStartNumBatchSize() {
		
		//TODO: Ver que hacer con estos parametros
		$startNum  = -1;//WebUtils::getIntParameter('startnum', -1);
		$batchSize = -1;//WebUtils::getIntParameter('batchsize', -1);

		if ($startNum > -1 && $batchSize > -1) {
			if ($startNum > 0) {
				$startNum--;
			}
		}
		return array($startNum, $batchSize);
	}

	// this function is public so it can be calling by the catalog helpers
	public function getFullSKU($productSKU, $variationSKU) {
		$sku = StringUtils::nullToString($productSKU);

		if (isset($variationSKU) && !Utils::isEmpty($variationSKU)) {
			if (!Utils::isEmpty($sku)) {
				$sku .= $this->skuSeparator;
			}
			$sku .= $variationSKU;
		}
		return $sku;
	}

	public function getVariationID($id) {
		return 'V' . $id;
	}
}