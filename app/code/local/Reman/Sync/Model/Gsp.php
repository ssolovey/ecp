<?php
class Reman_Sync_Model_Gsp extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('sync/gsp');  
	}
	
	/**
	 * Load products data form CSV file
	 *
	 */
	public function loadGspData() {
		
		$this->getResource()->trancateTable();
		
		// Location of CSV file
		$file	=	'import/gsp.csv';

		$csv	=	new Varien_File_Csv();

		// Set delimiter to "\"
		$csv->setDelimiter('|');

		// Load data from CSV file
		$data	=	$csv->getData($file);
		
		foreach( $data as $item ) {			
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
	}
	
	/**
	 * Load Customer Special Price
	 *
	 */
	public function loadSp($sku, $splink){
		return $this->getResource()->loadSp($sku, $splink);
    }
	
}
