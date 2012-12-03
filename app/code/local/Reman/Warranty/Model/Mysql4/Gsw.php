<?php
class Reman_Warranty_Model_Mysql4_Gsw extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('warranty/gsw',	'gsw_id');
		$this->_isPkAutoIncrement = false;
	}
	
	public function trancateTable() {
		$this->_getWriteAdapter()->query("TRUNCATE TABLE `reman_gsw`");
	}
}
