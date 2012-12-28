<?php
class Reman_Company_Block_Adminhtml_Company_Edit_Tab_Warranty extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
  
  		$warrantiesArray = Mage::getModel('warranty/warranties')->getWarrantiesArray();
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('warranty_form', array('legend'=>Mage::helper('company')->__('Warranty information')));     
      
      $fieldset->addField('tc_war', 'select', array(
          'label'     => Mage::helper('company')->__('Transfer Case'),
          'name'      => 'tc_war',
          'values'    => $warrantiesArray
      ));
      
      $fieldset->addField('tc_gswlink', 'text', array(
          'label'     => Mage::helper('company')->__('Transfer Case GSW Link'),
          'required'  => false,
          'name'      => 'tc_gswlink'
      ));
      
      $fieldset->addField('at_war', 'select', array(
          'label'     => Mage::helper('company')->__('Auto Transmission'),
          'name'      => 'at_war',
          'values'    => $warrantiesArray
      ));
      
      $fieldset->addField('at_gswlink', 'text', array(
          'label'     => Mage::helper('company')->__('Auto Transmission GSW Link'),
          'required'  => false,
          'name'      => 'at_gswlink'
      ));
      
      $fieldset->addField('di_war', 'select', array(
          'label'     => Mage::helper('company')->__('Differential'),
          'name'      => 'di_war',
          'values'    => $warrantiesArray
      ));
      
      $fieldset->addField('di_gswlink', 'text', array(
          'label'     => Mage::helper('company')->__('Differential GSW Link'),
          'required'  => false,
          'name'      => 'di_gswlink'
      ));
           
      if ( Mage::getSingleton('adminhtml/session')->getWebData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getWebData());
          Mage::getSingleton('adminhtml/session')->setWebData(null);
      } elseif ( Mage::registry('company_data') ) {
          $form->setValues(Mage::registry('company_data')->getData());
      }
      return parent::_prepareForm();
  }
}