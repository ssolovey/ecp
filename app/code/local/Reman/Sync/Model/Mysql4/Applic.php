<?php
/**
 * Sync Applic Mysql4
 *
 * @category    Reman
 * @package     Reman_Sync
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Sync_Model_Mysql4_Applic extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('sync/applic',	'applic_id');
		$this->_isPkAutoIncrement = false;
	}
	
	public function trancateTable() {
		$this->_getWriteAdapter()->query("TRUNCATE TABLE `reman_applic`");
	}
	
	/** 
	 * SQL query for select product ID from reman_applic table
	*/
	public function loadProductId($vehicle_id){
				
		$where = $this->_getReadAdapter()->quoteInto("vehicle_id=? AND ", (int)$vehicle_id).$this->_getReadAdapter()->quoteInto("group_number > 0");
		
		$select = $this->_getReadAdapter()->select()->from('reman_applic',array('group_number','menu_heading','applic','applic_id','subgroup'))->where($where)->order('applic');
		
		$result = $this->_getReadAdapter()->fetchAll($select); // run sql query

		// return result
		return $result; 
    }
	
	public function loadProduct ($applic_id)
	{
		
		$where = $this->_getReadAdapter()->quoteInto("applic_id=?", $applic_id);
		
		$select = $this->_getReadAdapter()->select()->from('reman_applic','part_number')->where($where);
		
		$sku = $this->_getReadAdapter()->fetchAll($select); // run sql query
		
		$product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);
		
		if(!$product){
			
			return $product;  // empty object
		
		}else{
			// calculate MSRP Price
			$specialPrices = $this->calculateMSRP($product);
			
			if($specialPrices['msrp'] != ""){
				 $product->setData('price',$specialPrices['msrp']);
			}
			
			if($specialPrices['core'] != ""){
				 $product->setData('parts_core_price',$specialPrices['core']);
			}
					
			 return $product;
		}
	}
	
	/**
	 * Calculate MSRP Price for current logged user 
	 * according to its Splink
	 * @return int
	*/
	
	public function calculateMSRP($prod){
		
		// value for MSRP and CORE prices
		$values = array(
    		"msrp" => "",
    		"core" => "",
		);
		
		
		// Customer Splink value
		$customer_splink = Mage::helper('company')->getGSPLink();
		// Customer Discount value
		$customer_discount = Mage::helper('company')->getDiscount();
		// Get Special Price for selected product if exist
		$spPrice = Mage::getModel('sync/gsp')->loadSp($prod->getData('sku'), $customer_splink);
		
		if(sizeof($spPrice) == 0)
		{
			// calculate discount rate 
			$inverseDiscValue =(100 - $customer_discount) / 100;
			
			// calculated msrp value
			$msrp =  round($prod->getData('parts_msrp') * $inverseDiscValue);
			// assing calculated msrp value
			$values["msrp"] = $msrp; 
			// return int
			return $values;
		}else
		{
			/**
			 * Get Price and Core price for current useer with valid Splink and SKU
			 *
			*/
			$values["msrp"]= $spPrice[0]['price'];
			
			$values["core"]= $spPrice[0]['core'];
			
			return $values;
		}
			
	}
}
