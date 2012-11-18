<?php
class Reman_Sync_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		echo 'Reman sync module.</br>';
		
		$this->syncMakeData();
		$this->syncModelData();
		$this->syncApplicData();	
	}
	
	private function deleteAllRecords($model) 
	{
		$collection = $model->getCollection()->getItems();
		
		foreach( $collection as $item ) {
			$item->delete();
		}
	}
	
	private function syncMakeData()
	{		
		$model	=	Mage::getModel('sync/make');
		
		$this->deleteAllRecords($model);
				
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
		
		$this->deleteAllRecords($model);
		
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
		
		$this->deleteAllRecords($model);
		
		// Location of CSV file
		$file	=	'import/applic.csv';
				
		$csv	=	new Varien_File_Csv();
		
		// Set delimiter to "\"
		$csv->setDelimiter('|');
		
		// Set delimiter to "\"
		$csv->setDelimiter('|');
		
		// Load data from CSV file
		$data	=	$csv->getData($file);
		
		$count	=	0;
		
		foreach( $data as $item ) {
					
			$model->setData(
				array(
					'vehicle_id'		=>		$item[0],
					'group'				=>		$item[1],
					'subgroup'			=>		$item[4],
					'menu_heading'		=>		$item[5],
					'applic'			=>		$item[3],
					'part_number'		=>		$item[6]
				)		    	
			);
			
			$model->save();
			
			$count++;
		}
		
		echo 'Applic: sync items > ';
		echo $count;
		echo '<br/>';
	
	}
}
