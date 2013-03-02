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

	protected $_gsw;
	
	public function _construct()
	{
		parent::_construct();
		
		$this->_gsw	=	Mage::getModel('warranty/gsw');
	}
	
	// override
	protected function _parseItem( $item )
	{
		$this->_gsw->setData(
			array(
				'customer_id'	=>		$item[0],
				'type'			=>		$item[1],
				'warranty_id'	=>		$item[4]
			)		    	
		);
		
		$this->_gsw->save();		
	}
	
	// override
	public function syncData()
	{
		$this->getResource()->trancateTable();	
		$this->_loadFile( 'GSW.TXT' );
	}
}
