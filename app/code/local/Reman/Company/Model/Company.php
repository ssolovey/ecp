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
	 * Export CSV file on Company information update
	 */
	public function exportCompanyUpdate($model) {
		
		$file = 'export/CUST-' . $model->getEte() . '.TXT';
		$delim = "|";
		
		$status = $model->status == 1 ? 'ACTIVE' : 'DELETE';
		
		$warranties = Mage::getModel('warranty/warranties');
		
		$fh = fopen($file, 'w');
		/*
"12 Months/12,000 Miles"|14|"82259"|"12 Months/12,000 Miles"|14|"12 Months/12,000 Miles"|14|"82259"|"ACCT"|"ACTIVE"
*/
		
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
			. '"' . '' .'"' . $delim //admin name
			. '"' . '' .'"' . $delim //admin email
			. '"' . '' .'"' . $delim //admin tel
			. '"' . '' .'"' . $delim //admin ext
			. '"' . $model->ship  .'"' . $delim
			. $model->at_war . $delim
			. '"' . $warranties->load($model->at_war)->warranty  .'"' . $delim
			. '"' . $model->at_gswlink  .'"' . $delim
			. $model->tc_war . $delim
			. '"' . $warranties->load($model->tc_war)->warranty  .'"' . $delim
			. '"' . $model->tc_gswlink  .'"' . $delim
			. $model->di_war . $delim
			. '"' . $warranties->load($model->di_war)->warranty  .'"' . $delim
			. '"' . $model->di_gswlink  .'"' . $delim
			. '"' . $model->payment  .'"' . $delim
			. '"' . $status . '"' . $delim
			. "\n";
		
		fwrite($fh, $stringData);
		fclose($fh);
	}
}
