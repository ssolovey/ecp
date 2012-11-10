<?php

class Reman_Sync_Model_Mysql4_Make_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	public function _construct()
	{
		$this->_init('sync/make');
	}
}
