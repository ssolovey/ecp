<?php
/**
 * Quote IndexController
 *
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
		
		if($request['step'] == 2)
		{
			// get years accprding to selected make
			$result_st2 = Mage::getModel('sync/model')->loadModel($request['id'],$request['year']);
			
			//return php array in json 	
			echo json_encode($result_st2);
			
		}
		
		if($request['step'] == 3)
		{
			
			// get years accprding to selected make
			$result_st3 = Mage::getModel('sync/applic')->loadProductId($request['id'],$request['category']);
			
			echo json_encode($result_st3);
		
		}
		
		if($request['step'] == 4)
		{
			// get product object from catalog according to product ID
			$result_st4 = Mage::getModel('sync/applic')->loadProduct($request['id']);
			$productObj = new stdClass();
			if($result_st4){
				$productObj->sku = $result_st4->getSku();
				$productObj->family = $result_st4->getData('parts_family');
				/*if($productObj->sku[0] != $request['category'] ){
					$productObj->sku = "";
				}*/
			}else{
				$productObj->sku = "";
			}
			
			echo json_encode($productObj);
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
		 *Log Search Results
		*/
		Mage::getModel('quote/log')->send( $request['year'], $request['make'], $request['model'], $request['applic'] , $request['partnum']);
		
		/* Load Current selected product object*/
		$product = Mage::getModel('catalog/product')->loadByAttribute('sku',$request['partnum']);
		/* Get Product ID*/
		$productId = $product->getId();
		/* Init Product Object*/
		Mage::helper('catalog/product')->initProduct($productId, $this);
		/* Render FrontEnd */
		$this->loadLayout();
        //This function processes and displays all layout phtml and php files.
		$this->renderLayout(); 
	
	}
	
	public function inventAction(){
		/** 
			Load Inventory info block page for Quick Quote Block
		*/
		$this->loadLayout('invent'); 
        //This function processes and displays all layout phtml and php files.
		$this->renderLayout(); 
	
	}
	
	public function shippingAction(){
		/** 
			Load Inventory info block page for Quick Quote Block
		*/
		$this->loadLayout('shipping'); 
        //This function processes and displays all layout phtml and php files.
		$this->renderLayout(); 
	
	}
	
	public function estimateshippingAction(){
		// parse request data
		$request = $this->getRequest()->getPost();
		$array = array();
		$client = new SoapClient("http://services.beta.afs.net/Rate/RateService_v2.asmx?WSDL"); 
	
		  foreach($request['stocks'] as $value){
				$params = array(
					   "clientId" => "1481",
					   "carrierId" => "0",
					   "shipmentDate" => date("m.d.y"),
					   "transportationMode" => "T",
					   "originPostalCode" => $value,
					   "destinationPostalCode" => $request['destzip'],
					   "rateItems" => "50|175",
					   "rateAccessorials" => "",
					   "rateIncrease" => "0",
					   "userName"      => "eteweb", 
					   "password"  => "afsrates"
					);
			
				$data = $client->GetLTLRateQuoteAdvanced($params);
			
				$response = simplexml_load_string($data->GetLTLRateQuoteAdvancedResult);
				
				$array[$value] =  $response;
		  }
	
		
		echo json_encode($array);	
	
	}
	
	
	public function orderAction(){
		/** 
			Load Order page 
		*/
		$this->loadLayout('order');   
        //This function processes and displays all layout phtml and php files.
		$this->renderLayout(); 
	
	}
	
	public function ordersubmitAction(){
	
		$request = $this->getRequest()->getPost();
		
		var_dump($request);
	}
	
	
}
