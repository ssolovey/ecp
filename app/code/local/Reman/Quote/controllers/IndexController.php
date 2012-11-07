<?php
class Reman_Quote_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();  //This function read all layout files and loads them in memory
        $this->renderLayout(); //This function processes and displays all layout phtml and php files.
		
		/* Init templates data*/
		//$data_make		= Mage::getModel('sync/make');
		//$data_model		= Mage::getModel('sync/model');
		//$data_applic	= Mage::getModel('sync/applic');
		
    }
}
