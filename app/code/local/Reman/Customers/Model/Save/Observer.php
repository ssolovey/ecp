<?php
/**
 * Customers Save Observer
 * should call after admin change customer data
 *
 * @category    Reman
 * @package     Reman_Customers
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Customers_Model_Save_Observer
{
	public function __construct()
	{
	
	}
	
	public function export_to_csv($observer)
	{
		$event = $observer->getEvent();
		$customer = $event->getCustomer();
		
		if ( $customer->getGroup_id() == 6 ) {
		
			$company = Mage::getModel('company/company')->load($customer->company);
		
			Mage::getModel('company/company')->exportCompanyUpdate($company, $customer);
		
		}
	
		return $this;
	}
}