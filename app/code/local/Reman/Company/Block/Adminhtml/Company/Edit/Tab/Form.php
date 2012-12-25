<?php
class Reman_Company_Block_Adminhtml_Company_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('web_form', array('legend'=>Mage::helper('company')->__('Company information')));
     
      $fieldset->addField('name', 'text', array(
          'label'     => Mage::helper('company')->__('Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'name'
      ));
      
      $fieldset->addField('addr1', 'text', array(
          'label'     => Mage::helper('company')->__('Address 1'),
          'required'  => false,
          'name'      => 'addr1'
      ));
      
      $fieldset->addField('addr2', 'text', array(
          'label'     => Mage::helper('company')->__('Address 2'),
          'required'  => false,
          'name'      => 'addr2'
      ));
      
      $fieldset->addField('city', 'text', array(
          'label'     => Mage::helper('company')->__('City'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'city'
      ));
      
      $fieldset->addField('state', 'text', array(
          'label'     => Mage::helper('company')->__('State'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'state'
      ));
      
      $fieldset->addField('zip', 'text', array(
          'label'     => Mage::helper('company')->__('Zip'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'zip'
      ));
      
      $fieldset->addField('tax', 'text', array(
          'label'     => Mage::helper('company')->__('Tax'),
          'required'  => false,
          'name'      => 'tax'
      ));
      
      $fieldset->addField('discount', 'text', array(
          'label'     => Mage::helper('company')->__('Discount'),
          'required'  => false,
          'name'      => 'discount'
      ));
      
      $fieldset->addField('fluid', 'text', array(
          'label'     => Mage::helper('company')->__('Fluid'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'fluid'
      ));
      
      $fieldset->addField('payment', 'text', array(
          'label'     => Mage::helper('company')->__('Payment'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'payment'
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