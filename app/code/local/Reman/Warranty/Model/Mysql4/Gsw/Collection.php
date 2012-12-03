<?php

class Reman_Warranty_Model_Mysql4_Gsw_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	public function _construct()
	{
		$this->_init('warranty/gsw');
	}
}
