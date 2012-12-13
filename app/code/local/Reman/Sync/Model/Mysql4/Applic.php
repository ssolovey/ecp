<?php
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
		
		$select = $this->_getReadAdapter()->select()->from('reman_applic',array('group_number','menu_heading','applic','applic_id','subgroup'))->where($where);
		
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
		
		
		$specialPrices = $this->calculateMSRP($product);
		
		if($specialPrices['msrp'] != ""){
			 $product->setData('parts_msrp',$specialPrices['msrp']);
		}
		
		if($specialPrices['core'] != ""){
			 $product->setData('parts_core_price',$specialPrices['core']);
		}
		
		// return result
		return $product;
	
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
		
		// Customer object
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		// Customer Splink value
		$customer_splink = $customer->getSplink();
		// Customer Discount value
		$customer_discount = $customer->getDiscount();
		// Get Special Price for selected product if exist
		$spPrice = Mage::getModel('sync/gsp')->loadSp($prod->getData('sku'), $customer_splink);
		
		
		if(is_null($customer_splink) || sizeof($spPrice) == 0)
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
			
			$values["msrp"]= $spPrice['price'];
			
			$values["core"]= $spPrice['core'];
			
			return $values;
		}
			
	}
	
}
