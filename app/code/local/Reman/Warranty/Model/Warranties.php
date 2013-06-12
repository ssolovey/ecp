<?php
/**
 * Reman Warranty Warranties Model
 *
 * @category    Remah
 * @package     Reman_Warranty
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Warranty_Model_Warranties extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('warranty/warranties');
	}
	
	/**
	 * Get warranties list as array
	 * data preformed from database 
	 *
	 * @return Array
	 */
	public function getWarrantiesArray() {
		
		$warranties_list = array();
		
		// Add blank select option
		array_push($warranties_list, array());
		
		foreach ( $this->getCollection() as $item ) {
			array_push($warranties_list, array(
					'value'     => $item->warranty_id,
					'weight'    => $item->value,
					'label'     => $item->warranty
				)
			);
		}
	
		return $warranties_list;
	}
	
	
	/**
	 * Get warranty waight value
	 * data preformed from database 
	 *
	 * @return string
	 */
	public function getWarrantyWeight($id) {
	
		foreach ( $this->getCollection() as $item ) {
			
			if($id == $item->warranty_id)
			{
				return $item->value;
			}
		}
	}
	
	/**
	 * Get warranty id by text name
	 *
	 * @param Warranty text value
	 * @return int
	 */
	public function getIdByName( $param )
	{
		return $this->getCollection()
			->addFieldToFilter('warranty', $param)
			->getFirstItem()
			->getId();
	}
}
