<?php
class Reman_Sync_Model_Applic extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('sync/applic');       
	}
	
	/** 
	 * SQL query for select year production from reman_model table
	*/
	public function loadProductId($vehicle_id){
		return $this->getResource()->loadProductId($vehicle_id);
    }
	
	public function loadProduct($applic_id){
		return $this->getResource()->loadProduct($applic_id);
    }
	
}
