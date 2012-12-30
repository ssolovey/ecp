<?php
/**
 * Customer Entity Attribute
 * used to display Status dropdown option in magento customers profile
 *
 * @category    Reman
 * @package     Reman_Customers
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Customers_Model_Entity_State extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
	public function getAllOptions()
	{
		if ($this->_options === null) {
			$this->_options = array();
				
			$this->_options[] = array(
				'value'     => 0,
				'label'     => 'Inactive'
			);
			
			$this->_options[] = array(
				'value'     => 1,
				'label'     => 'Active'
			);
		}
		return $this->_options;
	}
}
