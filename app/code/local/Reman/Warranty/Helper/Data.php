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
    public function getBaseWarrantyId($case,$product)
    {
       	$company = Mage::helper('company')->getCustomerCompanyObject();
		
		if($case == "T"){
			$warrantyID = $company->at_war;
		}else if($case == "X"){
			$warrantyID = $company->tc_war;
		}
		
		// Warranty Array
		$warrantyArray = Mage::getModel('warranty/warranties')->getWarrantiesArray();
		// Parts Original Warranty ID
		$parts_original_warrantyId = $product->getData('parts_original_warranty');
		
		if(is_null($parts_original_warrantyId))
		{
			foreach($warrantyArray as $index){
				if( $index['value'] == $warrantyID){ // return Base Warranty Value from comapny profile if parts original is empty
					//return $index['label'];
					return $index['value'];
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
						return  $index['value'];	// Parts Warranty Became Base Warranty
					}
				}
			}else{
				
				foreach($warrantyArray as $index){
					if( $index['value'] == $warrantyID){
						return  $index['value'];  // Company Warranty Became Base Warranty
					}
				}
			}	
		}
    }
	
	public function getBaseWarrantyLabel($case,$product){
		// Warranty Array
		$warrantyArray = Mage::getModel('warranty/warranties')->getWarrantiesArray();
		$warrantyId = $this->getBaseWarrantyId($case,$product);
		foreach($warrantyArray as $index){
			if( $index['value'] == $warrantyId){
				return  $index['label'];
			}
		}
	}
	
	
	public function getAdditionalWarranty($gsw_link, $type){
		
		$AdditionalWarrantyIds = Mage::getModel('sync/gsw')->loadWarranryID($gsw_link, $type); 
		
		$AdditionalWarrantyWeights = array();
	
		foreach($AdditionalWarrantyIds as $index){
			array_push($AdditionalWarrantyWeights, Mage::getModel('warranty/warranties')->getWarrantyWeight($index['warranty_id']));
		}
		
		return $AdditionalWarrantyWeights;
	
	}
	
	
	
	
	
	
	/* 
	 * Get Calculated Warrantry List according to Reman documentation
	 * Return BaseWarranty + Additional warranties if their weight less than Base Warranty	
	 * @return Array
	 *
	*/
	
	public function getCalculatedWarrantyList($partType,$_product){
		// Current Logged Company
		$company = Mage::helper('company')->getCustomerCompanyObject();
		// Waaranty Collection
		$warrantyArray = Mage::getModel('warranty/warranties')->getWarrantiesArray();
		// Calculated Base Warranty Id
		$warrantyId =$this->getBaseWarrantyId($partType,$_product);
		//Get BaseWarrantyWeightID
		$warrantyWeightID = Mage::getModel('warranty/warranties')->getWarrantyWeight($warrantyId);
		// Parts Commercial Warranty ID
		$parts_commercial_warrantyId = $_product->getData('parts_commercial_warranty');
		// Parts Commercial Warranty Weight ID
		$parts_commercial_warranty_weightId = Mage::getModel('warranty/warranties')->getWarrantyWeight($parts_commercial_warrantyId);
		
		$warranties = array();
		
		switch($partType){
			case 'T':{
				$GSW_link = $company->at_gswlink;
				$type = 'AUTO TRANS';
				break;
			}
			case 'X':{
				$GSW_link = $company->tc_gswlink;
				$type = 'TRANSFER CASE';
				break;
			}
		}
		
		
	
		/* Form Calculated Warranty list*/
	
		foreach ($warrantyArray as $item) {
			
			if($item['weight'] == $warrantyWeightID){ //Base Warranty
					
				array_push($warranties , $item);		
			}
			
			if($warrantyWeightID > $parts_commercial_warranty_weightId){ // If Commercial Warranty weight less than BaseWarranty 
					
				if($item['weight'] == $parts_commercial_warranty_weightId){ //Commercial Warranty
				
						array_push($warranties , $item);		
				}
			}
		
			foreach($this->getAdditionalWarranty($GSW_link,$type) as $additemWeight){ // Add all Additional warranties if they les than base warranty
					
				if($warrantyWeightID > $additemWeight){
				
					if($item['weight'] == $additemWeight){
					
						array_push($warranties , $item);
					
					}
								
				}
			}	
		}
		
		
		return $warranties;
	}
	
	
}
