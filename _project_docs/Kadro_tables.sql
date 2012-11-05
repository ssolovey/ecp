# Dump of table make_table
# ------------------------------------------------------------

DROP TABLE IF EXISTS `make_table`;

CREATE TABLE `make_table` (
  `make_id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Make ID',
  `start_year` int(4) NOT NULL DEFAULT '0' COMMENT 'Start Year',
  `end_year` int(4) NOT NULL DEFAULT '0' COMMENT 'End Year',
  `make` varchar(35) DEFAULT NULL COMMENT 'Make',
  PRIMARY KEY (`make_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Make table';



# Dump of table model_table
# ------------------------------------------------------------

DROP TABLE IF EXISTS `model_table`;

CREATE TABLE `model_table` (
  `vehicle_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Vehicle ID',
  `make_id` int(4) unsigned NOT NULL COMMENT 'Make ID',
  `year` int(4) NOT NULL DEFAULT '0' COMMENT 'Year',
  `end_year` int(4) NOT NULL DEFAULT '0' COMMENT 'End Year',
  `model` varchar(35) DEFAULT NULL COMMENT 'Model',
  PRIMARY KEY (`vehicle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Make table';




# Dump of table applic_table
# ------------------------------------------------------------

DROP TABLE IF EXISTS `applic_table`;

CREATE TABLE `applic_table` (
  `applic_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Applic ID',
  `vehicle_id` int(10) unsigned NOT NULL COMMENT 'Vehicle ID',
  `group` int(2) unsigned NOT NULL COMMENT 'Group',
  `key` int(2) unsigned NOT NULL COMMENT 'Key',
  `subgroup` int(2) unsigned NOT NULL COMMENT 'Subgroup',
  `menu_heading` varchar(30) DEFAULT NULL COMMENT 'Menu Heading',
  `applic` varchar(100) DEFAULT NULL COMMENT 'Applic',
  `part_number` varchar(12) DEFAULT NULL COMMENT 'Path Number',
  PRIMARY KEY (`applic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Application table';








# Dump of table parts_table
# ------------------------------------------------------------

DROP TABLE IF EXISTS `parts_table`;

CREATE TABLE `parts_table` (
  `part_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Applic ID',
  `part_number` varchar(12) DEFAULT NULL COMMENT 'Path Number',
  `start_year` int(4) NOT NULL DEFAULT '0' COMMENT 'Start Year',
  `end_year` int(4) NOT NULL DEFAULT '0' COMMENT 'End Year',
  `type` boolean NOT NULL DEFAULT 'TRUE' COMMENT 'Transmission',
  `fluid_option` varchar(1) unsigned NOT NULL COMMENT 'Optionality of Fluid',
  `fluid_quantity` int(3) unsigned NOT NULL COMMENT 'Fuild Quantity Required',
  `fuel_type` varchar(8) unsigned NOT NULL COMMENT 'Fuel Type',
  `engine` varchar(32) unsigned NOT NULL COMMENT 'Engine Sizes',
  `drive` varchar(8) unsigned NOT NULL COMMENT 'Drive Type',
  `aspiration` varchar(2) unsigned NOT NULL COMMENT 'Code Letter for Aspiration',
  `cylinder` varchar(4) unsigned NOT NULL COMMENT 'Cylinder Type',
  `family` varchar(30) unsigned NOT NULL COMMENT 'Family Type',
  `MSRP` varchar(30) unsigned NOT NULL COMMENT 'MSRP',
  `core` varchar(30) unsigned NOT NULL COMMENT 'Core Price',
  `orwarval` int(10) unsigned NOT NULL COMMENT 'Original Warranty Value',
  `comwarval` int(10) unsigned NOT NULL COMMENT 'Commercial Warranty Value',
  PRIMARY KEY (`part_id`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Parts table';
