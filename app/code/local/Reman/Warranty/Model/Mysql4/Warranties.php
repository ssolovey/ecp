<?php
class Reman_Warranty_Model_Mysql4_Warranties extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('warranty/warranties',	'warranty_id');
	}
}
