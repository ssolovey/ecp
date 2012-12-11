<?php
$installer = $this;
$installer->startSetup();
$setup = Mage::getModel('customer/entity_setup', 'core_setup');

// ETE Customer id
$setup->addAttribute('customer', 'eteid', array(
	'type'				=>	'int',
	'input'				=>	'text',
	'label'				=>	'ETE id',
	'global'			=>	1,
	'visible'			=>	1,
	'required'			=> 	0,
	'user_defined'		=>	1,
	'default'			=>	'0',
	'visible_on_front'	=>	1,
	'source'			=>	'profile/entity_eteid'
));

Mage::getSingleton('eav/config')
	->getAttribute('customer', 'eteid')
	->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit','checkout_register'))
	->save();


// Fluid
$setup->addAttribute('customer', 'fluid', array(
	'type'				=>	'int',
	'input'				=>	'text',
	'label'				=>	'Fluid',
	'global'			=>	1,
	'visible'			=>	1,
	'required'			=> 	0,
	'user_defined'		=>	1,
	'default'			=>	'0',
	'visible_on_front'	=>	1,
	'source'			=>	'profile/entity_fluid'
));

Mage::getSingleton('eav/config')
	->getAttribute('customer', 'fluid')
	->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit','checkout_register'))
	->save();

// Payment
$setup->addAttribute('customer', 'payment', array(
	'type'				=>	'text',
	'input'				=>	'text',
	'label'				=>	'Payment',
	'global'			=>	1,
	'visible'			=>	1,
	'required'			=> 	0,
	'user_defined'		=>	1,
	'default'			=>	'0',
	'visible_on_front'	=>	1,
	'source'			=>	'profile/entity_payment'
));

Mage::getSingleton('eav/config')
	->getAttribute('customer', 'payment')
	->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit','checkout_register'))
	->save();

// Discount
$setup->addAttribute('customer', 'discount', array(
	'type'				=>	'int',
	'input'				=>	'text',
	'label'				=>	'Discount',
	'global'			=>	1,
	'visible'			=>	1,
	'required'			=> 	0,
	'user_defined'		=>	1,
	'default'			=>	'0',
	'visible_on_front'	=>	1,
	'source'			=>	'profile/entity_discount'
));

Mage::getSingleton('eav/config')
	->getAttribute('customer', 'discount')
	->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit','checkout_register'))
	->save();

// AT Warranty
$setup->addAttribute('customer', 'atwar', array(
	'type'				=>	'int',
	'input'				=>	'text',
	'label'				=>	'AT Warranty',
	'global'			=>	1,
	'visible'			=>	1,
	'required'			=> 	0,
	'user_defined'		=>	1,
	'default'			=>	'0',
	'visible_on_front'	=>	1,
	'source'			=>	'profile/entity_atwar'
));

Mage::getSingleton('eav/config')
	->getAttribute('customer', 'atwar')
	->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit','checkout_register'))
	->save();
	
// TC Warranty
$setup->addAttribute('customer', 'tcwar', array(
	'type'				=>	'int',
	'input'				=>	'text',
	'label'				=>	'TC Warranty',
	'global'			=>	1,
	'visible'			=>	1,
	'required'			=> 	0,
	'user_defined'		=>	1,
	'default'			=>	'0',
	'visible_on_front'	=>	1,
	'source'			=>	'profile/entity_tcwar'
));

Mage::getSingleton('eav/config')
	->getAttribute('customer', 'tcwar')
	->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit','checkout_register'))
	->save();

$installer->endSetup();
