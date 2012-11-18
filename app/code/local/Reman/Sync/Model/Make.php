<?php
class Reman_Sync_Model_Make extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('sync/make');  
	}
	/**
	 * Load Make table names
	*/
	public function loadMake(){
		return $this->getResource()->loadMake();
	}
	
}
