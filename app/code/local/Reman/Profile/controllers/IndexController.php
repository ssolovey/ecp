<?php
/**
 * Profile IndexController 
 *
 * @category    Reman
 * @package     Reman_Profile
 * @author		Igor Zhavoronkin (zhavoronkin.i@gmail.com)
 */
class Reman_Profile_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();  //This function read all layout files and loads them in memory
        $this->renderLayout(); //This function processes and displays all layout phtml and php files.
    }
}
