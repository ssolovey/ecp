<?php
/**
 * Model for Model table
 *
 * @category    Reman
 * @package     Reman_Sync
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Sync_Model_Model extends Reman_Sync_Model_Abstract
{
	// model log name
	protected $_logid = 'model';
	
	public function _construct()
	{
		parent::_construct();
		$this->_init('sync/model');
	}
	
	// override
	protected function _parseItem( $item )
	{
        if (
            !isset($item[0])
            || !isset($item[1])
            || !isset($item[3])
            || !isset($item[4])
        ) {
            throw new Exception('Broken CSV file format.');
        }

		$this->setData(
			array(
				'vehicle_id'		=>		$item[3],
				'make_id'			=>		$item[0],
				'year'				=>		$item[1],
				'model'				=>		$item[4]
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
		$this->_loadFile( 'MODELS.TXT' );
	}
	
	/** 
	 * SQL query for select year production from reman_model table
	*/
	public function loadModel($make_id,$year){
		return $this->getResource()->loadModel($make_id,$year);
    }
}
