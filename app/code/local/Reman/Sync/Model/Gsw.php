<?php
/**
 * Model for GSW table
 *
 * @category    Reman
 * @package     Reman_Sync
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Sync_Model_Gsw extends Reman_Sync_Model_Abstract
{	
	// model log name
	protected $_logid = 'gsw';
	
	public function _construct()
	{
		parent::_construct();
		$this->_init('sync/gsw'); 
	}
	
	// override
	protected function _parseItem( $item )
	{
        if (
            !isset($item[0])
            || !isset($item[1])
            || !isset($item[4])
        ) {
            throw new Exception('Broken CSV file format.');
        }

		$this->setData(
			array(
				'customer_id'	=>		$item[0],
				'type'			=>		$item[1],
				'warranty_id'	=>		$item[4]
			)		    	
		);
		$this->save();

        return true;
	}
	
	// override
	protected function _beforeParseFile() {
		$this->getResource()->trancateTable();	
	}
	
	// override
	public function syncData()
	{
		$this->_loadFile( 'GSW.TXT' );
	}
	
	
	public function loadWarranryID($gsw_link, $category){
		return $this->getResource()->loadWarranryID($gsw_link, $category);
	}
	
}
