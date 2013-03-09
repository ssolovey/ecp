<?php
/**
 * Reman Warranty Intall
 *
 * Reman Warranty module setup
 * create database tables related to warranties
 *
 * @category    Remah
 * @package     Reman_Warranty
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
$installer = $this;

$installer->startSetup();

$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('warranty/warranties')}; 
	CREATE TABLE {$this->getTable('warranty/warranties')} (
	`warranty_id` int(2) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Warranty ID',
	`warranty` varchar(35) DEFAULT NULL COMMENT 'Warranty text',
	`value` int(2) DEFAULT NULL COMMENT 'Warranty value',
	PRIMARY KEY (`warranty_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
