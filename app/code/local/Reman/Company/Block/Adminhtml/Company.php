<?php
class Reman_Company_Block_Adminhtml_Company extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'adminhtml_company';
		$this->_blockGroup = 'company';
		$this->_headerText = Mage::helper('company')->__('Manage Companies');
		$this->_addButtonLabel = Mage::helper('company')->__('Add Company');
		parent::__construct();
	}
}
