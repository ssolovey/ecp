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
        /*if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // close the session
        session_write_close();*/

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
        /*if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // close the session
        session_write_close();*/

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
        /*if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // close the session
        session_write_close();*/

		$this->loadLayout('shipping'); 
        //This function processes and displays all layout phtml and php files.
		$this->renderLayout(); 
	
	}

    /* New Shipping service*/

    protected $_newShippingClient;

    protected function _construct()
    {
        parent::_construct();

        $this->_newShippingClient = new SoapClient("http://webservice.ratelinxapp.com/ratelinxwebservice/ratelinx.asmx?WSDL");
    }


    /**
     * Call For ThirdPart Shipping Service
     */
    public function estimateshippingAction(){
        // parse request data
        $request = $this->getRequest()->getPost();

        // Customer info
        //Load Current Logged Customer Object
        $customer_id = Mage::getSingleton('customer/session')->getCustomer()->getId();
        $customer = Mage::getModel('customer/customer')->load($customer_id);


        // Load Product Object
        $_product =  Mage::getSingleton('core/session')->getData('selectedProduct');

        $invlabel = $request['invlabel'];

        if((int)$_product->getData($invlabel)  == 0 && $request['stock'] != 3237 ) {

            $tax_value = Mage::getModel('sync/taxes')->getTaxValue($request['destzip']);

            $response = array(
                "data" =>  "",
                "stock" => "",
                "tax"=> $tax_value
            );

            echo json_encode($response);

        }else{

            $this->shippingServiceRequest($request,$customer);

        }

    }



    public function shippingServiceRequest ($request,$customer){

        $params = array(

            "ClientID" => "ETE",
            "Username" => "Webservice",
            "Password" => "eteweb1",
            "BillingType" => "1",
            "LocationID" => $request['stock'],
            "ShipDate" => date("m.d.y"),
            "ExpectedCount"=> "1",


            "ShipVia" => array(

                "ShipVia" => "LTL",
            ),

            "Addresses" => array(

                "Address" => array(

                    "Account"=>"",
                    "Address1"=>Mage::helper('company')->getCompanyAddress(),
                    "Address2"=>Mage::helper('company')->getCompanyAddress2(),
                    "AddressType"=>"SHIPTO",
                    "Attention"=>$customer->getFirstname().' '.$customer->getLastname(),
                    "City"=>$request['city'],
                    "Country"=>"US",
                    "Phone"=>$customer->phone,
                    "Name"=>Mage::helper('company')->getCompanyName(),
                    "State"=>$request['state'],
                    "Zip"=>$request['destzip']

                )
            ),

            "SpecialServices" => array(

                "SpecialService" => array(

                    "ID" => "",
                    "Value"=>""
                )

            ),

            "Packages"=>array(

                "Package"=>array(

                    "Height"=>"24.0",
                    "Length"=>"48.0",
                    "Width"=>"24.0",
                    "ActualWeight"=>"175.0",
                    "SpecialServices"=> array(

                        "SpecialService"=>array(

                            "ID" => "",
                            "Value"=>""

                        )

                    )
                )

            ),

            "BOLDetails"=>array(

                "BOLDetail"=>array(

                    "Class"=>"85",
                    "Pallets"=>"0",
                    "Pieces"=>"1",
                    "Height"=>"24.0",
                    "Length"=>"48.0",
                    "Width"=>"24.0",
                    "Weight"=>"175.0"
                )

            )

        );


        $xml = new SimpleXMLElement('<RateLinx ver="1.0"/>');

        $this->to_xml($xml, $params);

        $xmlString = $xml->asXML();

        $client = new Varien_Http_Client("http://webservice.ratelinxapp.com/ratelinxwebservice/ratelinx.asmx/RateShop");

        $client->setMethod(Varien_Http_Client::POST);

        $client->setParameterPost('xmlData', $xmlString);

        try{

            $response = $client->request();

            if ($response->isSuccessful()) {

                $result = simplexml_load_string($response->getBody());


                $tax_value = Mage::getModel('sync/taxes')->getTaxValue($request['destzip']);

                Mage::getSingleton('core/session')->setData('taxValue',$tax_value );

                $response = array(
                    "data" =>  $result,
                    "stock" => $request['stock'],
                    "tax"=>$tax_value
                );

                echo json_encode($response);

            }
        } catch (Exception $e) {

            echo $e;
        }

    }


    public function errorshippingAction(){

        // parse request data
        $request = $this->getRequest()->getPost();

        // Customer info
        //Load Current Logged Customer Object
        $customer_id = Mage::getSingleton('customer/session')->getCustomer()->getId();
        $customer = Mage::getModel('customer/customer')->load($customer_id);


        // Load Product Object
        $_product =  Mage::getSingleton('core/session')->getData('selectedProduct');


        $params = array(
              'date' => date('l jS \of F Y h:i:s A'),
              'customer_name' => $customer->getFirstname().' '.$customer->getLastname(),
              'part_number' => $_product->getSku(),
              'zip' => $request[zip],
              'error' => $request[error],
              'search' => $request[search],
              'message' => $request[message]
        );




        /* Send Email to WebOrders*/

                Mage::getModel('order/email')->sendEmail(

                       'reman_error_shipping',

                        array(
                            'name' => 'ETEREMAN',
                            'email' => 'noreply@etereman.com'
                        ),

                        'webcatadmin@etereman.com',
                        $customer,
                        'Shipping Error',
                        $params
                );




    }

    public function to_xml(SimpleXMLElement $object, array $data)
    {
        foreach ($data as $key => $value)
        {
            if (is_array($value))
            {
                $new_object = $object->addChild($key);
                $this->to_xml($new_object, $value);
            }
            else
            {
                $object->addChild($key, $value);
            }
        }
    }


    /**
     *
     * Calculate product price
     *
     */

    public function getPriceAction(){

        // Load Product Object
        $_product =  Mage::getSingleton('core/session')->getData('selectedProduct');

        // Current Logged Company
        $_company = Mage::helper('company')->getCustomerCompanyObject();

        // tax value
        $taxValue =  Mage::getSingleton('core/session')->getData('taxValue');

        // get request POST data
        $request = $this->getRequest()->getPost();

        /////// FLUID

        if($request[isfluid] === "true"){
            $fluid =  Mage::helper('quote')->getFluidPriceInventory($_company, $_product);
        }else{
            $fluid =  0;
        }

        $partPrice = $_product->getPrice();

        $partCorePrice = $_product->getData('parts_core_price');

        $shippingPrice = Mage::helper('company')->getCustomerShippingPrice();


        /////// TAX calculation

        $priceWithoutTax = $fluid + $partPrice + $partCorePrice + $shippingPrice;

        if(sizeof($taxValue) > 0){

            $taxPrice = ($priceWithoutTax/100) * $taxValue[0]["tax"];

            echo $priceWithoutTax + $taxPrice;

        }else{

            echo $priceWithoutTax;

        }

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
		
		if(Mage::getModel('order/order')->createOrder($customer_id, $array , false ) == "Success"){

		        $this->loadLayout('thankyou');

                //This function processes and displays all layout phtml and php files.
                $this->renderLayout();
		}else{

		    echo Mage::getModel('order/order')->createOrder($customer_id, $array , false );

		}


	}


	
}
