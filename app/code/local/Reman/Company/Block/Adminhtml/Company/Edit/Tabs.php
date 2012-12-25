<?php
class Reman_Company_Block_Adminhtml_Company_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('company_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('company')->__('Company'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('general_section', array(
          'label'     => Mage::helper('company')->__('General'),
          'title'     => Mage::helper('company')->__('Company Information'),
          'content'   => $this->getLayout()->createBlock('company/adminhtml_company_edit_tab_general')->toHtml(),
      ));
      
      $this->addTab('address_section', array(
          'label'     => Mage::helper('company')->__('Address'),
          'title'     => Mage::helper('company')->__('Address Information'),
          'content'   => $this->getLayout()->createBlock('company/adminhtml_company_edit_tab_address')->toHtml(),
      ));
      
      $this->addTab('warranty_section', array(
          'label'     => Mage::helper('company')->__('Warranty'),
          'title'     => Mage::helper('company')->__('Warranty Information'),
          'content'   => $this->getLayout()->createBlock('company/adminhtml_company_edit_tab_warranty')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}