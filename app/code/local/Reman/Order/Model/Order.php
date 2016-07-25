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
	public function createOrder( $customer_id, $data, $sync )
	{


        if(array_key_exists('ete_order_id',$data)){

            $orderCollection = $this->getCollection();
            $orderCollection->addFieldToFilter('ete_order_id', $data['ete_order_id']);

            $order = $orderCollection->getFirstItem();



            //$order = $this->load( $data['order_id'] );

            // check: is order in web database?
            if ( $order->getId() && $sync ) {

                $this->_updateOrder($order, $data);

                return;
            }
        }



		try
		{
			// init new order
			$quote = Mage::getModel('sales/quote')
	        	->setStoreId(Mage::app()->getStore('default')->getId());

			$customer = Mage::getModel('customer/customer')
	                ->setWebsiteId(1)
	                ->load($customer_id);

	        $data['so_cont_email'] = $customer->getEmail();

	        $quote->assignCustomer($customer);

			// add product(s)
			$product_id = Mage::getModel('catalog/product')->loadByAttribute( 'sku', $data['partnum'] );

			$product = Mage::getModel('catalog/product')->load($product_id->getId());


            /* decrease selected stock by 1 item */

            $currentStockValue =  (int)$product->getData($data['ship_from_lb']);

            if($currentStockValue > 0){

                $product->setData($data['ship_from_lb'], $currentStockValue - 1);

                $product->save();
            }



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

			// export new order
			Mage::getModel('sync/export')->exportOrder($data);

            // Send Email Notification
            $this->sendEmailNotification($data, $customer->getEmail(), $customer->firstname .' '.$customer->lastname);

			return 'Success';

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
		//Mage::getModel('sync/export')->exportOrder($data);
	}


  /**
    * When New Order is exported
    * Send E-mail to  WebOrders@etereman.com
    */

	public function sendEmailNotification($data, $email, $customer){

	    if($data['commercial_app'] == 0){
            $com_label = 'Non Commercial';
        }else{
            $com_label = 'Commercial';
        }

        $params = array(
            'order_id' => $data['order_id'],

            /**** Vehicle info *****/

            'year' => $data['year'],
            'make' => $data['make'],
            'model' => $data['model'],
            'engine' => $data['engine'],
            'drive' => $data['drive'],
            'vehicle_notes' => $data['vehicle_notes'],
            'vin' => $data['vin'],
            'mileage' => $data['mileage'],
            'commercial_app' => $com_label,

            /**** Order Details ****/
            'product_name' => $data['product_name'],
            'partnum' => $data['partnum'],
            'family' => $data['family'],
            'po' => $data['po'],
            'claim' => $data['claim'],
            'ro' => $data['ro'],
            'end_username'=> $data['end_username'],
            'ship_from' => $data['ship_from'],
            'ship_time' => $data['ship_time'],

            /*** Sold To  ***/
            'so_cust_name' => $data['so_cust_name'],
            'so_cont_name' => $data['so_cont_name'],
            'so_phone' => $data['so_phone'],


            /***** Ship To ****/

            'st_cust_name' => $data['st_cust_name'],
            'st_cont_name' => $data['st_cont_name'],
            'st_phone' => $data['st_phone'],
            'st_addr1' => $data['st_addr1'],
            'st_city' => $data['st_city'],
            'st_state' => $data['st_state'],
            'st_zip' => $data['st_zip'],

            /***** Price ****/
            'unit_amount' => $data['unit_amount'],
            'tax_percent' => $data['tax_percent'],
            'core_amount' => $data['core_amount'],
            'shipping_amount' => $data['shipping_amount'],
            'msrp_amount' => $data['msrp_amount'],
            'fluid_total' => $data['fluid_amount'],
            'tax_total' => $data['tax_total'],
            'total_amount' => $data['total_amount'],

             /***** Warranty ****/
             'warranty_text'=> $data['warranty_text'],
             'order_notes'=> $data['order_notes'],

            'gate_req' => $data['gate_req'],
            'tag' => $data['tag'],

            'search' => $data['search_result']



        );

        Mage::getModel('order/email')->sendEmail(

            'reman_order_confirmation',

            array(
                'name' => 'ETEREMAN',
                'email' => 'noreply@etereman.com'
            ),

            $email,
            $customer,
            'Order Confirmation',
            $params
        );

        /* Send Email to WebOrders*/

        Mage::getModel('order/email')->sendEmail(

               'reman_order_confirmation',

                array(
                    'name' => 'ETEREMAN',
                    'email' => 'noreply@etereman.com'
                ),

                'weborders@etereman.com',

               //'hybridtestmail@gmail.com',
                 $customer,
                'Order Confirmation',
                $params
        );


	}
}
