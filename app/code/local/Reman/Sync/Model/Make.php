<?php
/**
 * Model for Make table
 *
 * @category    Reman
 * @package     Reman_Sync
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Sync_Model_Make extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('sync/make');  
	}
	
	/**
	 * Sync database
	 */
	public function syncData()
	{	
	
		$this->getResource()->trancateTable();
		
		//Mage::getSingleton('core/resource')->getConnection('core_write')->query("TRUNCATE TABLE `reman_make`");
				
		// Location of CSV file
		$file	=	'import/make.csv';
				
		$csv	=	new Varien_File_Csv();
		
		// Set delimiter to "\"
		$csv->setDelimiter('|');
		
		// Load data from CSV file
		$data	=	$csv->getData($file);
		
		$count	=	0;		
		
		foreach( $data as $item ) {
					
			$this->setData(
				array(
					'make_id'		=>		$item[3],
					'start_year'	=>		$item[1],
					'end_year'		=>		$item[2],
					'make'			=>		$item[0]
				)		    	
			);
			
			$this->save();
			
			$count++;
		}

		$this->syncLog();
	}
	
	/**
	 * Load Make table names
	*/
	public function loadMake(){
		return $this->getResource()->loadMake();
	}
	
	// TEMPERARY
	// will remove after testing
	public function syncLog() {
		$myFile = "cronlog.html";
		$fh = fopen($myFile, 'a');
		$stringData = 'sync_make: ' . date('l jS \of F Y h:i:s A') . '<br/>';
		fwrite($fh, $stringData);
		fclose($fh);
   	}
}
