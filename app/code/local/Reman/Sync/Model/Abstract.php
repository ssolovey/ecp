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
	protected $_folder;
	protected $_importedFolder;

	// current file path
	protected $_file;

	protected function _construct()
	{
		parent::_construct();

		$this->_folder = Mage::getBaseDir('base') .  '/ftpex/Download/';
		$this->_importedFolder = Mage::getBaseDir('base') .  '/ftpex/Imported/';
	}
	
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

		if ( sizeof($files) ) {
			foreach($files as $file)
			{
				$this->_parseFile( $file , $folder );
			}
		} else {
			$this->syncLog(false,0,'-/-','Folder is Empty!!!');
		}	
	}
	
	/**
	 * Load file
	 *
	 */
	protected function _loadFile( $filename )
	{	
		$path = $this->_folder . $filename;


		if ( file_exists($path) ) {
			$collection = Mage::getSingleton('index/indexer')->getProcessesCollection();
			foreach ($collection as $process) {
				$process->setMode(Mage_Index_Model_Process::MODE_MANUAL)->save();
				// $process->setMode(Mage_Index_Model_Process::MODE_REAL_TIME)->save();
			}

			$this->_beforeParseFile();
			
			$this->_parseFile( $path );
			foreach ($collection as $process) {
				$process->setMode(Mage_Index_Model_Process::MODE_REAL_TIME)->save();
			}
			
		} else {

            $this->syncLog(false,0,$filename, 'File is absent!!!');

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
	protected function _parseFile( $path , $folder )
	{
		$csv = new Varien_File_Csv();
			
		// Set delimiter to "\"
		$csv->setDelimiter( $this->_delim );
		
		// Load data from CSV file
		$data = $csv->getData( $path );

		$count = 0;
			
		foreach( $data as $item ) {
			
			if ( sizeof($item) > 1 ) {
				if ( $this->_parseItem( $item ) !== 0 ) {
					$count++;
				}
			}
		}

		//unlink($path);
		


        $this->syncLog(true, $count,'','');

        $fileName = pathinfo($path, PATHINFO_FILENAME) . '.' . pathinfo($path, PATHINFO_EXTENSION);

        /* If folder is provided import files to folder*/
        if($folder){

            $pathToImportFolder = $this->_importedFolder.$folder;

        } else{

            $pathToImportFolder = $this->_importedFolder;

        }

        if ( copy( $path , $pathToImportFolder . $fileName ) ) {
          unlink($path);
        }

	}
	
	/**
	 * Log message in cronlog table
	 * after sync complete
	 */
	protected function syncLog( $synced, $count, $file, $reason  )
	{	
	
		date_default_timezone_set('America/Chicago');
		
		$date = date('Y-m-d H:i:s');

		if ( $this->_logid ) {
			$syncModel = Mage::getModel('sync/log')->load($this->_logid);
		} else {
			return;
		}
		
		if ( $synced ) {
			$syncModel->setData(
				array(
					'model_id'	=> $this->_logid,
					'sync_date' => $date,
					'sync_items'=> $count,
					'cron_date' => $date
				)
			);
			$this->logMessage('Synced ' . $count . ' items');
		} else {
			$syncModel->setData(
				array(
					'model_id'	=> $this->_logid,
					'cron_date' => $date
				)
			);
			//$this->logMessage('No updates');

			try {

				$params = array(
					'model' => $this->_logid,
					'cron_date' => $date,
                    'file' => $file,
                    'reason' => $reason
				);

				/* Send Export Fail Email */

				Mage::getModel('order/email')->sendEmail(
					'5',
					array(
						'name' => 'ETEREMAN',
						'email' => 'exportError@etereman.com'
					),

					'hybridtestmail@gmail.com',
					'Support Team',
					'Export Error Report',
					$params
				);
			} catch (Exception $e) {
				//failed to process email sending. Skipping it
			}
		}
		
		$syncModel->save();
   	}
   	
   	/**
	 * Log message in cronlog.html
	 * after sync success or error catched
	 */
	protected function logMessage($message)
	{
		date_default_timezone_set('America/Chicago');
		
		$myFile = "cronlog.html";
		$fh = fopen($myFile, 'a');
		$stringData = strtoupper( $this->_logid ) . ': ' . $message . ' @ ' . date('Y.m.d h:i A') . '<br/>';
		fwrite($fh, $stringData);
		fclose($fh);
   	}
}
