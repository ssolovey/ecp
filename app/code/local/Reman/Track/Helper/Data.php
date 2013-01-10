<?php
/**
 * Track Helper Data
 *
 * @category    Reman
 * @package     Reman_Track
 * @author		Igor Zhavoronkin (zhavoronkin.i@gmail.com)
 */
class Reman_Track_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
     * Retrieve quick quote search URL
     *
     * @return string
     */
	 
    public function getOrderTrackUrl()
    {
        return $this->_getUrl('track');
    }

}
?>