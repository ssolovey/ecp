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
	// model log name
	protected $_logid = 'parts';
	
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
        if (
            !isset($item[0])
            || !isset($item[3])
            || !isset($item[17])
            || !isset($item[15])
            || !isset($item[12])
            || !isset($item[13])
            || !isset($item[4])
            || !isset($item[5])
            || !isset($item[1])
            || !isset($item[2])
            || !isset($item[11])
            || !isset($item[6])
            || !isset($item[7])
            || !isset($item[8])
            || !isset($item[10])
            || !isset($item[9])
        ) {
            throw new Exception('Broken CSV file format.');
        }

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
        if (
            !isset($item[0])
            || !isset($item[3])
            || !isset($item[17])
            || !isset($item[15])
            || !isset($item[12])
            || !isset($item[13])
            || !isset($item[4])
            || !isset($item[5])
            || !isset($item[1])
            || !isset($item[2])
            || !isset($item[11])
            || !isset($item[6])
            || !isset($item[7])
            || !isset($item[8])
            || !isset($item[10])
            || !isset($item[9])
        ) {
            throw new Exception('Broken CSV file format.');
        }

		$needsUpdate = false;
		if ($product->getParts_commercial_warranty() != $data[17]) {
			$product->setParts_commercial_warranty($data[17]);
			$needsUpdate = true;
		}
		if ($product->getParts_original_warranty() != $data[15]) {
			$product->setParts_original_warranty($data[15]);
			$needsUpdate = true;
		}
		//$product->setParts_commercial_warranty2(	$this->getOptionId( $product->getResource()->getAttribute('parts_commercial_warranty2'), $data[17] ) );
		//$product->setParts_original_warranty2(		$this->getOptionId( $product->getResource()->getAttribute('parts_original_warranty2'), $data[15] ) );

		if ($product->getPrice() != $data[12]) {
			$product->setPrice($data[12]);
			$needsUpdate = true;
		}
		if ($product->getParts_msrp() != $data[12]) {
			$product->setParts_msrp($data[12]);
			$needsUpdate = true;
		}
		if ($product->getParts_core_price() != $data[13]) {
			$product->setParts_core_price($data[13]);
			$needsUpdate = true;
		}

		$fluidOptionId = $this->getOptionId( $product->getResource()->getAttribute('parts_fluid_option'), $data[4] );

		if ($product->getParts_fluid_option() != $fluidOptionId) {
			$product->setParts_fluid_option($fluidOptionId);
			$needsUpdate = true;
		}
		if ($product->getParts_fluid_quantity() != $data[5]) {
			$product->setParts_fluid_quantity($data[5]);
			$needsUpdate = true;
		}

		if ($product->getParts_start_year() != $data[1]) {
			$product->setParts_start_year($data[1]);
			$needsUpdate = true;
		}
		if ($product->getParts_end_year() != $data[2]) {
			$product->setParts_end_year($data[2]);
			$needsUpdate = true;
		}

		$partsType = $this->getOptionId( $product->getResource()->getAttribute('parts_type'), $data[3] );
		if ($product->getParts_type() != $partsType) {
			$product->setParts_type($partsType);
			$needsUpdate = true;
		}
		if ($product->getParts_family() != $data[11]) {
			$product->setParts_family($data[11]);
			$needsUpdate = true;
		}
		if ($product->getParts_fuel() != $data[6]) {
			$product->setParts_fuel($data[6]);
			$needsUpdate = true;
		}
		if ($product->getParts_engine() != $data[7]) {
			$product->setParts_engine($data[7]);
			$needsUpdate = true;
		}
		if ($product->getParts_drive() != $data[8]) {
			$product->setParts_drive($data[8]);
			$needsUpdate = true;
		}
		if ($product->getParts_cylinder_type() != $data[10]) {
			$product->setParts_cylinder_type($data[10]);
			$needsUpdate = true;
		}
		if ($product->getParts_aspiration() != $data[9]) {
			$product->setParts_aspiration($data[9]);
			$needsUpdate = true;
		}

		if ($needsUpdate) {
			$product->save();
		}
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
