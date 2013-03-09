<?php
/**
 * Reman Warranty Gsw Model Collection
 *
 * @category    Remah
 * @package     Reman_Warranty
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Sync_Model_Mysql4_Gsw_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	public function _construct()
	{
		$this->_init('sync/gsw');
	}
}
