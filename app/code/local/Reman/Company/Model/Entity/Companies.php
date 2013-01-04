<?php
/**
 * Company Entity Attribute
 * used to display Company dropdown option in magento customers profile
 *
 * @category    Reman
 * @package     Reman_Company
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Company_Model_Entity_Companies extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
	public function getAllOptions()
	{
		if ($this->_options === null) {
			// get companies from model
			$companiesArray = Mage::getModel('company/company')->getCompaniesArray();
			
			// insert companies array to options list
			$this->_options = $companiesArray;
		}
		return $this->_options;
	}
}