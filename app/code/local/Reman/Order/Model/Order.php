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
		$orderCollection = $this->getCollection();
		$orderCollection->addFieldToFilter('ete_order_id', $data['ete_order_id']);
		
		$order = $orderCollection->getFirstItem();

		//$order = $this->load( $data['order_id'] );
		
		// check: is order in web database?
		if ( $order->getId() ) {
			
			$this->_updateOrder($order, $data);
			
			return;
		}
		
		try
		{
			// init new order
			$quote = Mage::getModel('sales/quote')
	        	->setStoreId(Mage::app()->getStore('default')->getId());
			
			$customer = Mage::getModel('customer/customer')
	                ->setWebsiteId(1)
	                ->load($customer_id);
	        
	        $quote->assignCustomer($customer);
	        
			// add product(s)
			$product_id = Mage::getModel('catalog/product')->loadByAttribute( 'sku', $data['partnum'] );
			
			$product = Mage::getModel('catalog/product')->load($product_id->getId());
			
			$buyInfo = array(
			        'qty'	=> 1
			);
			
			$quote->addProduct($product, new Varien_Object($buyInfo));
			
			$billingData = array(
				'firstname'		=> $data['bt_cust_name'],
				'lastname'		=> '_',
				'street' 		=> array($data['bt_addr1'],$data['bt_addr2']),
				'city' 			=> $data['bt_city'],
				'country_id' 	=> 'US',
				'region_id' 	=> $data['bt_state'],
				'postcode' 		=> $data['bt_zip'],
				'telephone' 	=> $data['st_phone']
			);
			
			$shippingData = array(
				'firstname'		=> $data['st_cust_name'],
				'lastname'		=> '_',
				'street' 		=> array($data['st_addr1'],$data['st_addr2']),
				'city' 			=> $data['st_city'],
				'country_id' 	=> 'US',
				'region_id' 	=> $data['st_state'],
				'postcode' 		=> $data['st_zip'],
				'telephone' 	=> $data['st_phone']
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
		
			$data['order_id'] = $order->getIncrementId();
							
			$this->setData( $data );
			
			$this->save();
			
			return 'Success';
			
			// export new order
			Mage::getModel('sync/export')->exportOrder($data);
			
		}
		catch (Exception $e)
		{
			return $e->getMessage();
		}
	}
	
	/**
	 * Update existing order
	 *
	 * @param order data (Array)
	 */
	protected function _updateOrder( $order, $data )
	{		
		$order->addData( $data );
		
		$order->save();
		
		// export order update
		Mage::getModel('sync/export')->exportOrder($data);
	}
}
