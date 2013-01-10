<?php
/**
 * Model for Applic table
 *
 * @category    Reman
 * @package     Reman_Sync
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Sync_Model_Applic extends Reman_Sync_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('sync/applic');
	}
	
	// override
	protected function _parseItem( $item )
	{
		$this->setData(
			array(
				'vehicle_id'		=>		$item[0],
				'group_number'		=>		$item[2],
				'subgroup'			=>		$item[5],
				'menu_heading'		=>		$item[6],
				'applic'			=>		$item[4],
				'part_number'		=>		$item[7]
			)		    	
		);
		
		$this->save();		
	}
	
	// override
	public function syncData()
	{
		$this->getResource()->trancateTable();	
		$this->_loadFile( 'import/applic.csv' );
	}
	
	/** 
	 * SQL query for select year production from reman_model table
	*/
	public function loadProductId($vehicle_id){
		return $this->getResource()->loadProductId($vehicle_id);
    }
	/**
	 * Load Product object
	 * @return object
	*/
	public function loadProduct($applic_id){
		return $this->getResource()->loadProduct($applic_id);
    }
}
