<?php

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