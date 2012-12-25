<?php
class Reman_Company_Block_Adminhtml_Company_Edit_Tab_Address extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('address_form', array('legend'=>Mage::helper('company')->__('Address information')));     
           
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