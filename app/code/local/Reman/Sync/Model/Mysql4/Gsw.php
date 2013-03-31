<?php
/**
 * Reman Warranty Gsw Model Mysql4
 *
 * @category    Remah
 * @package     Reman_Warranty
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Sync_Model_Mysql4_Gsw extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('sync/gsw',	'gsw_id');
		$this->_isPkAutoIncrement = false;
	}
	
	public function trancateTable() {
		$this->_getWriteAdapter()->query("TRUNCATE TABLE `reman_gsw`");
	}
	
	
	/** 
	 * SQL query for GSW Warranty  ID
	*/
	public function loadWarranryID($gsw_link, $category){
		
		$where = $this->_getReadAdapter()->quoteInto("customer_id=? AND ", $gsw_link).$this->_getReadAdapter()->quoteInto("type=? ", $category);
		
		$select = $this->_getReadAdapter()->select()->from('reman_gsw','warranty_id')->where($where);
		
		$result = $this->_getReadAdapter()->fetchAll($select); // run sql query
		
		// return result
		return $result; 
    }
	
	
}
