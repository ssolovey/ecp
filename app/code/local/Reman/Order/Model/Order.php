<?php
/**
 * Company Model for Reman_Company module
 *
 * @category    Reman
 * @package     Reman_Company
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Order_Model_Order extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('order/order');  
	}
	
	/**
	 * Create new order
	 *
	 * @param customer id
	 * @param order data (Array)
	 */
	public function createOrder( $customer_id, $data )
	{
		
		echo var_dump( $data );
		
		return false;
		
		$quote = Mage::getModel('sales/quote')
        	->setStoreId(Mage::app()->getStore('default')->getId());
		
		$customer = Mage::getModel('customer/customer')
                ->setWebsiteId(1)
                ->load($customer_id);
        
        $quote->assignCustomer($customer);
        
		// add product(s)
		$product = Mage::getModel('catalog/product')->load($data->partnum);

		$buyInfo = array(
		        'qty'	=> 1
		);
		
		$quote->addProduct($product, new Varien_Object($buyInfo));
		
		
		$billingData = array(
			'firstname'		=> $data[1],
			'lastname'		=> '_',
			'street' 		=> array($data[5],$data[6]),
			'city' 			=> $data[7],
			'country_id' 	=> 'US',
			'region_id' 	=> $data[8],
			'postcode' 		=> $data[9],
			'telephone' 	=> '555-555'
		);
		
		$shippingData = array(
			'firstname'		=> $data[1],
			'lastname'		=> '_',
			'street' 		=> array($data[5],$data[6]),
			'city' 			=> $data[7],
			'country_id' 	=> 'US',
			'region_id' 	=> $data[8],
			'postcode' 		=> $data[9],
			'telephone' 	=> '555-555'
		);
		
		
		$billingAddress = $quote->getBillingAddress()->addData($billingData);
		$shippingAddress = $quote->getShippingAddress()->addData($shippingData);
 
		$shippingAddress->setCollectShippingRates(true)->collectShippingRates()
	        ->setShippingMethod('flatrate_flatrate')
	        ->setPaymentMethod('checkmo');
	
			
		$quote->getPayment()->importData(array('method' => 'checkmo'));
		
		
		$service = Mage::getModel('sales/service_quote', $quote);
		$service->submitAll();
		$order = $service->getOrder();
		
		
		$data->order_id = $order->getIncrementId();
				
		$this->setData( $data );
		
		$this->save();
	}
}
