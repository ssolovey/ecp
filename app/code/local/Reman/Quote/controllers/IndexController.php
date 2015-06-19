<?php
/**
 * Quote IndexController
 * Main Controller for Quick Quote Page BAckEnd Logic
 * DB reauest for Parts Models, Groups
 * Shipping Estimation Service Call
 * @category    Reman
 * @package     Reman_Quote
 * @author		Igor Zhavoronkin (zhavoronkin.i@gmail.com)
 */
class Reman_Quote_IndexController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {
        
		//Gerate random qoute ID in order to block history back button from quote module
		Mage::getSingleton('core/session')->setPid(rand(10,100));

		$this->loadLayout();  //This function read all layout files and loads them in memory
        $this->renderLayout(); //This function processes and displays all layout phtml and php files.


        // Quote Logger
        // Mage::getModel('quote/log')->send( $year, $make, $model, $applic );
    }
	
	public function ajaxAction(){
		// parse request data
		$request = $this->getRequest()->getPost();
		
		// If User Session expired redirect to login page
		if(!Mage::getSingleton('customer/session')->isLoggedIn()){
			
			// value for MSRP and CORE prices
			$check_session = array(
				"end_session" => true
			);	
			
			echo json_encode($check_session);	
			
			die;	
				
		}
		/** Quote Step 2 - GET Models */
		if($request['step'] == 2)
		{
			
			$result_st2 = Mage::getModel('sync/model')->loadModel($request['id'],$request['year']);
			
			//return php array in json 	
			echo json_encode($result_st2);
			
		}
		/** Quote Step 3 - Search for Product (Product Groups) */
		if($request['step'] == 3)
		{
			
			
			$result_st3 = Mage::getModel('sync/applic')->loadProductId($request['id'],$request['category']);
			
			echo json_encode($result_st3);
		
		}

		// close connection
		die;
	}
	/** 
	 *	Load Only Custom product page for Quick Quote Block
	*/
	public function productAction(){
		// parse request data
		$request = $this->getRequest()->getPost();

        /**
         * PHP Session Locks – Prevent Blocking Requests
         *
         */
        // start the session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // close the session
        session_write_close();

        /**
		 *Log Search Results
		*/
		//Mage::getModel('quote/log')->send( $request['year'], $request['make'], $request['model'], $request['applic'] , $request['partnum']);


        if(array_key_exists('sku', $request)){


            /* Load Current selected product object*/
            $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $request['sku']);


            /* In  case of wrong sku number or sku number is not found in data */

            if($product){
                        /* Get Product ID*/
                        $productId = $product->getId();
                        /* Init Product Object*/
                        Mage::helper('catalog/product')->initProduct($productId, $this);

                        /* Render FrontEnd */
                        $this->loadLayout();

                        //This function processes and displays all layout phtml and php files.
                        $this->renderLayout();
            }else{

                echo 'no sku';


            }





        }else{


            // get product object from catalog according to product ID
            $result_st4 = Mage::getModel('sync/applic')->loadProduct($request['id']);

            if($result_st4){

                $sku = $result_st4->getSku();

                $family = $result_st4->getData('parts_family');

                /* Load Current selected product object*/
                $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
                /* Get Product ID*/
                $productId = $product->getId();
                /* Init Product Object*/
                Mage::helper('catalog/product')->initProduct($productId, $this);

                /* Render FrontEnd */
                $this->loadLayout();

                //This function processes and displays all layout phtml and php files.
                $this->renderLayout();


            }else {



                echo 'no sku';



            }


        }

	}



	/** 
	  * Load Inventory info block page for Quick Quote Block
	*/
	public function inventAction(){

        // parse request data
        $request = $this->getRequest()->getPost();
        /**
         * PHP Session Locks – Prevent Blocking Requests
         *
         */
        // start the session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // close the session
        session_write_close();

        if(array_key_exists('sku', $request)){

            $sku = $request['sku'];

            $this->loadLayout('invent');

            /* Get block reference*/
            $block = Mage::app()->getLayout()->getBlock('quote');

            /* Set current SKU value*/
            $block->setSku($sku);
            /* Set current Family value*/

            //This function processes and displays all layout phtml and php files.
            $this->renderLayout();

        }else{
            // get product object from catalog according to product ID
            $result_st4 = Mage::getModel('sync/applic')->loadProduct($request['id']);

            if($result_st4){

                $sku = $result_st4->getSku();

                $this->loadLayout('invent');

                /* Get block reference*/
                $block = Mage::app()->getLayout()->getBlock('quote');

                /* Set current SKU value*/
                $block->setSku($sku);
                /* Set current Family value*/

                //This function processes and displays all layout phtml and php files.
                $this->renderLayout();


            }else{

                echo 'no sku';

            }
        }




	}
	/** 
	  * Load Inventory info block page for Quick Quote Block
	*/
	public function shippingAction(){

        /**
         * PHP Session Locks – Prevent Blocking Requests
         *
         */
        // start the session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // close the session
        session_write_close();

		$this->loadLayout('shipping'); 
        //This function processes and displays all layout phtml and php files.
		$this->renderLayout(); 
	
	}

    /* Init Shipping Service*/

    protected $_shippingClient;

    protected function _construct()
    {
        parent::_construct();

        $this->_shippingClient = new SoapClient("http://services.afs.net/rate/rateservice_v2.asmx?WSDL");
    }

	/**
	  * Call For ThirdPart Shipping Service
	*/
	public function estimateshippingAction(){
		// parse request data
		$request = $this->getRequest()->getPost();

        /**
         * PHP Session Locks – Prevent Blocking Requests
         *
         */
        // start the session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // close the session
        session_write_close();

        $params = array(
               "clientId" => "1481",
               "carrierId" => "0",
               "shipmentDate" => date("m.d.y"),
               "transportationMode" => "T",
               "originPostalCode" => $request['stock'],
               "destinationPostalCode" => $request['destzip'],
               "rateItems" => "50|175",
               "rateAccessorials" => "",
               "rateIncrease" => "0",
               "userName"      => "eteweb",
               "password"  => "afsrates"
            );

        $data =  $this->_shippingClient->GetLTLRateQuoteAdvanced($params);

        $response = simplexml_load_string($data->GetLTLRateQuoteAdvancedResult);


        echo json_encode($response);
	}
	
	/** 
		Load Order page 
	*/
	public function orderAction(){



		$this->loadLayout('order');

        //This function processes and displays all layout phtml and php files.
		$this->renderLayout(); 
	
	}
	/**
	  * Submit Order Action
	  *
	*/
	public function ordersubmitAction(){
		// get request POST data
		$request = $this->getRequest()->getPost();
		
		//customer ID
		$customer_id =  Mage::getSingleton('customer/session')->getCustomer()->getId();	
		// Parse submited form DATA
		parse_str($request['data'],$array);
		
		echo Mage::getModel('order/order')->createOrder($customer_id, $array , false );
	}
	
}
