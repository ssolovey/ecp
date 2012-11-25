<?php
class Reman_Sync_Model_Parts extends Mage_Core_Model_Abstract
{

	protected $_products;
	
	public function _construct()
	{
		parent::_construct();
		
		$this->_products	=	Mage::getModel('catalog/product');
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
			
			$product = $this->_getProductBySku( $item[0] );

			if ( $product ) {
				$this->_updateProductAttributes($product, $item);
			} else {
				$this->_addProduct($this->_products, $item);			
			}
		}
	}
	
	/**
	 * Load inventory data form CSV file
	 *
	 */
	public function loadInventoryData() {

		// Location of CSV file
		$file	=	'import/inven.csv';

		$csv	=	new Varien_File_Csv();

		// Set delimiter to "\"
		$csv->setDelimiter('|');

		// Load data from CSV file
		$data	=	$csv->getData($file);
				
		foreach( $data as $item ) {			
			$this->_updateInventory($item);
		}
	}
	
	/**
	 * Get product by SKU
	 *
	 * @param string $sku
	 */
	protected function _getProductBySku($sku)
	{		
		$product = $this->_products->loadByAttribute( 'sku', $sku );
		
		if ( $product ) {
			return $product;
		} else {
			return false;		
		}
	}
	
	/**
	 * Update product inventory
	 *
	 * @param array $data
	 */
	protected function _updateInventory($data)
	{		
		$product	=	$this->_getProductBySku( $data[0] );
		$totalStock	=	0;
		
		
		if ( $product ) {
			
			$stockData	=	array(
				'parts_inventory_nw'	=>	$data[1],
				'parts_inventory_nc'	=>	$data[2],
				'parts_inventory_ne'	=>	$data[3],
				'parts_inventory_mc'	=>	$data[4],
				'parts_inventory_me'	=>	$data[5],
				'parts_inventory_sw'	=>	$data[6],
				'parts_inventory_sc'	=>	$data[7],
				'parts_inventory_se'	=>	$data[8],
				'parts_inventory_ip'	=>	$data[9]
			);
			
			foreach ( $stockData as $key=>$value ) {
				if ( $value ) {
					$product->setData( $key, $value );
					$totalStock = $totalStock + $value;
				} else {
					$product->setData( $key, 0 );
				}
			}
			
			$product->save();
			
			
			$productId = $product->getId();
			$stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
			$stockItemId = $stockItem->getId();
			
			//$stockItem->setData('manage_stock', 1);
			$stockItem->setData('qty', $totalStock);
			
			
			$stockItem->save();
		}
	}

	/**
	 * Add new product
	 *
	 * @param Mage_Catalog_Model_Abstract $product
	 * @param array $data
	 */
	protected function _addProduct($product, $data)
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
				'name'					=>	$data[3] . ' - ' . $data[0],
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
				'qty'					=>	0
			)
		);
		
		// assign product to the default website
		$product->setWebsiteIds(array(Mage::app()->getStore(true)->getWebsite()->getId()));
		
		$product->save();
	}

	/**
	 * Update product attributes
	 *
	 * @param Mage_Catalog_Model_Abstract $product
	 * @param array $data
	 */
	protected function _updateProductAttributes($product, $data)
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
