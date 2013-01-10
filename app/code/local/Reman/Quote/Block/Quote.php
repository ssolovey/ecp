<?php
/**
 * Quote Block
 *
 * @category    Reman
 * @package     Reman_Quote
 * @author		Igor Zhavoronkin (zhavoronkin.i@gmail.com)
 */
class Reman_Quote_Block_Quote extends Mage_Core_Block_Template
{
	
	/** Get Makers names for reman_make model*/
    public function getMake()
    {
		return Mage::getModel('sync/make')->loadMake();
    }
	
}