<?php
/**
 * Profile model
 * 
 * @category	Reman
 * @package		Reman_Sync
 * @author		Artem Petrosyan <artpetrosyan@gmail.com>
 */
 
class Reman_Sync_Model_Profile extends Mage_Core_Model_Abstract
{
	
	protected $_customers;
		
	protected $passwordLength = 10;
	
	public function _construct()
	{
		parent::_construct();
		
		$this->_customers		=	Mage::getModel('customer/customer');
		
		$this->_customers->setWebsiteId(Mage::app()->getWebsite()->getId());
	}
	
	/**
	 * Load customers CSV file
	 *
	 */
	public function loadFile()
	{
				
		// Location of CSV file
		$file	=	'import/cust.csv';

		$csv	=	new Varien_File_Csv();

		// Set delimiter to "\"
		$csv->setDelimiter('|');

		// Load data from CSV file
		$data	=	$csv->getData($file);
				
		foreach( $data as $item ) {
			
			// try to load customer by id
			$customer = $this->_customers->loadByEmail( $item[13] );
			
			if ( $customer->getId() ) {
				echo 'Customer already exists';
			} else {
				// create new customer
				$this->_createCustomer( $customer, $item );
			}
		}
	}
	
	protected function _createCustomer( $customer, $data )
	{
		$customer->setData( 
			array(
				'eteid'			=>	$data[0],
				'email'			=>	$data[13],
				'firstname'		=>	$data[1],
				'lastname'		=>	'-',
				'splink'		=>	$data[10],
				'fluid'			=>	$data[11],
				'discount'		=>	$data[9],
				'payment'		=>	$data[23],
				'atwar'			=>	$data[18],
				'tcwar'			=>	$data[21]
				
			)
		);
		
		$this->_customers->save();
				
		$address = Mage::getModel('customer/address');
		
		$address->setCustomerId($customer->getId());


		$address->firstname		=	$customer->firstname;
		$address->lastname		=	$customer->lastname;
		$address->country_id	=	'US';
		$address->postcode		=	$data[6];
		$address->city			=	$data[4];
		$address->region		=	$data[5];
		//$address->telephone	=	'';
		//$address->fax			=	'';
		//$address->company		=	'';
		$address->street		=	array($data[2],$data[3]);
		$address->is_default_billing = true;
		$address->is_default_shipping = true;
		
		
		$address->save();
	}
}
