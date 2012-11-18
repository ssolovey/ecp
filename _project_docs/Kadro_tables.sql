# Dump of table reman_make
# ------------------------------------------------------------

DROP TABLE IF EXISTS `reman_make`;

CREATE TABLE `reman_make` (
  `make_id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Make ID',
  `start_year` int(4) NOT NULL DEFAULT '0' COMMENT 'Start Year',
  `end_year` int(4) NOT NULL DEFAULT '0' COMMENT 'End Year',
  `make` varchar(35) DEFAULT NULL COMMENT 'Make',
  PRIMARY KEY (`make_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Make table';



# Dump of table reman_model
# ------------------------------------------------------------

DROP TABLE IF EXISTS `reman_model`;

CREATE TABLE `reman_model` (
  `vehicle_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Vehicle ID',
  `make_id` int(4) unsigned NOT NULL COMMENT 'Make ID',
  `year` int(4) NOT NULL DEFAULT '0' COMMENT 'Year',
  `end_year` int(4) NOT NULL DEFAULT '0' COMMENT 'End Year',
  `model` varchar(35) DEFAULT NULL COMMENT 'Model',
  PRIMARY KEY (`vehicle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Make table';


# Dump of table reman_applic
# ------------------------------------------------------------

DROP TABLE IF EXISTS `reman_applic`;

CREATE TABLE `reman_applic` (
  `applic_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Applic ID',
  `vehicle_id` int(10) unsigned NOT NULL COMMENT 'Vehicle ID',
  `group_number` int(2) unsigned NOT NULL COMMENT 'Group',
  `subgroup` int(2) unsigned NOT NULL COMMENT 'Subgroup',
  `menu_heading` varchar(30) DEFAULT NULL COMMENT 'Menu Heading',
  `applic` varchar(100) DEFAULT NULL COMMENT 'Applic',
  `part_number` varchar(12) DEFAULT NULL COMMENT 'Path Number',
  PRIMARY KEY (`applic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Application table';


# Dump of table reman_warranties
# ------------------------------------------------------------

DROP TABLE IF EXISTS `reman_warranties`;

CREATE TABLE `reman_warranties` (
  `warranty_id` int(2) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Warranty ID',
  `warranty` varchar(35) DEFAULT NULL COMMENT 'Warranty text',
  PRIMARY KEY (`warranty_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Warranty table';


INSERT INTO `reman_warranties` (`warranty_id`, `warranty`) VALUES
(13, '6 Month/6,000 Mile Warranty'),
(14, '12 Month/12,000 Mile Warranty'),
(16, '18 Month/18,000 Mile Warranty'),
(19, '24 Month/Unlimited Miles Warranty'),
(23, '36 Month/100,000 Mile Warranty');


