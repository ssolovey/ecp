<?php

$installer = $this;

$installer->startSetup();

$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('company/company')}; 
	CREATE TABLE {$this->getTable('company/company')} (
	`company_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Company ID',
	`name` varchar(30) DEFAULT NULL COMMENT 'Company name',
	`addr1` varchar(30) DEFAULT NULL COMMENT 'Address 1',
	`addr2` varchar(30) DEFAULT NULL COMMENT 'Address 2',
	`city` varchar(20) DEFAULT NULL COMMENT 'City',
	`state` varchar(2) DEFAULT NULL COMMENT 'State',
	`zip` varchar(5) DEFAULT NULL COMMENT 'Zip',
	`tax` varchar(20) DEFAULT NULL COMMENT 'TAX Number',
	`discount` int(2) NOT NULL DEFAULT '0' COMMENT 'Discount',
	`fluid` varchar(2) DEFAULT NULL COMMENT 'Fluid',
	`payment` varchar(6) DEFAULT NULL COMMENT 'Payment method',
	`tc_war` int(2) NOT NULL DEFAULT '0' COMMENT 'TC Warranty ID',
	`at_war` int(2) NOT NULL DEFAULT '0' COMMENT 'AT Warranty ID',
	`di_war` int(2) NOT NULL DEFAULT '0' COMMENT 'DI Warranty ID',
	`status` smallint(6) NOT NULL default '0',
	PRIMARY KEY (`company_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$setup = Mage::getModel('customer/entity_setup', 'core_setup');
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

$installer->endSetup();
