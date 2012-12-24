<?php
class Reman_Company_Model_Mysql4_Company extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('company/company',	'company_id');
		$this->_isPkAutoIncrement = false;
	}
}
