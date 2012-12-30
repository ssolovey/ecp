<?php
/**
 * Reman Company Install
 *
 * Reman Company module setup
 *
 * @category    Reman
 * @package     Reman_Company
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
$installer = $this;

$installer->startSetup();

$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('company/company')}; 
	CREATE TABLE {$this->getTable('company/company')} (
	`company_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Company ID',
	`ete` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETE ID',
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
	`ship` varchar(10) DEFAULT NULL COMMENT 'Shipping price',
	`splink` varchar(12) DEFAULT NULL COMMENT 'GSP Link',
	`tc_war` int(2) NOT NULL DEFAULT '0' COMMENT 'TC Warranty ID',
	`tc_gswlink` varchar(12) DEFAULT NULL COMMENT 'TC GSW Link',
	`at_war` int(2) NOT NULL DEFAULT '0' COMMENT 'AT Warranty ID',
	`at_gswlink` varchar(12) DEFAULT NULL COMMENT 'AT GSW Link',
	`di_war` int(2) NOT NULL DEFAULT '0' COMMENT 'DI Warranty ID',
	`di_gswlink` varchar(12) DEFAULT NULL COMMENT 'DI GSW Link',
	`status` smallint(6) NOT NULL default '0',
	PRIMARY KEY (`company_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
