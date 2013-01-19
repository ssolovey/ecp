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
		
		$orderData = array(
			
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
