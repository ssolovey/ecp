<?php
/**
 * Model for Customers profiles
 * 
 * @category	Reman
 * @package		Reman_Sync
 * @author		Artem Petrosyan <artpetrosyan@gmail.com>
 */
class Reman_Sync_Model_Profile extends Reman_Sync_Model_Abstract
{
	
	//path to directory to scan
	protected $_directory = 'import/customers_tests/';
	
	// companies model
	protected $_companies;
	
	// customers model
	protected $_customers;
	
	// current file path
	protected $_file;

	public function _construct()
	{
		parent::_construct();
		
		$this->_companies		=	Mage::getModel('company/company');
		
		$this->_customers		=	Mage::getModel('customer/customer');
		$this->_customers->setWebsiteId(Mage::app()->getWebsite()->getId());
	}
	
	// override
	protected function _parseItem( $item )
	{
		echo '<table width="600px" border="1" cellspacing="0" cellpadding="5">';
		echo '<tr><td>Customer Num</td><td>' . $item[0] . '</td></tr>';
		echo '<tr><td>Cust Name</td><td>' . $item[1] . '</td></tr>';
		echo '<tr><td>Address</td><td> ' . $item[2] . '</td></tr>';
		echo '<tr><td>Address 2</td><td>' . $item[3] . '</td></tr>';
		echo '<tr><td>City</td><td>' . $item[4] . '</td></tr>';
		echo '<tr><td>State</td><td>' . $item[5] . '</td></tr>';
		echo '<tr><td>Zip</td><td>' . $item[6] . '</td></tr>';
		echo '<tr><td>tax number</td><td>' . $item[7] . '</td></tr>';
		echo '<tr><td>Group</td><td>' . $item[8] . '</td></tr>';
		echo '<tr><td>Discount</td><td>' . $item[9] . '</td></tr>';
		echo '<tr><td>Splink (GSP)</td><td>' . $item[10] . '</td></tr>';
		echo '<tr><td>Fluid</td><td>' . $item[11] . '</td></tr>';
		echo '<tr><td>Admin name</td><td>' . $item[12] . '</td></tr>';
		echo '<tr><td>Admin email</td><td>' . $item[13] . '</td></tr>';
		echo '<tr><td>Admin phone</td><td>' . $item[14] . '</td></tr>';
		echo '<tr><td>Admin ext</td><td>' . $item[15] . '</td></tr>';
		echo '<tr><td>Shipping</td><td>' . $item[16] . '</td></tr>';
		echo '<tr><td>ATWar</td><td>' . $item[17] . '</td></tr>';
		echo '<tr><td>ATWar val</td><td>' . $item[18] . '</td></tr>';
		echo '<tr><td>AT GSW</td><td>' . $item[19] . '</td></tr>';
		echo '<tr><td>TCWar</td><td>' . $item[20] . '</td></tr>';
		echo '<tr><td>TCWar val</td><td>' . $item[21] . '</td></tr>';
		echo '<tr><td>TC GSW</td><td>' . $item[22] . '</td></tr>';
		echo '<tr><td>DIWar</td><td>' . $item[23] . '</td></tr>';
		echo '<tr><td>DIWar val</td><td>' . $item[24] . '</td></tr>';
		echo '<tr><td>DI GSW</td><td>' . $item[25] . '</td></tr>';
		echo '<tr><td>Payment</td><td>' . $item[26] . '</td></tr>';
		echo '<tr><td>Status</td><td>' . $item[27] . '</td></tr>';
		echo '</table>';
		
		// Update companies data
		
		$data = array(
			'ete'		=>	$item[0],
			'name'		=>	$item[1],
			'addr1'		=>	$item[2],
			'addr2'		=>	$item[3],
			'city'		=>	$item[4],
			'state'		=>	$item[5],
			'zip'		=>	$item[6],
			'tax'		=>	$item[7],
			'discount'	=>	$item[9],
			'splink'	=>	$item[10],
			'fluid'		=>	$item[11],
			'ship'		=>	$item[16],
			'at_war'	=>	$item[18],
			'at_gswlink'	=>	$item[19],
			'tc_war'	=>	$item[21],
			'tc_gswlink'	=>	$item[22],
			'di_war'	=>	$item[24],
			'di_gswlink'	=>	$item[25],
			'payment'	=>	$item[26],
			'status'	=>	$item[27] === 'ACTIVE' ? 1 : 0
		);
		
		
		$company = $this->_companies->load($item[0],'ete');
										
		if ( $company->getId() ) {
			//echo '<h3>UPDATE COMPANY DATA</h3>';
			$company->addData( $data );
		} else {
			//echo '<h3>ADD NEW COMPANY</h3>';
			$company->setData( $data );
		}
		
		$company->save();
		
		
		// Update customers data
		
		$data = array(
			'email'			=>	$item[13],
			'created_in'	=>	'SYNC MODULE',
			'firstname'		=>	$item[12],
			'lastname'		=>	'-',
			'company'		=>	$company->getId(),
			'phone'			=>	$item[14],
			'ext'			=>	$item[15],
			'group_id'		=>	$item[27] === 'DELETE' ? 7: 6
		);
		
		$customer = $this->_customers->loadByEmail( $item[13] );
		
		if ( $customer->getId() ) {
			//echo '<h3>UPDATE CUSTOMER DATA</h3>';
			$customer->addData( $data );
		} else {
			//echo '<h3>ADD NEW CUSTOMER</h3>';
			
			// deactivate current admin
			$customers = $this->_customers->getCollection();
			
			foreach ( $customers as $customer_data ) {
				$customer_model = $this->_customers->load($customer_data->getId());
				
				if ( $customer_model->getCompany() == $company->getId() ) {
					
					//echo '<h3>' . $customer_model->getGroup_id() . '</h3>';
					
					if ( $customer_model->getGroup_id() == 6 ) {
						$customer_model->setGroup_id(7)->save();
					}
				}				
			}
			
			// create new admin			
			$customer->setData( $data );
		}
		
		$customer->setPassword( $customer->generatePassword(8) );
		$customer->save();
		
		// Send confirmation email
		$customer->sendNewAccountEmail('confirmation');
		
		// Delete file
		unlink( $this->_file );
	}
	
	// override
	public function syncData()
	{	
	
		$profiles = glob($this->_directory . '*.TXT');
		
		$this->_file = $profiles[0];
		
		if ( $this->_file  ) {
			echo '<h3>Parse file: ' . $this->_file . '</h3>';
			echo '<h4><a href=".">NEXT FILE</a></h4>';
		
			$this->_loadFile( $this->_file );
		} else {
			echo '<h3>No more files to parse in directory: ' . $this->_directory . '</h3>';
		}
		
		/*
		foreach($profiles as $file)
		{
			echo'<h3>Parse file: ' . $file . '</h3>';
			$this->_loadFile( $file );
		}
		*/
		
	}
	
	public function test()
	{
		$test_csv = new Varien_File_Csv();
		
		// Set delimiter to "\"
		$test_csv->setDelimiter( $this->_delim );
		
		// Load data from CSV file
		$data = $test_csv->getData( $this->_directory . 'test_seq.csv' );
				
		foreach( $data as $item ) {
			
			if ( sizeof($item) > 1 ) {
								
				$this->_file  = $this->_directory . $item[0];
				
				if ( file_exists($this->_file) ) {
					
					echo '<h3>Parse file: ' . $item[0] . '</h3>';
					echo '<h4>'. $item[2] . '</h4>';
					echo '<h4><a href=".">NEXT FILE</a></h4>';
					
					$this->_loadFile( $this->_directory . $item[0] );
				
					return;
				}								
			}
		}
	}
	
	/*
	protected function _createCustomer( $customer, $data )
	{
		$customer->setData( 
			array(
				'eteid'			=>	$data[0],
				'email'			=>	$data[13],
				'firstname'		=>	$data[1],
				'lastname'		=>	'-',
				'splink'		=>	$data[10],
				'fluid'			=>	$data[11],
				'discount'		=>	$data[9],
				'payment'		=>	$data[23],
				'atwar'			=>	$data[18],
				'tcwar'			=>	$data[21]
				
			)
		);
		
		$this->_customers->save();
				
		$address = Mage::getModel('customer/address');
		
		$address->setCustomerId($customer->getId());


		$address->firstname		=	$customer->firstname;
		$address->lastname		=	$customer->lastname;
		$address->country_id	=	'US';
		$address->postcode		=	$data[6];
		$address->city			=	$data[4];
		$address->region		=	$data[5];
		//$address->telephone	=	'';
		//$address->fax			=	'';
		//$address->company		=	'';
		$address->street		=	array($data[2],$data[3]);
		$address->is_default_billing = true;
		$address->is_default_shipping = true;
		
		
		$address->save();
	}
	*/
}
