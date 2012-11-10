<?php
class Reman_Sync_Model_Mysql4_Make extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('sync/make',	'make_id');
		$this->_isPkAutoIncrement = false;
	}
	
	/** 
	 * SQL query for select makers names from reman_make table
	*/
	public function loadMake(){
		$select = $this->_getReadAdapter()->select()->from('reman_make',array('make','make_id')); // form sql query "SELECT make,make_id FROM reman_make"
		$result = $this->_getReadAdapter()->fetchAll($select); // run sql query
		return $result; // return result
    }
}
