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
	// model log name
	protected $_logid = 'applic';
	
	public function _construct()
	{
		parent::_construct();
		$this->_init('sync/applic');
	}
	
	// override
	protected function _parseItem( $item )
	{
        if (
            !isset($item[0])
            || !isset($item[2])
            || !isset($item[5])
            || !isset($item[6])
            || !isset($item[4])
            || !isset($item[7])
            || !isset($item[1])
            || !isset($item[8])
        ) {
            throw new Exception('Broken CSV file format.');
        }

		$this->setData(
			array(
				'vehicle_id'		=>		$item[0],
				'group_number'		=>		$item[2],
				'subgroup'			=>		$item[5],
				'menu_heading'		=>		$item[6],
				'applic'			=>		$item[4],
				'part_number'		=>		$item[7],
				'part_type'			=>		$item[1],
				'engine_size'		=>		$item[8]
			)		    	
		);
		
		$this->save();		
	}
	
	// override
	protected function _beforeParseFile() {
		$this->getResource()->trancateTable();	
	}
	
	// override
	public function syncData()
	{
		$this->_loadFile( 'APPLIC.TXT' );
	}
	
	/** 
	 * SQL query for select year production from reman_model table
	*/
	public function loadProductId($vehicle_id, $category){
		return $this->getResource()->loadProductId($vehicle_id, $category);
    }
	/**
	 * Load Product object
	 * @return object
	*/
	public function loadProduct($applic_id){
		return $this->getResource()->loadProduct($applic_id);
    }

    /**
     * Get Applic Id by SKU
     * @return string
     */
    public function getApplicIdBySku($sku){

        return $this->getResource()->getApplicIdBySku($sku);
    }

	/*
	 * Get Product Engine Value
	 * @return string 
	*/
	public function getProductEngine ($applic_id){
		return $this->getResource()->getProductEngine($applic_id);
	}
}
