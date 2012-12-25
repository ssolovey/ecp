<?php
class Reman_Company_Block_Adminhtml_Company_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('company_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('company')->__('Company Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('company')->__('Company Information'),
          'title'     => Mage::helper('company')->__('Company Information'),
          'content'   => $this->getLayout()->createBlock('company/adminhtml_company_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}