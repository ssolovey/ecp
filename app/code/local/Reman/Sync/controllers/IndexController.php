<?php
class Reman_Sync_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		echo 'Reman sync module.</br>';
		Mage::getModel('sync/applic')->syncData();
		//Mage::getModel('sync/parts')->loadInventoryData();
		//Mage::getModel('sync/parts')->loadProductsData();
		//Mage::getModel('sync/gsw')->loadGswData();
		//Mage::getModel('sync/gsp')->loadGspData();
		//Mage::getModel('sync/applic')->syncData();
		//Mage::getModel('sync/profile')->test();
		//Mage::getModel('sync/order')->syncData();
		/*
		$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
		$setup->removeAttribute('customer', 'state');
		*/
		//$order = Mage::getModel('sales/order')->loadByAttribute('customer_email','AWS.PARTS.UNIT@US.THEWG.COM');
				
		//$order = Mage::getModel('sales/order')->loadByIncrementId('100000051');//->getItemsCollection();
		
		//print_r( $order );
	}
}