<?php
/**
 * @category    Reman
 * @package     Reman_Order
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Order_Model_Mysql4_Order extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('order/order',	'order_id');
		$this->_isPkAutoIncrement = false;
	}
}
