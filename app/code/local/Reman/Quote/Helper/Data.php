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

        if($_product->parts_fluid_option == 7 || ($fluid == "R")){

            return $_product->getData('parts_fluid_quantity')*$fluidAmountPrice;

        }else{

            return 0;

        }

    }


    public function getFluidPriceInventory($_company, $_product){

        $fluidAmountPrice = 5;

        $fluid = $_company->fluid;

        if($_product->parts_fluid_option == 7 || ($fluid == "R" || $fluid == "O")){

            return $_product->getData('parts_fluid_quantity')*$fluidAmountPrice;

        }else{

            return 0;

        }

    }


    public function getFluidType($_company, $_product){

        if($_product->parts_fluid_option == 5){

            return 'N';

        }else{

            return $_company->fluid;

        }

    }

    public function getFluidAmount($_product){

        if($_product->parts_fluid_quantity == NULL){

            return 0;

        }else{

            return $_product->parts_fluid_quantity;
        }
    }


    public function getTotalInventoryPrice($_company,$_product){

       return  $_product->getPrice() + Mage::helper('company')->getCustomerShippingPrice() + $_product->getData('parts_core_price')+ $this->getFluidPriceInventory($_company,$_product);

    }


    public function getTotalPrice($_product){

        return  $_product->getPrice() + Mage::helper('company')->getCustomerShippingPrice() + $_product->getData('parts_core_price');

    }


    public function getEngineSizeInfo($_product){


        $apId = Mage::getModel('sync/applic')->getApplicIdBySku($_product->getData('sku'));


        if(isset($apId)){

            $engineSize = Mage::getModel('sync/applic')->getProductEngine($apId);

            if(isset($engineSize)){

                return $engineSize.' L';

            }else{

                return  "";

            }

        }


    }



}
