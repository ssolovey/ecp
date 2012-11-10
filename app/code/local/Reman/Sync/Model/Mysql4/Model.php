<?php
class Reman_Sync_Model_Mysql4_Model extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('sync/model',	'vehicle_id');
		$this->_isPkAutoIncrement = false;
	}
}
