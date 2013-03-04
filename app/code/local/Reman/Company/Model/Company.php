<?php
/**
 * Company Model for Reman_Company module
 *
 * @category    Reman
 * @package     Reman_Company
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Company_Model_Company extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('company/company');  
	}
	
	/**
	 * Return company admin ID
	 *
	 * @return Number
	 */
	public function getCompanyAdmin()
	{
		
		
		
		return;
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
	
	/**
	 * Get companies list as array for grid option
	 *
	 * @return Array
	 */
	public function getCompaniesOptions() {
	
		$companies_option = array();
		
		foreach ( $this->getCollection() as $item ) {
			$companies_option[$item->company_id] = $item->name;
		}
	
		return $companies_option;
	}
	
	/**
	 * Export CSV file on Company information update
	 */
	public function exportCompanyUpdate($model, $admin) {
	
		
		// find company admin if nessesarry
		if (!$admin) {
		
			$customers = Mage::getModel('customer/customer')->getCollection();
	
			foreach ( $customers as $customer_data ) {
				$customer_model =  Mage::getModel('customer/customer')->load($customer_data->getId());
				
				if ( $customer_model->getCompany() == $model->company_id ) {
										
					if ( $customer_model->getGroup_id() == 6 ) {
						$admin = $customer_model;
					}
				}				
			}
		}
		
		$file = 'ftpex/Upload/Customers/' . $model->getEte() . '.TXT';
		$delim = "|";
		
		$status = $model->status == 1 ? 'ACTIVE' : 'DELETE';
		
		$warranties = Mage::getModel('warranty/warranties');
		
		$fh = fopen($file, 'w');
		
		$stringData = $model->ete . $delim
			. '"' . $model->name . '"' . $delim
			. '"' . $model->addr1  .'"' . $delim
			. '"' . $model->addr2  .'"' . $delim
			. '"' . $model->city  .'"' . $delim
			. '"' . $model->state  .'"' . $delim
			. '"' . $model->zip  .'"' . $delim
			. '"' . $model->tax  .'"' . $delim
			. '""' . $delim
			. $model->discount . $delim
			. '"' . $model->splink  .'"' . $delim
			. '"' . $model->fluid  .'"' . $delim
			. '"' . $admin->firstname .'"' . $delim //admin name
			. '"' . $admin->email .'"' . $delim //admin email
			. '"' . $admin->phone .'"' . $delim //admin tel
			. '"' . $admin->ext .'"' . $delim //admin ext
			. '"' . $model->ship  .'"' . $delim
			. '"' . $warranties->load($model->at_war)->warranty  .'"' . $delim
			. $model->at_war . $delim
			. '"' . $model->at_gswlink  .'"' . $delim
			. '"' . $warranties->load($model->tc_war)->warranty  .'"' . $delim
			. $model->tc_war . $delim
			. '"' . $model->tc_gswlink  .'"' . $delim
			. '"' . $warranties->load($model->di_war)->warranty  .'"' . $delim
			. $model->di_war . $delim
			. '"' . $model->di_gswlink  .'"' . $delim
			. '"' . $model->payment  .'"' . $delim
			. '"' . $status . '"'
			. "\n";
		
		fwrite($fh, $stringData);
		fclose($fh);
	}
}
