<?php
/**
 * Model for Parts and Inventories
 *
 * @category    Reman
 * @package     Reman_Sync
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Sync_Model_Parts extends Reman_Sync_Model_Product
{	
	// override
	protected function _parseItem( $item )
	{
		$product = $this->_getProductBySku( $item[0] );
	
		if ( $product ) {
			$this->_updateProductAttributes($product, $item);
		} else {
			$this->_addProduct($this->_products, $item);			
		}		
	}
	
		
	// override
	public function syncData()
	{	
		$this->_loadFile( 'PARTS.TXT' );
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
				'parts_commercial_warranty'	=>	$data[17],
				'parts_original_warranty'		=>	$data[15],
				//'parts_commercial_warranty2'	=>	$this->getOptionId( $product->getResource()->getAttribute('parts_commercial_warranty2'), $data[17] ),
				//'parts_original_warranty2'		=>	$this->getOptionId( $product->getResource()->getAttribute('parts_original_warranty2'), $data[15] ),
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
		$product->setParts_commercial_warranty(		$data[17] );
		$product->setParts_original_warranty(		$data[15] );
		//$product->setParts_commercial_warranty2(	$this->getOptionId( $product->getResource()->getAttribute('parts_commercial_warranty2'), $data[17] ) );
		//$product->setParts_original_warranty2(		$this->getOptionId( $product->getResource()->getAttribute('parts_original_warranty2'), $data[15] ) );

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
			if ( $attr->getIs_required() ) {
				// return default option value
				return $attr->getDefaultValue();
			} else {
				return '';
			}
		} else {
			
			foreach ( $attr->getSource()->getAllOptions() as $option) {
            	if (strcasecmp($option['label'], $value)==0 ) {
                	return $option['value'];
            	}
        	}
			
			//return $attr->getSource()->getOptionId($value);
		}
	}
}
