<?php
class Reman_Company_Block_Adminhtml_Company_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('general_form', array('legend'=>Mage::helper('company')->__('General information')));     
     
     $fieldset->addField('company_id', 'text', array(
          'label'     => Mage::helper('company')->__('Company ID'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'company_id'
      ));
     
     
      $fieldset->addField('name', 'text', array(
          'label'     => Mage::helper('company')->__('Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'name'
      ));
            
      $fieldset->addField('tax', 'text', array(
          'label'     => Mage::helper('company')->__('Tax Number'),
          'required'  => false,
          'name'      => 'tax'
      ));
      
      $fieldset->addField('discount', 'text', array(
          'label'     => Mage::helper('company')->__('Discount, %'),
          'required'  => false,
          'name'      => 'discount'
      ));
      
      $fieldset->addField('fluid', 'select', array(
          'label'     => Mage::helper('company')->__('Fluid'),
          'name'      => 'fluid',
          'values'    => array(
              array(
                  'value'     => 'R',
                  'label'     => Mage::helper('company')->__('Required')
              ),

              array(
                  'value'     => 'O',
                  'label'     => Mage::helper('company')->__('Optional')
              ),
              
              array(
                  'value'     => 'N',
                  'label'     => Mage::helper('company')->__('None')
              )
          )
      ));
      
      $fieldset->addField('payment', 'select', array(
          'label'     => Mage::helper('company')->__('Payment'),
          'name'      => 'payment',
          'values'    => array(
              array(
                  'value'     => 'ACCT',
                  'label'     => Mage::helper('company')->__('On Account')
              ),

              array(
                  'value'     => 'PREPAY',
                  'label'     => Mage::helper('company')->__('Prepaid')
              )
          )
      ));
      
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('company')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('company')->__('Enabled')
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('company')->__('Disabled')
              )
          )
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