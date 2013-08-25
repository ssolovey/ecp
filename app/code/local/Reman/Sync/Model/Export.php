<?php
/**
 * Export Model for Reman Sync module
 *
 * @category    Reman
 * @package     Reman_Sync
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Sync_Model_Export extends Mage_Core_Model_Abstract
{
	/**
	 * determine delimiter for CSV file parse
	 * @type string
	 */
	protected $_delimiter = '|';
	
	/**
	 * determine enclosure for CSV file parse
	 * @type string
	 */
	protected $_enclosure = '"';
	
	// export folder path
	protected $_folder = 'export/';
	//protected $_folder = 'ftpex/Upload/';
	
	/**
	 * Save data to file
	 *
	 * @param {String} $file
	 * @param {String} $data
	 * @param {Boolean} $to_ftp
	 */
	protected function _exportData($file, $data, $to_ftp)
	{
		$path = $this->_folder . $file;
				
		$fh = fopen($path, 'w');
				
    	//fputcsv($fh, $data, '|');
		
		fwrite($fh, $data);
		
		fclose($fh);
		
		if ( $to_ftp ) {
			$this->_exportToFtp( $path, $file );
		}
	}
	
	/**
	 * Store saved file to FTP
	 *
	 * @param {String} $local_path
	 * @param {String} $file
	 */
	protected function _exportToFtp($local_path, $file)
	{	
		
		// set up basic connection
		$conn_id = ftp_connect('ftp.enginetrans.com');
		
		// login with username and password
		$login_result = ftp_login($conn_id, 'buyeteftp', 'Bu38@Xtrn');
		
		// convert filename to DOS format
		$dos_file = substr($file, -12, 12);
				
		//ftp_chdir($this->conn_id, 'orders/');
			
		// upload a file
		ftp_put($conn_id, $dos_file, $local_path, FTP_BINARY);
		
		// close the connection
		ftp_close($conn_id);		
	}
	
	/**
	 * Export order
	 *
	 * @param {Array} $orderData
	 */
	public function exportOrder($orderData)
	{	
		// convert state name to symbolic code
		$state = Mage::getModel('directory/region')->getCollection()->addFieldToFilter('default_name', $orderData['st_state'] );
		
		// generate CSV string
		$stringData = $orderData['order_id'] . $this->_delimiter
			. $orderData['so_cust_num'] . $this->_delimiter
			. $this->_enclosure . $orderData['so_cust_name'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['so_cont_name'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['so_cont_email'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['so_phone'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['so_phone_ext'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['partnum'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['vin'] . $this->_enclosure . $this->_delimiter
			. $orderData['mileage'] . $this->_delimiter
			. $this->_enclosure . ($orderData['commercial_app'] ? 'Y' : 'N') . $this->_enclosure . $this->_delimiter
			. $orderData['unit_amount'] . $this->_delimiter
			. ( $orderData['core_amount'] ? $orderData['core_amount'] : 0 ) . $this->_delimiter
			. ( $orderData['ship_amount'] ? $orderData['ship_amount'] : 0 ) . $this->_delimiter
			. ( $orderData['fluid_amount'] ? $orderData['fluid_amount'] : 0 ) . $this->_delimiter
			. ( $orderData['tax_percent'] ? $orderData['tax_percent'] : 0 ) . $this->_delimiter
			. ( $orderData['tax_amount'] ? $orderData['tax_amount'] : 0 )  . $this->_delimiter
			. $orderData['warranty_id'] . $this->_delimiter
			. $this->_enclosure . $orderData['po'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['claim'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['ro'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['end_username'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['st_cust_name'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['st_cont_name'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['st_addr1'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['st_addr2'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['st_city'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $state->getFirstItem()->getCode() . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['st_zip'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['st_phone'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['st_phone_ext'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['make'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['year'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['model'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['engine'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['drive'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['aspiration'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['cyl_type'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['fuel'] . $this->_enclosure . $this->_delimiter
			. $this->_enclosure . $orderData['tag'] . $this->_enclosure
			. "\n";
						
		// new simplified format
		/*
		$exportData = array(
			'order_id' => $orderData['order_id'],
			'so_cust_num' => $orderData['so_cust_num'],
			'so_cust_name' => $orderData['so_cust_name'],
			'so_cont_name' => $orderData['so_cont_name'],
			'so_cont_email' => $orderData['so_cont_email'],
			'so_phone' => $orderData['so_phone'],
			'so_phone_ext' => $orderData['so_phone_ext'],
			'partnum' => $orderData['partnum'],
			'vin' => $orderData['vin'],
			'mileage' => $orderData['mileage'],
			'commercial_app' => $orderData['commercial_app'] ? 'Y' : 'N',
			'unit_amount' => $orderData['unit_amount'],
			'core_amount' => $orderData['core_amount'],
			'ship_amount' => $orderData['ship_amount'],
			'fluid_amount' => $orderData['fluid_amount'],
			'tax_percent' => $orderData['tax_percent'],
			'tax_amount' => $orderData['tax_amount'],
			'warranty_id' => $orderData['warranty_id'],
			'po' => $orderData['po'],
			'claim' => $orderData['claim'],
			'ro' => $orderData['ro'],
			'end_username' => $orderData['end_username'],
			'st_cust_name' => $orderData['st_cust_name'],
			'st_cont_name' => $orderData['st_cont_name'],
			'st_addr1' => $orderData['st_addr1'],
			'st_addr2' => $orderData['st_addr2'],
			'st_city' => $orderData['st_city'],
			'st_state' => $orderData['st_state'],
			'st_zip' => $orderData['st_zip'],
			'st_phone' => $orderData['st_phone'],
			'st_phone_ext' => $orderData['st_phone_ext'],
			'make' => $orderData['make'],
			'year' => $orderData['year'],
			'model' => $orderData['model'],
			'engine' => $orderData['engine'],
			'drive' => $orderData['drive'],
			'aspiration' => $orderData['aspiration'],
			'cyl_type' => $orderData['cyl_type'],
			'fuel' => $orderData['fuel'],
			'tag' => $orderData['tag']
		);
		*/
		
		$this->_exportData( 'orders/'.$orderData['order_id'].'.TXT', $stringData, true );
		
	}
}
