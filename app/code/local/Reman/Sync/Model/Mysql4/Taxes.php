<?php
/**
 * Sync Taxes Mysql4
 *
 * @category    Reman
 * @package     Reman_Sync
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Sync_Model_Mysql4_Taxes extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('sync/taxes',	'tax_id');
		$this->_isPkAutoIncrement = false;
	}
	
	public function trancateTable() {
		$this->_getWriteAdapter()->query("TRUNCATE TABLE `reman_taxes`");
	}
	
	
	/** 
	 * SQL query for select TAX Value from reman_taxes table
	*/
	public function getTaxValue($id){
		
		$where = $this->_getReadAdapter()->quoteInto("tax_id=?", $id);
		
		$select = $this->_getReadAdapter()->select()->from('reman_taxes', 'value')->where($where);
		
		$result = $this->_getReadAdapter()->fetchAll($select); // run sql query
		
		// return result
		return $result; 
    }
	
	
}
