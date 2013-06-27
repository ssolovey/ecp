<?php
/**
 * Profile Helper Data
 * Profile Helper function
 * @category    Reman
 * @package     Reman_Profile
 * @author		Igor Zhavoronkin (zhavoronkin.i@gmail.com)
 */
class Reman_Profile_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
     * Retrieve profile page URL
     *
     * @return string
     */
	 
    public function getProfileUrl()
    {
        return $this->_getUrl('profile');
    }

}
?>