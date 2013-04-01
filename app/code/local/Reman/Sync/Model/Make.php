<?php
/**
 * Model for Make table
 *
 * @category    Reman
 * @package     Reman_Sync
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Sync_Model_Make extends Reman_Sync_Model_Abstract
{
	// model log name
	protected $_logid = 'make';
	
	public function _construct()
	{
		parent::_construct();
		$this->_init('sync/make');  
	}
	
	// override
	protected function _parseItem( $item )
	{
		$this->setData(
			array(
				'make_id'		=>		$item[3],
				'start_year'	=>		$item[1],
				'end_year'		=>		$item[2],
				'make'			=>		$item[0]
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
		$this->_loadFile( 'MAKES.TXT' );
	}

	/**
	 * Load Make table names
	*/
	public function loadMake(){
		return $this->getResource()->loadMake();
	}
}
