<?php

class Reman_Profile_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
     * Retrieve quick quote search URL
     *
     * @return string
     */
	 
    public function getProfileUrl()
    {
        return $this->_getUrl('profile');
    }

}
?>