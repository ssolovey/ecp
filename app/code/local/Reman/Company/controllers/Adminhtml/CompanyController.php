<?php
/**
 * Company Admin Controller
 * provide CRUD operations logic
 *
 * @category    Reman
 * @package     Reman_Company
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Company_Adminhtml_CompanyController extends Mage_Adminhtml_Controller_Action
{
	/**
	 * Initialization
	 */
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('customer/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Company Manager'), Mage::helper('adminhtml')->__('Company Manager'));
		return $this;
	}
	
	/**
	 * Index action for display list of companies
	 * used in adminhtml grid
	 */
	public function indexAction() {
		$this->_initAction();
		$this->_addContent($this->getLayout()->createBlock('company/adminhtml_company'));
		$this->renderLayout();
	}
	
	/**
	 * Action for edit company
	 */
	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('company/company')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('company_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('company/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Company Manager'), Mage::helper('adminhtml')->__('Company Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Company News'), Mage::helper('adminhtml')->__('Company News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('company/adminhtml_company_edit'))
				->_addLeft($this->getLayout()->createBlock('company/adminhtml_company_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('company')->__('Company does not exist'));
			$this->_redirect('*/*/');
		}
	}
	
	/**
	 * Action for create new company
	 */
	public function newAction() {
		$this->_forward('edit');
	}
	
	/**
	 * Action for save company
	 */
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			$model = Mage::getModel('company/company');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('company')->__('Company was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('company')->__('Unable to find company to save'));
        $this->_redirect('*/*/');
	}
	
	/**
	 * Action for delete company
	 */
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('company/company');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Company was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}
}
