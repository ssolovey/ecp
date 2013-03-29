<?php
/**
 * Model for GSW table
 *
 * @category    Reman
 * @package     Reman_Sync
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Sync_Model_Warranty extends Reman_Sync_Model_Abstract
{
	
	protected $_model;
	
	public function _construct()
	{
		parent::_construct();
		
		$this->_model	=	Mage::getModel('warranty/warranties');
	}
	
	// override
	protected function _parseItem( $item )
	{
		$this->_model->setData(
			array(
				'warranty_id'	=>		$item[0],
				'value'			=>		$item[1],
				'warranty'		=>		$item[2]
			)		    	
		);
		
		$this->_model->save();		
	}
	
	// override
	protected function _beforeParseFile() {
		$this->_model->getResource()->trancateTable();	
	}
	
	// override
	public function syncData()
	{
		$this->_loadFile( 'WARRANTY.TXT' );
	}
}
