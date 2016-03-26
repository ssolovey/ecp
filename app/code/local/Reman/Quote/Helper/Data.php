<?php
/**
 * Quote Helper Data
 * Retrieve quick quote page URL
 * @category    Reman
 * @package     Reman_Quote
 * @author		Igor Zhavoronkin (zhavoronkin.i@gmail.com)
 */
class Reman_Quote_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
     * Retrieve quick quote page URL
     *
     * @return string
     */
	 
    public function getQuickQuoteUrl()
    {
        return $this->_getUrl('quote');
    }


    //Flued Options
    /*
    * Parts Value: 7 = Required , 6 = Optional , 5 = None
    * Company VAlue: R = Required , O = Optional , N = None
    *
    */
    /* Requirement:
    *
    *  a. We do not need to show fluid capacity for Transfer casesß
    *
    *  b. If the part number is hard keyed to show Required always show the capacity and “included” for the price (i.e. cft30s)
    *
    *  c.  Otherwise, use the customer’s file to determine if fluid shows.
    *      Required and optional should always show capacity and cost.  None should not.
    */
    public function getFluidPrice($_company, $_product){

        $fluidAmountPrice = 5;

        $fluid = $_company->fluid;

        if($_product->parts_fluid_option == 7 || (($fluid == "R") || ($fluid == "O")) ){

            return $_product->getData('parts_fluid_quantity')*$fluidAmountPrice;

        }else{

            return false;

        }

    }

    public function getTotalInventoryPrice($_company,$_product,$tax)
    {

       return  $_product->getPrice() + Mage::helper('company')->getCustomerShippingPrice() + $_product->getData('parts_core_price')+ $this->getFluidPrice($_company,$_product);

    }

}
