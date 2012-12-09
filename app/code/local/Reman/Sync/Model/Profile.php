<?php
/**
 * Profile model
 * 
 * @category	Reman
 * @package		Reman_Sync
 * @author		Artem Petrosyan <artpetrosyan@gmail.com>
 */
 
class Reman_Sync_Model_Profile extends Mage_Core_Model_Abstract
{	
	public function _construct()
	{
		parent::_construct();		
	}
	
	/**
	 * Load customers CSV file
	 *
	 */
	public function loadFile() {
				
		// Location of CSV file
		$file	=	'import/cust.csv';

		$csv	=	new Varien_File_Csv();

		// Set delimiter to "\"
		$csv->setDelimiter('|');

		// Load data from CSV file
		$data	=	$csv->getData($file);
		
		foreach( $data as $item ) {			
			echo $item[0];
		}
	}
}
