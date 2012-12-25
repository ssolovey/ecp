<?php
class Reman_Company_Block_Adminhtml_Company_Edit_Tab_Warranty extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('warranty_form', array('legend'=>Mage::helper('company')->__('Warranty information')));     
      
      $fieldset->addField('tc_war', 'select', array(
          'label'     => Mage::helper('company')->__('Transfer Case'),
          'name'      => 'tc_war',
          'values'    => array(
              
              array(
                  'value'     => 0,
                  'label'     => Mage::helper('company')->__('None')
              ),
              
              array(
                  'value'     => 13,
                  'label'     => Mage::helper('company')->__('6 Month/6,000 Mile Warranty')
              ),

              array(
                  'value'     => 14,
                  'label'     => Mage::helper('company')->__('12 Month/12,000 Mile Warranty')
              ),
              
              array(
                  'value'     => 17,
                  'label'     => Mage::helper('company')->__('18 Month/18,000 Mile Warranty')
              ),
              
              array(
                  'value'     => 19,
                  'label'     => Mage::helper('company')->__('24 Month/Unlimited Miles Warranty')
              ),
              
              array(
                  'value'     => 23,
                  'label'     => Mage::helper('company')->__('36 Month/100,000 Mile Warranty')
              )
          )
      ));
      
      $fieldset->addField('at_war', 'select', array(
          'label'     => Mage::helper('company')->__('Auto Transmission'),
          'name'      => 'at_war',
          'values'    => array(
              
              array(
                  'value'     => 0,
                  'label'     => Mage::helper('company')->__('None')
              ),
              
              array(
                  'value'     => 13,
                  'label'     => Mage::helper('company')->__('6 Month/6,000 Mile Warranty')
              ),

              array(
                  'value'     => 14,
                  'label'     => Mage::helper('company')->__('12 Month/12,000 Mile Warranty')
              ),
              
              array(
                  'value'     => 17,
                  'label'     => Mage::helper('company')->__('18 Month/18,000 Mile Warranty')
              ),
              
              array(
                  'value'     => 19,
                  'label'     => Mage::helper('company')->__('24 Month/Unlimited Miles Warranty')
              ),
              
              array(
                  'value'     => 23,
                  'label'     => Mage::helper('company')->__('36 Month/100,000 Mile Warranty')
              )
          )
      ));
      
      $fieldset->addField('di_war', 'select', array(
          'label'     => Mage::helper('company')->__('Differential'),
          'name'      => 'di_war',
          'values'    => array(
              
              array(
                  'value'     => 0,
                  'label'     => Mage::helper('company')->__('None')
              ),
              
              array(
                  'value'     => 13,
                  'label'     => Mage::helper('company')->__('6 Month/6,000 Mile Warranty')
              ),

              array(
                  'value'     => 14,
                  'label'     => Mage::helper('company')->__('12 Month/12,000 Mile Warranty')
              ),
              
              array(
                  'value'     => 17,
                  'label'     => Mage::helper('company')->__('18 Month/18,000 Mile Warranty')
              ),
              
              array(
                  'value'     => 19,
                  'label'     => Mage::helper('company')->__('24 Month/Unlimited Miles Warranty')
              ),
              
              array(
                  'value'     => 23,
                  'label'     => Mage::helper('company')->__('36 Month/100,000 Mile Warranty')
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