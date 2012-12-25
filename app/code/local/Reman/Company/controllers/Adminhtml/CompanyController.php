<?php
class Reman_Company_Adminhtml_CompanyController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('company/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Company Manager'), Mage::helper('adminhtml')->__('Company Manager'));
		return $this;
	}   
	
	/*
	public function indexAction() {
		$this->loadLayout();
		$myblock = $this->getLayout()->createBlock('company/adminhtml_company');
		$this->_addContent($myblock);
		$this->renderLayout();

	}*/
	
	public function indexAction() {
		$this->_initAction();
		$this->_addContent($this->getLayout()->createBlock('company/adminhtml_company'));
		$this->renderLayout();
	}
	
	/*
	public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
               $this->getLayout()->createBlock('company/adminhtml_company_grid')->toHtml()
        );
    }
    */
}
