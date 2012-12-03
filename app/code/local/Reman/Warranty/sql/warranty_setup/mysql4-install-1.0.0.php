<?php

$installer = $this;

$installer->startSetup();

$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('warranty/gsw')}; 
	CREATE TABLE {$this->getTable('warranty/gsw')} (
	`gsw_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'GSW ID',
	`customer_id` int(6) unsigned NOT NULL COMMENT 'Customer ID',
	`type` varchar(35) DEFAULT NULL COMMENT 'Warranty Type',
	`warranty_id` int(2) unsigned NOT NULL COMMENT 'Warranty ID',
	PRIMARY KEY (`gsw_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
