<?php
class Reman_Quote_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();  //This function read all layout files and loads them in memory
        $this->renderLayout(); //This function processes and displays all layout phtml and php files.
    }
	
	public function ajaxAction(){
		// parse request data
		$request = $this->getRequest()->getPost();
		
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
			$result_st3 = Mage::getModel('sync/applic')->loadProductId($request['id']);
			
			echo json_encode($result_st3);
		
		}
		
		if($request['step'] == 4)
		{
		
			// get years accprding to selected make
			$result_st4 = Mage::getModel('sync/applic')->loadProduct($request['id']);
			if($result_st4 != null){
			$productObj = new stdClass();
				$productObj->sku = $result_st4->getSku();
				$productObj->error = false;
			
				echo json_encode($productObj);
			}else{
				$productError = new stdClass();
					$productError->error = true;
					$productError->message = "Not available";
				echo json_encode($productError);
			}
			
		}
		
		// close connection
		die;
	}
	
	public function productAction(){
		/** 
			Load Only Custom product page for Quick Quote Block
		*/
		$this->loadLayout('view'); 
        //This function processes and displays all layout phtml and php files.
		$this->renderLayout(); 
	
	}
	
}
