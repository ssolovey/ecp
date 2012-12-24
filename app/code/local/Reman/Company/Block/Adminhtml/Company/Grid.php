<?php
class Reman_Company_Block_Adminhtml_Company_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {        
        parent::__construct();
        $this->setId('companyGrid');
        // This is the primary key of the database
        $this->setDefaultSort('company_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        //$this->setUseAjax(true);
    }
 
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('company/company')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
 
    protected function _prepareColumns()
    {
        $this->addColumn('company_id', array(
          'header'    => Mage::helper('company')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'company_id'
        ));
 
        $this->addColumn('name', array(
          'header'    => Mage::helper('company')->__('Name'),
          'align'     =>'left',
          'index'     => 'name',
          'width'     => '150px'
        ));
        
        return parent::_prepareColumns();
    }
}