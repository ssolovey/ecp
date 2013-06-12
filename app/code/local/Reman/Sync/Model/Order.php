<?php
/**
 * Model for Orders updates
 * 
 * @category	Reman
 * @package		Reman_Sync
 * @author		Artem Petrosyan <artpetrosyan@gmail.com>
 */
class Reman_Sync_Model_Order extends Reman_Sync_Model_Abstract
{
	// model log name
	protected $_logid = 'order';
	
	// override
	protected function _parseItem( $item )
	{
		/*
		echo '<h1>Parsed ' . $item[0] . '</h1>';
		
		
		echo '<table width="600px" border="1" cellspacing="0" cellpadding="5">';
		echo '<tr><td>Order/Inv #</td><td>' . $item[0] . '</td></tr>';
		echo '<tr><td>Invoice Date</td><td>' . $item[1] . '</td></tr>';
		echo '<tr><td>Order Date</td><td>' . $item[2] . '</td></tr>';
		echo '<tr><td>Order Status</td><td>' . $item[3] . '</td></tr>';
		echo '<tr><td>Order Type</td><td>' . $item[4] . '</td></tr>';
		echo '<tr><td>ETE Cust Rep</td><td>' . $item[5] . '</td></tr>';
		echo '<tr><td>Sold To Customer Number</td><td>' . $item[6] . '</td></tr>';
		echo '<tr><td>Sold To Customer Name</td><td>' . $item[7] . '</td></tr>';
		echo '<tr><td>Sold To Contact Name</td><td>' . $item[8] . '</td></tr>';
		echo '<tr><td>Sold To Phone</td><td>' . $item[9] . '</td></tr>';
		echo '<tr><td>Sold To Phone Ext</td><td>' . $item[10] . '</td></tr>';
		echo '<tr><td>PO#</td><td>' . $item[11] . '</td></tr>';
		echo '<tr><td>Bill To Customer Number</td><td>' . $item[12] . '</td></tr>';
		echo '<tr><td>Bill To Customer Name</td><td>' . $item[13] . '</td></tr>';
		echo '<tr><td>Bill To Address 1</td><td>' . $item[14] . '</td></tr>';
		echo '<tr><td>Bill To Address 2</td><td>' . $item[15] . '</td></tr>';
		echo '<tr><td>Bill To City</td><td>' . $item[16] . '</td></tr>';
		echo '<tr><td>Bill To State</td><td>' . $item[17] . '</td></tr>';
		echo '<tr><td>Bill To Zip</td><td>' . $item[18] . '</td></tr>';
		echo '<tr><td>Ship To Customer Number</td><td>' . $item[19] . '</td></tr>';
		echo '<tr><td>Ship To Customer Name</td><td>' . $item[20] . '</td></tr>';
		echo '<tr><td>Ship To Address 1</td><td>' . $item[21] . '</td></tr>';
		echo '<tr><td>Ship To Address 2</td><td>' . $item[22] . '</td></tr>';
		echo '<tr><td>Ship To City</td><td>' . $item[23] . '</td></tr>';
		echo '<tr><td>Ship To State</td><td>' . $item[24] . '</td></tr>';
		echo '<tr><td>Ship To Zip</td><td>' . $item[25] . '</td></tr>';
		echo '<tr><td>Ship To Contact Name</td><td>' . $item[26] . '</td></tr>';
		echo '<tr><td>Ship To Phone</td><td>' . $item[27] . '</td></tr>';
		echo '<tr><td>Ship To Phone Ext</td><td>' . $item[28] . '</td></tr>';
		echo '<tr><td>Vehicle VIN</td><td>' . $item[29] . '</td></tr>';
		echo '<tr><td>Vehicle Make</td><td>' . $item[30] . '</td></tr>';
		echo '<tr><td>Vehicle Year</td><td>' . $item[31] . '</td></tr>';
		echo '<tr><td>Vehicle Model</td><td>' . $item[32] . '</td></tr>';
		echo '<tr><td>Vehicle Engine</td><td>' . $item[33] . '</td></tr>';
		echo '<tr><td>Vehicle Aspiration</td><td>' . $item[34] . '</td></tr>';
		echo '<tr><td>Vehicle Cyl Type</td><td>' . $item[35] . '</td></tr>';
		echo '<tr><td>Vehicle Fuel</td><td>' . $item[36] . '</td></tr>';
		echo '<tr><td>Vehicle Drive</td><td>' . $item[37] . '</td></tr>';
		echo '<tr><td>Unit Tag #</td><td>' . $item[38] . '</td></tr>';
		echo '<tr><td>End User Name</td><td>' . $item[39] . '</td></tr>';
		echo '<tr><td>RO #</td><td>' . $item[40] . '</td></tr>';
		echo '<tr><td>Vehicle Mileage</td><td>' . $item[41] . '</td></tr>';
		echo '<tr><td>Claim #</td><td>' . $item[42] . '</td></tr>';
		echo '<tr><td>Part #</td><td>' . $item[43] . '</td></tr>';
		echo '<tr><td>Serial #</td><td>' . $item[44] . '</td></tr>';
		echo '<tr><td>Family</td><td>' . $item[45] . '</td></tr>';
		echo '<tr><td>Alt Part #</td><td>' . $item[46] . '</td></tr>';
		echo '<tr><td>Unit Type</td><td>' . $item[47] . '</td></tr>';
		echo '<tr><td>Warranty Terms</td><td>' . $item[48] . '</td></tr>';
		echo '<tr><td>Carrier</td><td>' . $item[49] . '</td></tr>';
		echo '<tr><td>Carrier Service</td><td>' . $item[50] . '</td></tr>';
		echo '<tr><td>Carrier Options</td><td>' . $item[51] . '</td></tr>';
		echo '<tr><td>Ship By Date</td><td>' . $item[52] . '</td></tr>';
		echo '<tr><td>Deliver By Date</td><td>' . $item[53] . '</td></tr>';
		echo '<tr><td>Ship From</td><td>' . $item[54] . '</td></tr>';
		echo '<tr><td>Tracking #</td><td>' . $item[55] . '</td></tr>';
		echo '<tr><td>Original Invoice</td><td>' . $item[56] . '</td></tr>';
		echo '<tr><td>Return Auth #</td><td>' . $item[57] . '</td></tr>';
		echo '<tr><td>CSI #</td><td>' . $item[58] . '</td></tr>';
		echo '<tr><td>Web Order #</td><td>' . $item[59] . '</td></tr>';
		echo '<tr><td>Unit Amount</td><td>' . $item[60] . '</td></tr>';
		echo '<tr><td>Core Amount</td><td>' . $item[61] . '</td></tr>';
		echo '<tr><td>Parts Amount</td><td>' . $item[62] . '</td></tr>';
		echo '<tr><td>Tax %</td><td>' . $item[63] . '</td></tr>';
		echo '<tr><td>Tax Amount</td><td>' . $item[64] . '</td></tr>';
		echo '<tr><td>Shipping Amount</td><td>' . $item[65] . '</td></tr>';
		echo '<tr><td>Deposit Received</td><td>' . $item[66] . '</td></tr>';
		echo '<tr><td>Total Amount</td><td>' . $item[67] . '</td></tr>';
		echo '<tr><td>Transaction Type</td><td>' . $item[68] . '</td></tr>';
		echo '<tr><td>Commercial App</td><td>' . $item[69] . '</td></tr>';
		echo '</table>';
		*/
		
		$warranty_id = Mage::getModel('warranty/warranties')->getIdByName($item[48]);
		
		$data = array(
			'ete_order_id' => $item[0],
			'date_invoice' => $item[1],
			'date_order' => $item[2],
			'order_status' => $item[3],
			'order_type' => $item[4],
			'ete_cust' => $item[5],
			'so_cust_num' => $item[6],
			'so_cust_name' => $item[7],
			'so_cont_name' => $item[8],
			'so_phone' => $item[9],
			'so_phone_ext' => $item[10],
			'po' => $item[11],
			'bt_cust_num' => $item[12],
			'bt_cust_name' => $item[13],
			'bt_addr1' => $item[14],
			'bt_addr2' => $item[15],
			'bt_city' => $item[16],
			'bt_state' => $item[17],
			'bt_zip' => $item[18],
			'st_cust_num' => $item[19],
			'st_cust_name' => $item[20],
			'st_addr1' => $item[21],
			'st_addr2' => $item[22],
			'st_city' => $item[23],
			'st_state' => $item[24],
			'st_zip' => $item[25],
			'st_cont_name' => $item[26],
			'st_phone' => $item[27],
			'st_phone_ext' => $item[28],
			'vin' => $item[29],
			'make' => $item[30],
			'year' => $item[31],
			'model' => $item[32],
			'engine' => $item[33],
			'aspiration' => $item[34],
			'cyl_type' => $item[35],
			'fuel' => $item[36],
			'drive' => $item[37],
			'tag' => $item[38],
			'end_username' => $item[39],
			'ro' => $item[40],
			'mileage' => $item[41],
			'claim' => $item[42],
			'partnum' => $item[43],
			'serial' => $item[44],
			'family' => $item[45],
			'alt_partnum' => $item[46],
			'unit_type' => $item[47],
			'warranty_id' => $warranty_id,
			'carrier' => $item[49],
			'carrier_service' => $item[50],
			'carrier_options' => $item[51],
			'date_ship' => $item[52],
			'date_deliver' => $item[53],
			'ship_from' => $item[54],
			'tracknum' => $item[55],
			'original_invoice' => $item[56],
			'return_auth' => $item[57],
			'csi' => $item[58],
			//'order_id' => $item[59],
			'unit_amount' => $item[60],
			'core_amount' => $item[61],
			'fluid_amount' => $item[62],
			'tax_percent' => $item[63],
			'tax_amount' => $item[64],
			'ship_amount' => $item[65],
			'deposit_received' => $item[66],
			'total_amount' => $item[67],
			'transaction_type' => $item[68],
			'commercial_app' => ($item[69] == 'Y') ? 1 : 0
		);
		
		$user_id = Mage::getModel('company/company')->getComplanyAdmin($item[6]);
		
		Mage::getModel('order/order')->createOrder(
			$user_id, // magento user id
			$data,
			true
		);
	}
	
	// override
	public function syncData()
	{	
		$this->_loadFile( 'ORDERS.TXT' );
	}
	
	public function test()
	{		
		$test_csv = new Varien_File_Csv();
		
		// Set delimiter to "\"
		$test_csv->setDelimiter( $this->_delim );
		
		$test_directory = 'import/orders_tests/';
		
		$test_file = '';
		
		// Load data from CSV file
		$data = $test_csv->getData( $test_directory . 'test_seq.csv' );
				
		foreach( $data as $item ) {
			
			if ( sizeof($item) > 1 ) {
								
				$test_file  = $test_directory . $item[0];
				
				if ( file_exists($test_file) ) {
					
					echo '<h3>Parse file: ' . $item[0] . '</h3>';
					echo '<h4>'. $item[1] . '</h4>';
					echo '<h4><a href=".">NEXT FILE</a></h4>';
					
					$this->_parseFile( $test_directory . $item[0] );
				
					return;
				}								
			}
		}
	}
}
