<?php
/**
 * Model for Orders updates
 * 
 * @category	Reman
 * @package		Reman_Sync
 * @author		Artem Petrosyan <artpetrosyan@gmail.com>
 */
class Reman_Sync_Model_Order extends Reman_Sync_Model_Abstract
{
	
	//path to directory to scan
	protected $_directory = 'import/orders/';
		
	// current file path
	protected $_file;
	
	// override
	protected function _parseItem( $item )
	{
		
		$quote = Mage::getModel('sales/quote')
        	->setStoreId(Mage::app()->getStore('default')->getId());
		
		
		$customer = Mage::getModel('customer/customer')
                ->setWebsiteId(1)
                ->loadByEmail($item[2]);
        
        $quote->assignCustomer($customer);
        
		// add product(s)
		$product = Mage::getModel('catalog/product')->load(3420);

		$buyInfo = array(
		        'qty'	=> 1
		        // custom option id => value id
		        // or
		        // configurable attribute id => value id
		);
		
		$quote->addProduct($product, new Varien_Object($buyInfo));
		
		
		$addressData = array(
			'firstname'		=> $item[1],
			'lastname'		=> '_',
			'street' 		=> array($item[5],$item[6]),
			'city' 			=> $item[7],
			'country_id' 	=> 'US',
			'region_id' 	=> $item[8],
			'postcode' 		=> $item[9],
			'telephone' 	=> '555-555'
		);
		
		
		$billingAddress = $quote->getBillingAddress()->addData($addressData);
		$shippingAddress = $quote->getShippingAddress()->addData($addressData);
 
		$shippingAddress->setCollectShippingRates(true)->collectShippingRates()
	        ->setShippingMethod('flatrate_flatrate')
	        ->setPaymentMethod('checkmo');
	
			
		$quote->getPayment()->importData(array('method' => 'checkmo'));
		
		
		$service = Mage::getModel('sales/service_quote', $quote);
		$service->submitAll();
		$order = $service->getOrder();
		 
		printf("Created order %s\n", $order->getIncrementId());
				
		//$this->create( $orderData, $product );
		
		// Delete file
		//unlink( $this->_file );
	}
	
	/**
	 * Retrieve order create model
	 *
	 * @return  Mage_Adminhtml_Model_Sales_Order_Create
	 */
	protected function _getOrderCreateModel()
	{
		return Mage::getSingleton('adminhtml/sales_order_create');
	}
	
	/**
	 * Retrieve session object
	 *
	 * @return Mage_Adminhtml_Model_Session_Quote
	 */
	protected function _getSession()
	{
		return Mage::getSingleton('adminhtml/session_quote');
	}
	
	/**
	 * Initialize order creation session data
	 *
	 * @param array $data
	 * @return Mage_Adminhtml_Sales_Order_CreateController
	 */
	protected function _initSession($data)
	{
		// Get/identify customer
		if (!empty($data['customer_id'])) {
			$this->_getSession()->setCustomerId((int) $data['customer_id']);
		}
		// Get/identify store
		if (!empty($data['store_id'])) {
			$this->_getSession()->setStoreId((int) $data['store_id']);
		}
		
		return $this;
	}
	
	protected function _processQuote($data = array())
	{
		/* Saving order data */
		if (!empty($data['order'])) {
			$this->_getOrderCreateModel()->importPostData($data['order']);
		}
		$this->_getOrderCreateModel()->getBillingAddress();
		$this->_getOrderCreateModel()->setShippingAsBilling(true);
		
		/* Just like adding products from Magento admin grid */
		if (!empty($data['add_products'])) {
			$this->_getOrderCreateModel()->addProducts($data['add_products']);
		}
		
		/* Collect shipping rates */
		$this->_getOrderCreateModel()->collectShippingRates();
		
		/* Add payment data */
		if (!empty($data['payment'])) {
			$this->_getOrderCreateModel()->getQuote()->getPayment()->addData($data['payment']);
		}
		
		$this->_getOrderCreateModel()
			->initRuleData()
			->saveQuote();

		return $this;
	}
	
	/**
	 * Creates order
	 */
	public function create( $orderData, $product )
	{
		if (!empty($orderData)) {
			$this->_initSession($orderData['session']);
			//try {
				$this->_processQuote($orderData);
				if (!empty($orderData['payment'])) {
					$this->_getOrderCreateModel()->setPaymentData($orderData['payment']);
					$this->_getOrderCreateModel()->getQuote()->getPayment()->addData($orderData['payment']);
				}
				$item = $this->_getOrderCreateModel()->getQuote()->getItemByProduct($product);
				
				Mage::app()->getStore()->setConfig(Mage_Sales_Model_Order::XML_PATH_EMAIL_ENABLED, "0");
				$_order = $this->_getOrderCreateModel()
					->importPostData($orderData['order'])
					->createOrder();
				$this->_getSession()->clear();
				Mage::unregister('rule_data');
				return $_order;
			//}
			//catch (Exception $e){
			//	echo $e;
			//	echo "<h1>Order save error</h1>";
			//}
		}
		return null;
	}
	
	// override
	public function syncData()
	{	
	
		$profiles = glob($this->_directory . '*.TXT');
		
		$this->_file = $profiles[0];
		
		if ( $this->_file  ) {
			echo '<h3>Parse file: ' . $this->_file . '</h3>';
			echo '<h4><a href=".">NEXT FILE</a></h4>';
		
			$this->_loadFile( $this->_file );
		} else {
			echo '<h3>No more files to parse in directory: ' . $this->_directory . '</h3>';
		}
		
		/*
		foreach($profiles as $file)
		{
			echo'<h3>Parse file: ' . $file . '</h3>';
			$this->_loadFile( $file );
		}
		*/
		
	}
}
