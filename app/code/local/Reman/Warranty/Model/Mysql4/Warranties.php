<?php
/**
 * Reman Warranty Gsw Warenties
 *
 * @category    Remah
 * @package     Reman_Warranty
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Warranty_Model_Mysql4_Warranties extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('warranty/warranties',	'warranty_id');
		
		$this->_isPkAutoIncrement = false;
	}
	
	public function trancateTable() {
		$this->_getWriteAdapter()->query("TRUNCATE TABLE `reman_warranties`");
	}
}
