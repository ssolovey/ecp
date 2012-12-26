<?php
/**
 * Reman Warranty Gsw Model
 *
 * Model for Reman General Special Warranties data
 *
 * @category    Remah
 * @package     Reman_Warranty
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Warranty_Model_Gsw extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('warranty/gsw');
	}
	
}
