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
	
	//path to directory to scan
	protected $_directory = 'import/orders/';
		
	// current file path
	protected $_file;
	
	// override
	protected function _parseItem( $item )
	{
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
	}
	
	// override
	public function syncData()
	{	
		$this->_loadFile( 'import/ORDERS.TXT' );
	}
}
