<?php
/**
 * Company Adminhtml Edit
 * define company edit layout
 *
 * @category    Reman
 * @package     Reman_Company
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Company_Block_Adminhtml_Company_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'company';
        $this->_controller = 'adminhtml_company';
        
        $this->_updateButton('save', 'label', Mage::helper('company')->__('Save Company'));
        $this->_updateButton('delete', 'label', Mage::helper('company')->__('Delete Company'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('company_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'company_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'company_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('company_data') && Mage::registry('company_data')->getId() ) {
            return Mage::helper('company')->__("Edit Company '%s'", $this->htmlEscape(Mage::registry('company_data')->getName()));
        } else {
            return Mage::helper('company')->__('Add Company');
        }
    }
}