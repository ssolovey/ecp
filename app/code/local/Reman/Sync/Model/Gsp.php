<?php
/**
 * Model for GSP table
 *
 * @category    Reman
 * @package     Reman_Sync
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Sync_Model_Gsp extends Reman_Sync_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('sync/gsp');  
	}
	
	// override
	protected function _parseItem( $item )
	{
		$this->setData(
			array(
				'customer_id'	=>		$item[0],
				'partnum'		=>		$item[1],
				'price'			=>		$item[2],
				'core'			=>		$item[3]
			)		    	
		);
		
		$this->save();		
	}
	
	// override
	public function syncData()
	{
		$this->getResource()->trancateTable();	
		$this->_loadFile( 'GSP.TXT' );
	}
	
	/**
	 * Load Customer Special Price
	 *
	 */
	public function loadSp($sku, $splink){
		return $this->getResource()->loadSp($sku, $splink);
    }
	
}
