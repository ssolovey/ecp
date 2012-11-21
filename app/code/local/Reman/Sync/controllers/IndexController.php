<?php
class Reman_Sync_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		echo 'Reman sync module.</br>';
		
		//$this->loadProductsData();
	}
	
	/**
	 * Load products data form CSV file
	 *
	 */
	public function loadProductsData() {
		
		// Location of CSV file
		$file	=	'import/parts.csv';
				
		$csv	=	new Varien_File_Csv();
		
		// Set delimiter to "\"
		$csv->setDelimiter('|');
		
		// Load data from CSV file
		$data	=	$csv->getData($file);
		
		foreach( $data as $item ) {
			
			$this->addProduct($item);			
			//return false;
		}
	} 
	
	/**
	 * Add or update product
	 *
	 * @param array $data
	 */
	private function addProduct($data)
	{
		$product = Mage::getModel('catalog/product');
		
		$product->setData(
			array(
				// general
				'sku'					=>	$data[0],
				'type_id'				=>	'simple',
				'attribute_set_id'		=>	9,
				'name'					=>	$data[0],
				'description'			=>	'Description',
				'short_description'		=>	'Short description',
				'weight'				=>	1.0,
				'status'				=>	1,
				'visibility'			=>	4,
				// Parts prices
				'price'					=>	$data[12],
				'parts_msrp'			=>	$data[12],
				'parts_core_price'		=>	$data[13],
				// Parts fluid
				'parts_fluid_option'	=>	$this->getOptionId( $product->getResource()->getAttribute('parts_fluid_option'), $data[4] ),
				'parts_fluid_quantity'	=>	$data[5],
				// Parts general
				'parts_start_year'		=>	$data[1],
				'parts_end_year'		=>	$data[2],
				'parts_type'			=>	$this->getOptionId( $product->getResource()->getAttribute('parts_type'), $data[3] ),
				'parts_family'			=>	$data[11],
				'parts_fuel'			=>	$data[6],
				'parts_engine'			=>	$data[7],
				'parts_drive'			=>	$data[8],
				'parts_cylinder_type'	=>	$data[10],
				'parts_aspiration'		=>	$data[9]
			)
		);
		
		$product->setStockData(
			array(
				'is_in_stock'			=>	1,
				'qty'					=>	999
			)
		);
		
		// assign product to the default website
		//$product->setWebsiteIds(array(Mage::app()->getStore(true)->getWebsite()->getId()));
		
		$product->save();

	}
		
	/**
	 * Get product attribute dropdown option id
	 *
	 * @param:	Mage_Eav_Model_Entity_Attribute $attr
	 * #param:	string $value
	 * @return:	int  
	 */
	private function getOptionId($attr, $value)
	{
		return $attr->getSource()->getOptionId($value);
	}
}
