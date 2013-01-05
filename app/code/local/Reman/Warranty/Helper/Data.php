<?php
/**
 * @category    Reman
 * @package     Reman_Warranty
 * @author		Igor Zhavoronkin (zhavoronkin.i@gmail.com)
 */
class Reman_Warranty_Helper_Data extends Mage_Core_Helper_Abstract
{
	
	/**
     * Retrieve current logged user Original Warranty
     *
     * @return string
     */
    public function getOriginalWarranty($case)
    {
       	$company = Mage::helper('company')->getCustomerCompanyObject();
		
		if($case == "T"){
			
			$warrantyID = $company->at_war;
		
		}else if($case == "X"){
		
			$warrantyID = $company->tc_war;
		}
		
		// Warranty Array
		$warrantyArray = Mage::getModel('warranty/warranties')->getWarrantiesArray();

		foreach($warrantyArray as $index){
			if( $index['value'] == $warrantyID){
				return $index['label'];
			}
		}
    }

}
