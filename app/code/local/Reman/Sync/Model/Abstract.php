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
	
	// sync folder path
	//protected $_folder = 'import/';
	protected $_folder = 'ftpex/Download/';
	
	// current file path
	protected $_file;
	
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
	 * Scan folder for data files
	 */
	protected function _scanFolder( $folder ) 
	{
		
		$files = glob( $this->_folder . $folder . '*.TXT' );

		foreach($files as $file)
		{
			try {
				$this->_parseFile( $file );
			} catch (Exception $e) {
				$this->syncLog($file, "Filed: " . $e->getMessage());
			}
		}
		
		$this->syncLog($folder, "Scaned");
	}
	
	/**
	 * Load file
	 *
	 */
	protected function _loadFile( $filename )
	{
		$path = $this->_folder . $filename;
				
		if ( file_exists($path) ) {
			
			$this->_beforeParseFile();
			
			$this->_parseFile( $path );
			
		} else {
			$this->syncLog($path, "Not found");
		}		
	}
	
	/**
	 * Do some action before parsing file
	 * (if necessary) 
	 */
	protected function _beforeParseFile()
	{
		
	}
	
	/**
	 * Parse file
	 *
	 */
	protected function _parseFile( $path )
	{
		$csv = new Varien_File_Csv();
			
		// Set delimiter to "\"
		$csv->setDelimiter( $this->_delim );
		
		// Load data from CSV file
		$data = $csv->getData( $path );
				
		foreach( $data as $item ) {
			
			if ( sizeof($item) > 1 ) {
				$this->_parseItem( $item );
			}
		}
					
		//unlink($path);
		
		if ( copy( $path , '_imported/'.$path ) ) {
		  unlink($path);
		}
				
		$this->syncLog($path, "Synced");
	}
	
	/**
	 * Log message in cronlog
	 * after sync complete
	 */
	protected function syncLog($path, $message)
	{
		date_default_timezone_set('America/Chicago');
		
		$myFile = "cronlog.html";
		$fh = fopen($myFile, 'a');
		$stringData = $message . ': ' . $path . ' @ ' . date('Y.m.d h:i A') . '<br/>';
		fwrite($fh, $stringData);
		fclose($fh);
   	}
}
