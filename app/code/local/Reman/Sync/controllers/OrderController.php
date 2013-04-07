<?php
class Reman_Sync_OrderController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{			
		Mage::getModel('sync/order')->test();
	}
}