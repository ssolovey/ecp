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
	
	// customers model
	protected $_customers;
	
	public function _construct()
	{
		parent::_construct();
		
		$this->_customers		=	Mage::getModel('customer/customer');
		$this->_customers->setWebsiteId(Mage::app()->getWebsite()->getId());
	}
	
	// override
	protected function _parseItem( $item )
	{
		// Get product model
		$product = Mage::getModel('catalog/product')->load(5111);
				
		// Get customer model	
		$customer = $this->_customers->loadByEmail( $item[2] );
		
		// Get customer adress
		$customer_adress = array(
			//'customer_address_id' => '',
			'prefix'		=> '',
			'firstname'		=> $item[1],
			'middlename'	=> '',
			'lastname'		=> '_',
			'suffix' 		=> '',
			'company' 		=> '',
			'street' 		=> array($item[5],$item[6]),
			'city' 			=> $item[7],
			'country_id' 	=> 'US',
			'region' 		=> '',
			'region_id' 	=> $item[8],
			'postcode' 		=> $item[9],
			'telephone' 	=> '',
			'fax' 			=> ''
		);
		
		// Order data mapping
		$orderData = array(
			'session'       => array(
				'customer_id'   => $customer->getId(),
				'store_id'      => '1',
			),
			'payment'       => array(
				'method'    => 'checkmo',
			),
			'add_products'  =>array(
				$product->getId() => array('qty' => 1),
			),
			'order' => array(
				'currency' => 'USD',
				'account' => array(
					'group_id'	=> $customer->_groupId,
					'email' 	=> $customer->getEmail()
				),
				'billing_address' => $customer_adress,
				'shipping_address' => $customer_adress,
				'shipping_method' => 'flatrate_flatrate',
				'comment' => array(
					'customer_note' => 'This order has been programmatically created via import script.',
				),
				'send_confirmation' => '0'
			)
		);
		
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
	
	
	/**
	 * Creates order
	 */
	public function create()
	{
		
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
