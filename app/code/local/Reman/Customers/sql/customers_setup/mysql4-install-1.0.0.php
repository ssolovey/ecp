<?php
/**
 * Reman Customers Install
 *
 * Reman Customers module setup
 *
 * @category    Reman
 * @package     Reman_Customres
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
$installer = $this;
$installer->startSetup();

$setup = Mage::getModel('customer/entity_setup', 'core_setup');

// Company
$setup->addAttribute('customer', 'company', array(
	'type'				=> 'int',
	'input'				=> 'select',
	'label'				=> 'Company',
	'global'			=> 1,
	'visible'			=> 1,
	'required'			=> 1,
	'user_defined'		=> 1,
	'default'			=> '0',
	'visible_on_front'	=> 1,
	'source'			=> 'company/entity_companies',
));

Mage::getSingleton('eav/config')
	->getAttribute('customer', 'company')
	->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit','checkout_register'))
	->save();
	

// Phone
$setup->addAttribute('customer', 'phone', array(
	'type'				=>	'text',
	'input'				=>	'text',
	'label'				=>	'Phone',
	'global'			=>	1,
	'visible'			=>	1,
	'required'			=> 	0,
	'user_defined'		=>	1,
	'default'			=>	'',
	'visible_on_front'	=>	1,
	'source'			=>	'customer/entity_phone'
));

Mage::getSingleton('eav/config')
	->getAttribute('customer', 'phone')
	->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit','checkout_register'))
	->save();
	
// Ext
$setup->addAttribute('customer', 'ext', array(
	'type'				=>	'text',
	'input'				=>	'text',
	'label'				=>	'Ext',
	'global'			=>	1,
	'visible'			=>	1,
	'required'			=> 	0,
	'user_defined'		=>	1,
	'default'			=>	'',
	'visible_on_front'	=>	1,
	'source'			=>	'customer/entity_ext'
));

Mage::getSingleton('eav/config')
	->getAttribute('customer', 'ext')
	->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit','checkout_register'))
	->save();

/*
$setup->addAttribute('customer', 'state', array(
	'type'				=> 'int',
	'input'				=> 'select',
	'label'				=> 'State',
	'global'			=> 1,
	'visible'			=> 1,
	'required'			=> 1,
	'user_defined'		=> 1,
	'default'			=> '0',
	'visible_on_front'	=> 1,
	'source'			=> 'customers/entity_state'
));

Mage::getSingleton('eav/config')
	->getAttribute('customer', 'state')
	->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit','checkout_register'))
	->save();
*/

$installer->endSetup();