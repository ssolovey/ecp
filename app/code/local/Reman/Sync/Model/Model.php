<?php
class Reman_Sync_Model_Model extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('sync/model');
	}
	
	/** 
	 * SQL query for select year production from reman_model table
	*/
	public function loadModel($make_id,$year){
		return $this->getResource()->loadModel($make_id,$year);
    }
	
}
