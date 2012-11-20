<?php
class Reman_Sync_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		echo 'Reman sync module.</br>';
		
		//Mage::getModel('sync/make')->syncData();
		//Mage::getModel('sync/model')->syncData();
		//Mage::getModel('sync/applic')->syncData();
	}
}
