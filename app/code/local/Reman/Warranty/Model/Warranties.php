<?php
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
		
		foreach ( $this->getCollection() as $item ) {
			array_push($warranties_list, array(
					'value'     => $item->warranty_id,
					'label'     => $item->warranty
				)
			);
		}
	
		return $warranties_list;
	}
}
