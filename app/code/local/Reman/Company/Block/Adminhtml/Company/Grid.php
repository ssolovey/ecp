<?php
/**
 * Company Adminhtml Grid
 * define company module grid layout
 * describe fields and actions
 *
 * @category    Reman
 * @package     Reman_Company
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
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
          'width'     => '100px',
          'index'     => 'company_id',
          'align'     => 'right'
        ));
        
        $this->addColumn('ete', array(
          'header'    => Mage::helper('company')->__('ETE Company ID'),
          'index'     => 'ete'
        ));
 
        $this->addColumn('name', array(
          'header'    => Mage::helper('company')->__('Name'),
          'width'     => '250px',
          'index'     => 'name'
        ));
        
        $this->addColumn('zip', array(
          'header'    => Mage::helper('company')->__('Zip'),
          'index'     => 'zip'
        ));
        
        $this->addColumn('tax', array(
          'header'    => Mage::helper('company')->__('Tax Number'),
          'index'     => 'tax'
        ));
        
        $this->addColumn('discount', array(
          'header'    => Mage::helper('company')->__('Discount, %'),
          'index'     => 'discount'
        ));
        
        $this->addColumn('payment', array(
          'header'    => Mage::helper('company')->__('Payment'),
          'index'     => 'payment',
          'type'      => 'options',
          'options'   => array(
              'ACCT' => 'On Account',
              'PREPAY' => 'Prepaid'
          )
      ));
        
        $this->addColumn('status', array(
          'header'    => Mage::helper('company')->__('Status'),
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              0 => 'Disabled'
          )
      ));
		
		 $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('company')->__('Action'),
                'width'     => '100px',
                'align'     => 'center',
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