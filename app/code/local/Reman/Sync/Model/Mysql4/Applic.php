<?php
class Reman_Sync_Model_Mysql4_Applic extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('sync/applic',	'applic_id');
		$this->_isPkAutoIncrement = false;
	}
	
	
	/** 
	 * SQL query for select product ID from reman_applic table
	*/
	public function loadProductId($vehicle_id){
				
		$where = $this->_getReadAdapter()->quoteInto("vehicle_id=? AND ", $vehicle_id).$this->_getReadAdapter()->quoteInto("subgroup = 0");
		
		$select = $this->_getReadAdapter()->select()->from('reman_applic',array('group','menu_heading','applic','applic_id'))->where($where);
		
		$result = $this->_getReadAdapter()->fetchAll($select); // run sql query

		// return result
		return $result; 
    }
	
	public function loadProduct ($applic_id)
	{
		
		$where = $this->_getReadAdapter()->quoteInto("applic_id=?", $applic_id);
		
		$select = $this->_getReadAdapter()->select()->from('reman_applic',array('part_number'))->where($where);
		
		$result = $this->_getReadAdapter()->fetchAll($select); // run sql query

		// return result
		return $result; 
	
	}
	
}
