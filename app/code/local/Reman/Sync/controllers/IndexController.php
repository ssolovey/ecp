<?php
class Reman_Sync_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		echo 'Reman sync module.</br>';
		//Mage::getModel('sync/parts')->loadInventoryData();
		//Mage::getModel('sync/parts')->loadProductsData();
	}
}