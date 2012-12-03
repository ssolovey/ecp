<?php
class Reman_Sync_Model_Gsw extends Mage_Core_Model_Abstract
{

	protected $_gsw;
	
	public function _construct()
	{
		parent::_construct();
		
		$this->_gsw	=	Mage::getModel('warranty/gsw');
	}
	
	/**
	 * Load products data form CSV file
	 *
	 */
	public function loadGswData() {
		
		$this->_gsw->getResource()->trancateTable();
		
		// Location of CSV file
		$file	=	'import/gsw.csv';

		$csv	=	new Varien_File_Csv();

		// Set delimiter to "\"
		$csv->setDelimiter('|');

		// Load data from CSV file
		$data	=	$csv->getData($file);
		
		foreach( $data as $item ) {			
			$this->_gsw->setData(
				array(
					'customer_id'	=>		$item[0],
					'type'			=>		$item[1],
					'warranty_id'	=>		$item[4]
				)		    	
			);
			
			$this->_gsw->save();
		}
	}
}
