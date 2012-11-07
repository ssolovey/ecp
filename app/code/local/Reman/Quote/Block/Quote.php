<?php
class Reman_Quote_Block_Quote extends Mage_Core_Block_Template
{
    public function getContent()
    {
		
        return Mage::getModel('sync/make')->load(1)->getMake();
    }
}