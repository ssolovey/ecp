<?php
/**
 * Sync Log Mysql4
 *
 * @category    Reman
 * @package     Reman_Sync
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Sync_Model_Mysql4_Log extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('sync/log',	'model_id');
		$this->_isPkAutoIncrement = false;
	}
}
