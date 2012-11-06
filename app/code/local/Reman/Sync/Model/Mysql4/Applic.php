<?php
class Reman_Sync_Model_Mysql4_Applic extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('sync/applic',	'applic_id');
	}
}
