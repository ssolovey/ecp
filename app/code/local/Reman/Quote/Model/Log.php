<?php
/**
 * Logger for Quote
 *
 * @category    Reman
 * @package     Reman_Quote
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Quote_Model_Log extends Mage_Core_Model_Abstract
{	
	/**
	 * Log message in quotelog
	 *
	 * @param year:string
	 * @param make:string
	 * @param model:string
	 * @param applic:string
	 * @param partnum:string
	 */
	public function send($year, $make, $model, $applic, $partnum)
	{
		
		$user = Mage::getSingleton('customer/session')->getCustomer();
		$company = Mage::getModel('company/company')->load( $user->company );
		$delim = ",";
		
		if ( $partnum == "" ) {
			$partnum = 'N/A';
		}
		
		$myFile = "export/quotelog.csv";
		$fh = fopen($myFile, 'a');
		
		$stringData = date('Y.m.d h:i A') . $delim
			. '"' . $company->name . '"' . $delim
			. '"' . $user->email . '"' . $delim
			. '"' . $user->firstname . '"' . $delim
			. '"' . $user->lastname . '"' . $delim
			. '"' . $make . '"' . $delim
			. '"' . $year . '"' . $delim
			. '"' . $model . '"' . $delim
			. '"' . $applic . '"' . $delim
			. '"' . $partnum . '"'
			. "\n";
		
		fwrite($fh, $stringData);
		fclose($fh);
   	}
}
