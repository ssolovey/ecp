<?php
/**
 * Model for Customers profiles
 * 
 * @category	Reman
 * @package		Reman_Sync
 * @author		Artem Petrosyan <artpetrosyan@gmail.com>
 */
class Reman_Sync_Model_Profile extends Reman_Sync_Model_Abstract
{
	
	//path to directory to scan
	protected $_directory = 'import/customers/';
	
	/*
	protected $_customers;
		
	protected $passwordLength = 10;
	
	public function _construct()
	{
		parent::_construct();
		
		$this->_customers		=	Mage::getModel('customer/customer');
		
		$this->_customers->setWebsiteId(Mage::app()->getWebsite()->getId());
	}
	*/
	
	
	// override
	protected function _parseItem( $item )
	{
		echo $item[0] . '<br />';
		/*
		$this->setData(
			array(
				'vehicle_id'		=>		$item[0],
				'group_number'		=>		$item[2],
				'subgroup'			=>		$item[5],
				'menu_heading'		=>		$item[6],
				'applic'			=>		$item[4],
				'part_number'		=>		$item[7]
			)		    	
		);
		
		$this->save();
		*/
	}
	
	// override
	public function syncData()
	{	
	
		$profiles = glob($this->_directory . '*.csv');
		
		foreach($profiles as $file)
		{
			$this->_loadFile( $file );
		}
		
	}
	
	/*
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
	*/
}
