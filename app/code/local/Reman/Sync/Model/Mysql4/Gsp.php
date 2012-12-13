<?php
class Reman_Sync_Model_Mysql4_Gsp extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('sync/gsp',	'gsp_id');
		$this->_isPkAutoIncrement = false;
	}
	
	public function trancateTable() {
		$this->_getWriteAdapter()->query("TRUNCATE TABLE `reman_gsp`");
	}
	
	/**
	 * Load Customer Special Price
	 *
	 */
	public function loadSp($sku, $splink)
	{
		$where = $this->_getReadAdapter()->quoteInto("partnum=? AND ", $sku).$this->_getReadAdapter()->quoteInto("customer_id=?", $splink);
		
		$select = $this->_getReadAdapter()->select()->from('reman_gsp',array('price','core'))->where($where);
		
		$spPrice = $this->_getReadAdapter()->fetchAll($select); // run sql query
		
		// return result
		return $spPrice;
	
	}
	
}
