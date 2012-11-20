<?php
class Reman_Sync_Model_Model extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('sync/model');
	}
	
	/**
	 * Sync database
	 */
	public function syncData() 
	{
		
		$this->getResource()->trancateTable();
		
		//Mage::getSingleton('core/resource')->getConnection('core_write')->query("TRUNCATE TABLE `reman_model`");
		
		// Location of CSV file
		$file	=	'import/model.csv';
				
		$csv	=	new Varien_File_Csv();
		
		// Set delimiter to "\"
		$csv->setDelimiter('|');
		
		// Load data from CSV file
		$data	=	$csv->getData($file);
		
		$count	=	0;
		
		foreach( $data as $item ) {
					
			$this->setData(
				array(
					'vehicle_id'		=>		$item[3],
					'make_id'			=>		$item[0],
					'year'				=>		$item[1],
					'model'				=>		$item[4]
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
	public function loadModel($make_id,$year){
		return $this->getResource()->loadModel($make_id,$year);
    }

	// TEMPERARY
	// will remove after testing
	public function syncLog() {
		$myFile = "cronlog.html";
		$fh = fopen($myFile, 'a');
		$stringData = 'sync_model: ' . date('l jS \of F Y h:i:s A') . '<br/>';
		fwrite($fh, $stringData);
		fclose($fh);
   	}
	
}
