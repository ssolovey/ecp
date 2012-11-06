<?php
class Reman_Sync_Model_Mysql4_Make extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('sync/make',	'make_id');
	}
}
