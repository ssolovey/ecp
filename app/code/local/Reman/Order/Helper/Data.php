<?php
/**
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
	
		$order_array = array();
		
		$companyAdminId = Mage::helper('company')->getCompanyAdminId();
		
		$orderCollection = Mage::getModel('sales/order')->getCollection()
		->addFieldToFilter('customer_id', array('eq' => array($companyAdminId)));
		foreach($orderCollection as $order_row){
				$order_id = $order_row->getData('entity_id');
				$order_array[] = Mage::getModel('sales/order')->load($order_id)->getRealOrderId();
		}
		
		return $order_array;
	}
	
	
	
	
	
}