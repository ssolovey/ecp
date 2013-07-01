<?php
/**
 * @category    Reman
 * Helper function for Company Model
 * @package     Reman_Company_Helper
 * @author		Igor Zhavoronkin (zhavoronkin.i@gmail.com)
 */
class Reman_Company_Helper_Data extends Mage_Core_Helper_Abstract
{
	
	/**
     * Retrieve current logged user company name
     *
     * @return string
     */
	public function getCustomerCompanyObject()
	{
		$company = Mage::getModel('company/company')->load($this->getCompanyId());
		return $company;
	}
	
	
	/**
     * Retrieve current logged user company name
     *
     * @return string
     */
    public function getCompanyName()
    {
        //customer ID
		$customer_id =  Mage::getSingleton('customer/session')->getCustomer()->getId();	
		// custommer selected company ID
		$customer_company_id = Mage::getModel('customer/customer')->load($customer_id)->getCompany();
		// Companies Array
		$companiesArray = Mage::getModel('company/company')->getCompaniesArray();

		foreach($companiesArray as $index){
			if( $index['value'] == $customer_company_id){
				return $index['label'];
			}
		}
    }

	/**
     * Retrieve current logged user company Id
     *
     * @return string
     */
	 public function getCompanyId()
    {
        //customer ID
		$customer_id =  Mage::getSingleton('customer/session')->getCustomer()->getId();	
		// custommer selected company ID
		$customer_company_id = Mage::getModel('customer/customer')->load($customer_id)->getCompany();
		
		return $customer_company_id;
    }
	/**
     * Retrieve current logged user company Shipping price
     *
     * @return int or string
     */
	public function getCustomerShippingPrice()
	{
		
		$SHIPPING_DEFAULT_PRICE = 200;
		
		$company = $this->getCustomerCompanyObject();
  		
		$shippingPrice = $company->ship;
		
		if(is_null($shippingPrice))
		{
			return $SHIPPING_DEFAULT_PRICE;
		}else
		{
			return $shippingPrice;
		}
	}
	/**
     * Retrieve current logged user company GSP Link
     *
     * @return int
     */
	 public function getGSPLink()
	 {
	 	$company = $this->getCustomerCompanyObject();
  		$SPlink = $company->splink;
		return $SPlink;
	 }
	 
	 /**
     * Retrieve current logged user company Discount value
     *
     * @return int
     */
	 public function getDiscount()
	 {
	 	$company = $this->getCustomerCompanyObject();
  		$discount = $company->discount;
		return $discount;
	 }
	  /**
     * Retrieve current logged Admin Users customers Data
     *
     * @return Array
     */
	 function getCompanyCustomers() {
		 
		//customer ID
		$customer_id =  Mage::getSingleton('customer/session')->getCustomer()->getId();	
		  
		/* Get customer model, run a query */
		$collection = Mage::getModel('customer/customer')
					  ->getCollection()
					  ->addAttributeToSelect('*');
		$result = array();
		foreach ($collection as $customer) {
			$customerArr = $customer->toArray();
			if($customerArr['entity_id'] != $customer_id ){
				if($customerArr['company'] == $this->getCompanyId()){
					$result[] = $customer->toArray();
				}
			}
		}
		return $result;
	}
	
	 /**
     * Retrieve current logged Company Users customers Data
     *
     * @return Array
     */
	 function getAllCompanyCustomers() {
		 
		//customer ID
		$customer_id =  Mage::getSingleton('customer/session')->getCustomer()->getId();	
		  
		/* Get customer model, run a query */
		$collection = Mage::getModel('customer/customer')
					  ->getCollection()
					  ->addAttributeToSelect('*');
		$result = array();
		foreach ($collection as $customer) {
			$customerArr = $customer->toArray();
			if($customerArr['company'] == $this->getCompanyId()){
				$result[] = $customer->toArray();
			}
		}
		return $result;
	}
	
	
	/**
     * Retrieve current logged Company Users customers ID
     *
     * @return Array
     */
	 function getAllCompanyCustomersId() {
		 
		//customer ID
		$customer_id =  Mage::getSingleton('customer/session')->getCustomer()->getId();	
		  
		/* Get customer model, run a query */
		$collection = Mage::getModel('customer/customer')
					  ->getCollection()
					  ->addAttributeToSelect('*');
		$result = array();
		foreach ($collection as $customer) {
			$customerArr = $customer->toArray();
			if($customerArr['company'] == $this->getCompanyId()){
				$result[] = $customer->entity_id;
			}
		}
		return $result;
	}
	
	
	 /**
     * Retrieve current logged Admin User Data
     *
     * @return Array
     */
	 function getCompanyAdminEmail() {
		
		/* Get customer model, run a query */
		$collection = Mage::getModel('customer/customer')
					  ->getCollection()
					  ->addAttributeToSelect('*');
		$result = array();
		foreach ($collection as $customer) {
			$customerArr = $customer->toArray();
			if($customerArr['company'] == $this->getCompanyId()){
				$result[] = $customer->toArray();
			}
		}
		
		foreach ( $result as $key => $value ) {
			if($value['group_id'] == 6){
				return $value['email'];
			}
		}
	}
	
	/**
     * Retrieve current logged Admin User Id
     *
     * @return Int
     */
	 function getCompanyAdminId() {
		
		/* Get customer model, run a query */
		$collection = Mage::getModel('customer/customer')
					  ->getCollection()
					  ->addAttributeToSelect('*');
		$result = array();
		foreach ($collection as $customer) {
			$customerArr = $customer->toArray();
			if($customerArr['company'] == $this->getCompanyId()){
				$result[] = $customer->toArray();
			}
		}
		
		foreach ( $result as $key => $value ) {
			if($value['group_id'] == 6){
				return $value['entity_id'];
			}
		}
	}
	
}