<?php
class Reman_Sync_Model_Model extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('sync/model');
	}
}
