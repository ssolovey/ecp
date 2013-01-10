<?php
/**
 * Quote Helper Data
 *
 * @category    Reman
 * @package     Reman_Quote
 * @author		Igor Zhavoronkin (zhavoronkin.i@gmail.com)
 */
class Reman_Quote_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
     * Retrieve quick quote search URL
     *
     * @return string
     */
	 
    public function getQuickQuoteUrl()
    {
        return $this->_getUrl('quote');
    }

}
?>