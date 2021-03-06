<?php
/**
 * Sync Make Mysql4
 *
 * @category    Reman
 * @package     Reman_Sync
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Sync_Model_Mysql4_Make extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('sync/make',	'make_id');
		$this->_isPkAutoIncrement = false;
	}
	
	public function trancateTable() {
		$this->_getWriteAdapter()->query("TRUNCATE TABLE `reman_make`");
	}
	
	/** 
	 * SQL query for select makers names from reman_make table
	*/
	public function loadMake(){
		$select = $this->_getReadAdapter()->select()->from('reman_make',array('make','make_id','start_year','end_year'))->order('make'); // form sql query "SELECT make,make_id FROM reman_make"
		$result = $this->_getReadAdapter()->fetchAll($select); // run sql query
		return $result; // return result
    }
}
