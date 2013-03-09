<?php
/**
 * @category    Reman
 * @package     Reman_Warranty
 * @author		Igor Zhavoronkin (zhavoronkin.i@gmail.com)
 */
class Reman_Warranty_Helper_Data extends Mage_Core_Helper_Abstract
{
	
	/**
     * Retrieve current logged user Base Warranty Lable
     * 
	 *@description
	 * Calculating the base warranty. The fields used are relevant to the unit type (Automatic
	 * Transmission xx=AT or Transfer Case xx=TC or Differential xx=DI) All warranty values
	 * are retrieved from the “Warranty” File by the respective warranty codes.
	 *	i. The base warranty (BW) is in the customer file. ([xxBasewar] field) The base warranty
	 *		Code (BWC) is the [xxBasewarCode] field.
	 * ii. If the [Orwar] field is populated in the part file, AND the [Orwarval] field is less than
	 *		the [xxBasewarval] field, then the values retrieved from the Warranty File become the
	 *		BW and the BWV.
	 * 
     * @return string
     */
    public function getBaseWarranty($case,$product)
    {
       	$company = Mage::helper('company')->getCustomerCompanyObject();
		
		if($case == "T"){
			$warrantyID = $company->at_war;
		}else if($case == "X"){
			$warrantyID = $company->tc_war;
		}
		
		// Warranty Array
		$warrantyArray = Mage::getModel('warranty/warranties')->getWarrantiesArray();
		// Parts Original Warranty value
		$parts_original_warrantyId = $product->getData('parts_original_warranty');
		
		if(is_null($parts_original_warrantyId))
		{
			foreach($warrantyArray as $index){
				if( $index['value'] == $warrantyID){ // return Base Warranty Value from comapny profile if parts original is empty
					return $index['label'];
				}
			}
		}else{
			
			// Company Warranty Weight value
			$company_warranty_weight = Mage::getModel('warranty/warranties')->getWarrantyWeight($warrantyID);
			// Product Warranty Weight value
			$part_warranty_weight = Mage::getModel('warranty/warranties')->getWarrantyWeight($parts_original_warrantyId);
			
			if($part_warranty_weight < $company_warranty_weight)
			{
				foreach($warrantyArray as $index){
					if( $index['value'] == $parts_original_warrantyId){
						return  $index['label'];	// Parts Warranty Became Base Warranty
					}
				}
			}else{
				
				foreach($warrantyArray as $index){
					if( $index['value'] == $warrantyID){
						return  $index['label'];  // Company Warranty Became Base Warranty
					}
				}
			}	
		}
    }
}
