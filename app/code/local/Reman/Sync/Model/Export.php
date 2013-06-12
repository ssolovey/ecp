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
	protected $_delim = '|';
	
	// export folder path
	protected $_folder = 'export/';
	//protected $_folder = 'ftpex/Upload/';
	
	protected function _exportData($file, $data, $to_ftp)
	{
		$path = $this->_folder . $file;
				
		$fh = fopen($path, 'w');
				
    	fputcsv($fh, $data, '|');
		
		//fwrite($fh, $stringData);
		fclose($fh);
		
		if ( $to_ftp ) {
			$this->_exportToFtp( $path, $file );
		}
	}
	
	protected function _exportToFtp($local_path, $file)
	{	
		
		// set up basic connection
		$conn_id = ftp_connect('ftp.enginetrans.com');
		
		// login with username and password
		$login_result = ftp_login($conn_id, 'buyeteftp', 'Bu38@Xtrn');
		
		ftp_chdir($this->conn_id, 'orders/');
			
		// upload a file
		ftp_put($conn_id, $file, $local_path, FTP_BINARY);
		
		// close the connection
		ftp_close($conn_id);		
	}
	
	public function exportOrder($orderData)
	{
		
		// new simplified format
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
			'warrenty_terms' => $orderData['warrenty_terms'],
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
		
		// old format
		/*
		$exportData = array(
			'ete_order_id' => $orderData['ete_order_id'],
			'date_invoice' => $orderData['date_invoice'],
			'date_order' => $orderData['date_order'],
			'order_status' => $orderData['order_status'],
			'order_type' => $orderData['order_type'],
			'ete_cust' => $orderData['ete_cust'],
			'so_cust_num' => $orderData['so_cust_num'],
			'so_cust_name' => $orderData['so_cust_name'],
			'so_cont_name' => $orderData['so_cont_name'],
			'so_phone' => $orderData['so_phone'],
			'so_phone_ext' => $orderData['so_phone_ext'],
			'po' => $orderData['po'],
			'bt_cust_num' => $orderData['bt_cust_num'],
			'bt_cust_name' => $orderData['bt_cust_name'],
			'bt_addr1' => $orderData['bt_addr1'],
			'bt_addr2' => $orderData['bt_addr2'],
			'bt_city' => $orderData['bt_city'],
			'bt_state' => $orderData['bt_state'],
			'bt_zip' => $orderData['bt_zip'],
			'st_cust_num' => $orderData['st_cust_num'],
			'st_cust_name' => $orderData['st_cust_name'],
			'st_addr1' => $orderData['st_addr1'],
			'st_addr2' => $orderData['st_addr2'],
			'st_city' => $orderData['st_city'],
			'st_state' => $orderData['st_state'],
			'st_zip' => $orderData['st_zip'],
			'st_cont_name' => $orderData['st_cont_name'],
			'st_phone' => $orderData['st_phone'],
			'st_phone_ext' => $orderData['st_phone_ext'],
			'vin' => $orderData['vin'],
			'make' => $orderData['make'],
			'year' => $orderData['year'],
			'model' => $orderData['model'],
			'engine' => $orderData['engine'],
			'aspiration' => $orderData['aspiration'],
			'cyl_type' => $orderData['cyl_type'],
			'fuel' => $orderData['fuel'],
			'drive' => $orderData['drive'],
			'tag' => $orderData['tag'],
			'end_username' => $orderData['end_username'],
			'ro' => $orderData['ro'],
			'mileage' => $orderData['mileage'],
			'claim' => $orderData['claim'],
			'partnum' => $orderData['partnum'],
			'serial' => $orderData['serial'],
			'family' => $orderData['family'],
			'alt_partnum' => $orderData['alt_partnum'],
			'unit_type' => $orderData['unit_type'],
			'warrenty_terms' => $orderData['warrenty_terms'],
			'carrier' => $orderData['carrier'],
			'carrier_service' => $orderData['carrier_service'],
			'carrier_options' => $orderData['carrier_options'],
			'date_ship' => $orderData['date_ship'],
			'date_deliver' => $orderData['date_deliver'],
			'ship_from' => $orderData['ship_from'],
			'tracknum' => $orderData['tracknum'],
			'original_invoice' => $orderData['original_invoice'],
			'return_auth' => $orderData['return_auth'],
			'csi' => $orderData['csi'],
			'order_id' => $orderData['order_id'],
			'unit_amount' => $orderData['unit_amount'],
			'core_amount' => $orderData['core_amount'],
			'parts_amount' => $orderData['parts_amount'],
			'tax_percent' => $orderData['tax_percent'],
			'tax_amount' => $orderData['tax_amount'],
			'ship_amount' => $orderData['ship_amount'],
			'deposit_received' => $orderData['deposit_received'],
			'total_amount' => $orderData['total_amount'],
			'transaction_type' => $orderData['transaction_type'],
			'commercial_app' => $orderData['commercial_app']
		);
		*/
		
		$this->_exportData( 'orders/'.$orderData['order_id'].'.TXT', $exportData, true );
		
	}
}
