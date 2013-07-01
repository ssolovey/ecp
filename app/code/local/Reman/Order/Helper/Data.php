<?php
/**
 * Order Helper Data
 * @category    Reman
 * @package     Reman_Order
 * @author		Igor Zhavoronkin (zhavoronkin.i@gmail.com)
 */
class Reman_Order_Helper_Data extends Mage_Core_Helper_Abstract
{

	/*
	 * Get Current Logged Company Orders Ids
	 * @return Array
	 *
	*/
	
	function getCompanyOrdersIds(){

		$orders = array();
		// get All Customers Ids of current Logged Company
		$usersId = Mage::helper('company')->getAllCompanyCustomersId();
		// Get Users Orders
		foreach ($usersId as $value){
			// Get Order Collection
			$orders_by_user = array();
			
			$customerData = Mage::getModel('customer/customer')->load($value)->getData();
			
			$orders_by_user['user_id'] = $customerData['firstname'].' '.$customerData['lastname'];
				
			$orderCollection = Mage::getModel('sales/order')->getCollection()
			->addFieldToFilter('customer_id', array('eq' => array($value)));
			// Filter Orders
			foreach($orderCollection as $order_row){
					$order_id = $order_row->getData('entity_id');
					$orders_by_user['orders'][] = Mage::getModel('sales/order')->load($order_id)->getRealOrderId();
			}
			
			$orders[] = $orders_by_user;
		}
				
		return $orders;
	}
	

}