<?php
/**
 * Track IndexController
 *
 * @category    Reman
 * @package     Reman_Track
 * @author		Igor Zhavoronkin (zhavoronkin.i@gmail.com)
 */
class Reman_Track_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();  //This function read all layout files and loads them in memory
        $this->renderLayout(); //This function processes and displays all layout phtml and php files.
    }
	
}
