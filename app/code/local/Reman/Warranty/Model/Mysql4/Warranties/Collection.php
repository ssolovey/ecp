<?php
/**
 * Reman Warranty Warranties Model Collection
 *
 * @category    Remah
 * @package     Reman_Warranty
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Warranty_Model_Mysql4_Warranties_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	public function _construct()
	{
		$this->_init('warranty/warranties');
	}
}
