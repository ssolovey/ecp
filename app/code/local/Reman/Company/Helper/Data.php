<?php
class Reman_Company_Helper_Data extends Mage_Core_Helper_Abstract
{
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
	
	public function getCustomerShippingPrice()
	{
		
		$company = Mage::getModel('company/company')->load($this->getCompanyId());
  		$shippingPrice = $company->ship;
		if(is_null($shippingPrice))
		{
			return "NULL";
		}else
		{
			return $shippingPrice;
		}
	}
}