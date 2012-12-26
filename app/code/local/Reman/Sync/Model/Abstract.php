<?php
/**
 * Abstract Model for Reman Sync module
 *
 * @category    Reman
 * @package     Reman_Sync
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Sync_Model_Abstract extends Mage_Core_Model_Abstract
{	
	/**
	 * determine delimiter for CSV file parse
	 * @type string
	 */
	protected $_delim = '|';
	
	/**
	 * Sync resource data
	 * should be overrides in each model
	 */
	public function syncData()
	{	
		
	}
	
		
	/**
	 * Parse item data from CSV file
	 * should be overrides in each model
	 *
	 * @param item
	 */
	protected function _parseItem( $item )
	{
		
	}
	
	/**
	 * Load CSV file
	 *
	 */
	protected function _loadFile( $path )
	{
		$csv = new Varien_File_Csv();
		
		// Set delimiter to "\"
		$csv->setDelimiter( $this->_delim );
		
		// Load data from CSV file
		$data = $csv->getData( $path );
				
		foreach( $data as $item ) {
			$this->_parseItem( $item );
		}
		
		$this->syncLog();
	}
	
	/**
	 * TEMPERARY
	 * Log message in cronlog
	 * after sync complete
	 */
	protected function syncLog()
	{
		$myFile = "cronlog.html";
		$fh = fopen($myFile, 'a');
		$stringData = $this->getResourceName() . ': ' . date('l jS \of F Y h:i:s A') . '<br/>';
		fwrite($fh, $stringData);
		fclose($fh);
   	}
}
