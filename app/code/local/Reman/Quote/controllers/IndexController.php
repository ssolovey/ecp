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
		
		// get years accprding to selected make
		$result = Mage::getModel('sync/model')->loadModel($request['id'],$request['year']);
		
		
		//return php array in json 	
		echo json_encode($result);
		// close connection
		die;
	}
	
}
