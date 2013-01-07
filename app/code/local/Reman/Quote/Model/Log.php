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
	 */
	public function send($year, $make, $model, $applic)
	{
		
		$user = Mage::getSingleton('customer/session')->getCustomer();
		$company = Mage::getModel('company/company')->load( $user->company );
		$delim = "|";
		
		$myFile = "export/QUOTELOG.TXT";
		$fh = fopen($myFile, 'a');
		
		$stringData = date('Y.n.d h:i A') . $delim
			. '"' . $company->name . '"' . $delim
			. '"' . $user->email . '"' . $delim
			. '"' . $user->firstname . '"' . $delim
			. '"' . $user->lastname . '"' . $delim
			. '"' . $make . '"' . $delim
			. '"' . $year . '"' . $delim
			. '"' . $model . '"' . $delim
			. '"' . $applic . '"'
			. "\n";
		
		fwrite($fh, $stringData);
		fclose($fh);
   	}
}
