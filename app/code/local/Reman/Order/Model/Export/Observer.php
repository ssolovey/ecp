<?php
/**
 * Export Order Observer
 * should call after new order created
 *
 * @category    Reman
 * @package     Reman_Order
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Order_Model_Export_Observer
{
	public function __construct()
	{
	
	}
	
	/**
	 * Exports new orders to an cdv file
	 *
	 * @param Varien_Event_Observer $observer
	 * @return Feed_Sales_Model_Order_Observer
	 */
	public function export_new_order($observer)
	{
		Mage::log("reached export_new_order");
		try
		{
			$dumpFile = fopen('export/testdump', 'w+');
			fwrite($dumpFile, 'this is a test!');
		}
		catch (Exception $e)
		{
			Mage::log("order export failed.\n");
		}
		return $this;
	}
}