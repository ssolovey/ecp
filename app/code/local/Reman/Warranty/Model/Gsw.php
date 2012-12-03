<?php
class Reman_Warranty_Model_Gsw extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('warranty/gsw');
	}
	
}
