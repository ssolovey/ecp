<?php
class Reman_Sync_Model_Applic extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('sync/applic');       
	}
	
	/**
	 * Sync database
	 */
	public function syncData()
	{	
			
		$this->getResource()->trancateTable();
		
		// Location of CSV file
		$file	=	'import/applic.csv';
				
		$csv	=	new Varien_File_Csv();
		
		// Set delimiter to "\"
		$csv->setDelimiter('|');
		
		// Load data from CSV file
		$data	=	$csv->getData($file);
		
		$count	=	0;
		
		foreach( $data as $item ) {
					
			$this->setData(
				array(
					'vehicle_id'		=>		$item[0],
					'group_number'		=>		$item[1],
					'subgroup'			=>		$item[4],
					'menu_heading'		=>		$item[5],
					'applic'			=>		$item[3],
					'part_number'		=>		$item[6]
				)		    	
			);
			
			$this->save();
			
			$count++;
		}
		
		$this->syncLog();
	}
	
	/** 
	 * SQL query for select year production from reman_model table
	*/
	public function loadProductId($vehicle_id){
		return $this->getResource()->loadProductId($vehicle_id);
    }
	
	public function loadProduct($applic_id){
		return $this->getResource()->loadProduct($applic_id);
    }
	
	// TEMPERARY
	// will remove after testing
	public function syncLog() {
		$myFile = "cronlog.html";
		$fh = fopen($myFile, 'a');
		$stringData = 'sync_applic: ' . date('l jS \of F Y h:i:s A') . '<br/>';
		fwrite($fh, $stringData);
		fclose($fh);
   	}
}
