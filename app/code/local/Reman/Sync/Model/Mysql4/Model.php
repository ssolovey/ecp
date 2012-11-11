<?php
class Reman_Sync_Model_Mysql4_Model extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('sync/model',	'vehicle_id');
		$this->_isPkAutoIncrement = false;
	}
	
	
	/** 
	 * SQL query for select year production from reman_model table
	*/
	public function loadModel($make_id,$year){
		//where condition
		$where = $this->_getReadAdapter()->quoteInto("make_id=? AND ", $make_id).$this->_getReadAdapter()->quoteInto("year=?", $year);
		//sql select query
		$select = $this->_getReadAdapter()->select()->from('reman_model',array('model'))->where($where); // form sql query "SELECT year FROM reman_model"
		//fetch result
		$result = $this->_getReadAdapter()->fetchCol($select); // run sql query
		// return result
		return $result; 
    }
	
}
