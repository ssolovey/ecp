<?php
class Reman_Sync_Model_Mysql4_Gsp extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('sync/gsp',	'gsp_id');
		$this->_isPkAutoIncrement = false;
	}
	
	public function trancateTable() {
		$this->_getWriteAdapter()->query("TRUNCATE TABLE `reman_gsp`");
	}
}
