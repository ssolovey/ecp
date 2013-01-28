<?php
/**
 * Profile IndexController 
 *
 * @category    Reman
 * @package     Reman_Profile
 * @author		Igor Zhavoronkin (zhavoronkin.i@gmail.com)
 */
class Reman_Profile_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();  //This function read all layout files and loads them in memory
        $this->renderLayout(); //This function processes and displays all layout phtml and php files.
    }
	
	/** 
	  *Enable Customers's profile
	*/
	public function enableAccountAction() {
		//get Selected User ID
		$request = $this->getRequest()->getParams();	
		// Load Users Object Data
		$customer = Mage::getModel("customer/customer")->load($request['id']);
		//  Set SALES Group to Customer Profile
		$customer->setGroupId(Mage::helper('customer')->getSalesGroupId());
		$customer->save();
		// redirect to profile page
		Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account/#users'));
	}
	/** 
	  *Disable Customers's profile
	*/
	public function disableAccountAction() {
		//get Selected User ID
		$request = $this->getRequest()->getParams();	
		// Load Users Object Data
		$customer = Mage::getModel("customer/customer")->load($request['id']);
		//  Set DIsabled Group to Customer Profile
		$customer->setGroupId(7); // Disabled Group ID = 7
		$customer->save();
		// redirect to profile page
		Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account/#users'));
	}
	
}
