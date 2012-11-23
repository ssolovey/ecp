<?php
class Reman_Sync_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		echo 'Reman sync module.</br>';

		$this->loadProductsData();
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
			$this->getProduct($item);			
		}
	}

	/**
	 * Get product by SKU
	 *
	 * @param array $data
	 */
	private function getProduct($data) 
	{
		$product = Mage::getModel('catalog/product');

		$_Pdetails = $product->loadByAttribute( 'sku', $data[0] );

		if ( $_Pdetails ) {
			$this->updateProductAttributes($_Pdetails, $data);
		} else {
			$this->addProduct($product, $data);			
		}
	}

	/**
	 * Add new product
	 *
	 * @param Mage_Catalog_Model_Abstract $product
	 * @param array $data
	 */
	private function addProduct($product, $data)
	{
		$product->setData( 
			array(
				'sku'					=>	$data[0],
				'type_id'				=>	'simple',
				'attribute_set_id'		=>	9,
				'weight'				=>	1.0,
				'status'				=>	1,
				'visibility'			=>	4,
				'category_ids'			=>	array(3),
				'name'					=>	$data[0],
				'description'			=>	'Description',
				'short_description'		=>	'Short description',
				// Parts Warranty
				'parts_commercial_warranty2'	=>	$this->getOptionId( $product->getResource()->getAttribute('parts_commercial_warranty2'), $data[17] ),
				'parts_original_warranty2'		=>	$this->getOptionId( $product->getResource()->getAttribute('parts_original_warranty2'), $data[15] ),
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

		$product->save();
	}

	/**
	 * Update product attributes
	 *
	 * @param Mage_Catalog_Model_Abstract $product
	 * @param array $data
	 */
	private function updateProductAttributes($product, $data)
	{

		$product->setParts_commercial_warranty2(	$this->getOptionId( $product->getResource()->getAttribute('parts_commercial_warranty2'), $data[17] ) );
		$product->setParts_original_warranty2(		$this->getOptionId( $product->getResource()->getAttribute('parts_original_warranty2'), $data[15] ) );

		$product->setPrice(							$data[12] );
		$product->setParts_msrp(					$data[12] );
		$product->setParts_core_price(				$data[13] );

		$product->setParts_fluid_option(			$this->getOptionId( $product->getResource()->getAttribute('parts_fluid_option'), $data[4] ) );
		$product->setParts_fluid_quantity(			$data[5] );

		$product->setParts_start_year(				$data[1] );
		$product->setParts_end_year(				$data[2] );

		$product->setParts_type(					$this->getOptionId( $product->getResource()->getAttribute('parts_type'), $data[3] ) );
		$product->setParts_family(					$data[11] );
		$product->setParts_fuel(					$data[6] );
		$product->setParts_engine(					$data[7] );
		$product->setParts_drive(					$data[8] );
		$product->setParts_cylinder_type(			$data[10] );
		$product->setParts_aspiration(				$data[9] );

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
		if ( $value === '' ) {
			// return default option value
			return $attr->getDefaultValue();
		} else {
			return $attr->getSource()->getOptionId($value);
		}
	}
}