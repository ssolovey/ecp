<?php
class Reman_Company_Model_Company extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('company/company');  
	}
	
	/**
	 * Get companies list as array
	 *
	 * @return Array
	 */
	public function getCompaniesArray() {
	
		$companies_list = array();
		
		// Add blank select option
		// to forbiddance save user w/o company
		array_push($companies_list, array());
		
		// preform companies array from collection
		foreach ( $this->getCollection() as $item ) {
			array_push($companies_list, array(
					'value'     => $item->company_id,
					'label'     => $item->name
				)
			);
		}
	
		return $companies_list;
	}	
}
