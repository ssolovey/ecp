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
          'width'     => '50px',
          'index'     => 'company_id',
          'align'     => 'center'
        ));
 
        $this->addColumn('name', array(
          'header'    => Mage::helper('company')->__('Name'),
          'index'     => 'name'
        ));
        
        $this->addColumn('city', array(
          'header'    => Mage::helper('company')->__('City'),
          'index'     => 'city'
        ));
        
        $this->addColumn('state', array(
          'header'    => Mage::helper('company')->__('State'),
          'index'     => 'state'
        ));
        
        $this->addColumn('zip', array(
          'header'    => Mage::helper('company')->__('Zip'),
          'index'     => 'zip'
        ));
        
        $this->addColumn('payment', array(
          'header'    => Mage::helper('company')->__('Payment'),
          'index'     => 'payment'
        ));
		
		 $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('company')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('company')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
        
        return parent::_prepareColumns();
    }
}