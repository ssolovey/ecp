<?php
/**
 * Model for Parts and Inventories
 *
 * @category    Reman
 * @package     Reman_Sync
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Sync_Model_Inventory extends Reman_Sync_Model_Product
{
	// model log name
	protected $_logid = 'inventory';
	
	// override
	protected function _parseItem( $item )
	{
		$this->_updateInventory($item);		
	}
	
		
	// override
	public function syncData()
	{	
		$this->_loadFile( 'INVEN.TXT' );
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

            $productId = $product->getId();
            $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
            $qty = $stockItem->getData('qty');

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
			$needUpdate = false;
			foreach ( $stockData as $key=>$value ) {
				$currentValue = $product->getData($key);

                //var_dump('currentValue: '.$currentValue.' value '.$value);

				if ($currentValue != $value || $qty == 0 ) {
					$needUpdate = true;
					if ($value) {
						$product->setData($key, $value);
						$totalStock = $totalStock + $value;
					} else {
						$product->setData($key, 0);
					}
				}
			}

			if ($needUpdate) {
				$product->save();
			}
            

			$needUpdate = false;
			if ($stockItem->getData('manage_stock') != 1) {
				$stockItem->setData('manage_stock', 1);
				$needUpdate = true;
			}
			$isInStock = $totalStock>0 ? 1 : 0;
			if ($stockItem->getData('is_in_stock') != $isInStock) {
				$stockItem->setData('is_in_stock', $isInStock);
				$needUpdate = true;
			}



			if ($stockItem->getData('qty') != $totalStock) {
				$stockItem->setData('qty', $totalStock);
				$needUpdate = true;
			}
			if ($needUpdate) {
				$stockItem->save();
			}
		}
	}
}
