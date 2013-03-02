<?php
/**
 * Product Model for Reman Sync module
 *
 * @category    Reman
 * @package     Reman_Sync
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Sync_Model_Product extends Reman_Sync_Model_Abstract
{	
	protected $_products;
	
	public function _construct()
	{
		parent::_construct();
		
		$this->_products	=	Mage::getModel('catalog/product');
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
}
