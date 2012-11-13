<?php

$installer = $this;

$installer->startSetup();

$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('sync/make')}; 
	CREATE TABLE {$this->getTable('sync/make')} (
	`make_id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Make ID',
	`start_year` int(4) NOT NULL DEFAULT '0' COMMENT 'Start Year',
	`end_year` int(4) NOT NULL DEFAULT '0' COMMENT 'End Year',
	`make` varchar(35) DEFAULT NULL COMMENT 'Make',
	PRIMARY KEY (`make_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	DROP TABLE IF EXISTS {$this->getTable('sync/model')}; 
	CREATE TABLE {$this->getTable('sync/model')} (
	`vehicle_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Vehicle ID',
	`make_id` int(4) unsigned NOT NULL COMMENT 'Make ID',
	`year` int(4) NOT NULL DEFAULT '0' COMMENT 'Year',
	`model` varchar(35) DEFAULT NULL COMMENT 'Model',
	PRIMARY KEY (`vehicle_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	DROP TABLE IF EXISTS {$this->getTable('sync/applic')}; 
	CREATE TABLE {$this->getTable('sync/applic')} (
	`applic_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Applic ID',
	`vehicle_id` int(10) unsigned NOT NULL COMMENT 'Vehicle ID',
	`group` int(2) unsigned NOT NULL COMMENT 'Group',
	`key` int(2) unsigned NOT NULL COMMENT 'Key',
	`subgroup` int(2) unsigned NOT NULL COMMENT 'Subgroup',
	`menu_heading` varchar(30) DEFAULT NULL COMMENT 'Menu Heading',
	`applic` varchar(100) DEFAULT NULL COMMENT 'Applic',
	`part_number` varchar(12) DEFAULT NULL COMMENT 'Path Number',
	PRIMARY KEY (`applic_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();