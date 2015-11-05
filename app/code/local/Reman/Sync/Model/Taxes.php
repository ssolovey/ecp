<?php
/**
 * Model for Taxes table
 *
 * @category    Reman
 * @package     Reman_Sync
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Sync_Model_Taxes extends Reman_Sync_Model_Abstract
{
	
	// model log name
	//protected $_logid = 'taxes';
	
	public function _construct()
	{
		parent::_construct();
		$this->_init('sync/taxes');  
	}

	
	/** 
	 * SQL query for select TAX Value from reman_taxes table
	*/
	public function getTaxValue($id){
		return $this->getResource()->getTaxValue($id);
    }
	
}
