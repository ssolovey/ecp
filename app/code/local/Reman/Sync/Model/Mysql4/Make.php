<?php
class Reman_Sync_Model_Mysql4_Make extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('sync/make',	'make_id');
	}
	
	/** 
	 * SQL query for select makers names from reman_make table
	*/
	public function loadMake(){
        $select = $this->_getReadAdapter()->select()->from('reman_make',array('make')); // form sql query "SELECT make FROM reman_make"
		$result = $this->_getReadAdapter()->fetchCol($select); // run sql query
		return $result; // return result
    }
}
