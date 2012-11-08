<?php
class Reman_Sync_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		echo 'Sync Module Initiated</br>';
		
		
		$data_make		= Mage::getModel('sync/make');
		$data_model		= Mage::getModel('sync/model');
		$data_applic	= Mage::getModel('sync/applic');
		
		echo 'Make: ';
		echo $data_make->getCollection()->count();
		echo '</br>';
		
		echo 'Model: ';
		echo $data_model->getCollection()->count();
		echo '</br>';
		
		echo 'Applic: ';
		echo $data_applic->getCollection()->count();
		echo '</br>';
	}
}
