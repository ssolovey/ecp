<?php
class Reman_Sync_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		echo 'Reman sync module.</br>';
		
		$this->syncMakeData();
		$this->syncModelData();
		//$this->syncInvenData();	
	}
	
	private function syncMakeData()
	{		
		$model	=	Mage::getModel('sync/make');
		
		// Location of CSV file
		$file	=	'import/make.csv';
				
		$csv	=	new Varien_File_Csv();
		
		// Set delimiter to "\"
		$csv->setDelimiter('|');
		
		// Load data from CSV file
		$data	=	$csv->getData($file);
		
		$count	=	0;
		
		foreach( $data as $item ) {
					
			$model->setData(
				array(
					'make_id'		=>		$item[3],
					'start_year'	=>		$item[1],
					'end_year'		=>		$item[2],
					'make'			=>		$item[0]
				)		    	
			);
			
			$model->save();
			
			$count++;
		}
		
		echo 'Make: sync items > ';
		echo $count;
		echo '<br/>';
	}
	
	private function syncModelData()
	{		
		$model	=	Mage::getModel('sync/model');
		
		// Location of CSV file
		$file	=	'import/model.csv';
				
		$csv	=	new Varien_File_Csv();
		
		// Set delimiter to "\"
		$csv->setDelimiter('|');
		
		// Load data from CSV file
		$data	=	$csv->getData($file);
		
		$count	=	0;
		
		foreach( $data as $item ) {
					
			$model->setData(
				array(
					'vehicle_id'		=>		$item[3],
					'make_id'			=>		$item[0],
					'year'				=>		$item[1],
					'model'				=>		$item[4]
				)		    	
			);
			
			$model->save();
			
			$count++;
		}
		
		echo 'Model: sync items > ';
		echo $count;
		echo '<br/>';
	}
	
	private function syncApplicData()
	{		
		$model	=	Mage::getModel('sync/applic');
		
		// Location of CSV file
		$file	=	'import/applic.csv';
				
		$csv	=	new Varien_File_Csv();
		
		// Set delimiter to "\"
		$csv->setDelimiter('|');
	
	}
}
