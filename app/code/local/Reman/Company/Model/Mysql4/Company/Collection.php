<?php
class Reman_Company_Model_Mysql4_Company_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	public function _construct()
	{
		$this->_init('company/company');
	}
}
