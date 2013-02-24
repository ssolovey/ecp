<?php
/**
 * Reman Order module setup
 *
 * @category    Remah
 * @package     Reman_Order
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */

$installer = $this;
$installer->startSetup();

$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('order/order')}; 
	CREATE TABLE {$this->getTable('order/order')} (
	`order_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Magento order id',
	`ete_order_id` int(10) unsigned NOT NULL COMMENT 'ETE order id',
	`date_invoice` date DEFAULT NULL COMMENT 'Invoice Date',
	`date_order` date DEFAULT NULL COMMENT 'Oder Date',
	`order_type` varchar(1) DEFAULT NULL COMMENT 'Order Type',
	`po` varchar(35) DEFAULT NULL COMMENT 'PO#',
	`vin` varchar(35) DEFAULT NULL COMMENT 'Vehicle VIN',
	`make` varchar(35) DEFAULT NULL COMMENT 'Vehicle Make',
	`year` varchar(35) DEFAULT NULL COMMENT 'Vehicle Year',
	`model` varchar(35) DEFAULT NULL COMMENT 'Vehicle Model',
	`engine` varchar(35) DEFAULT NULL COMMENT 'Vehicle Engine',
	`aspiration` varchar(35) DEFAULT NULL COMMENT 'Vehicle Aspiration',
	`cyl_type` varchar(35) DEFAULT NULL COMMENT 'Vehicle Cyl Type',
	`fuel` varchar(35) DEFAULT NULL COMMENT 'Vehicle Fuel',
	`drive` varchar(35) DEFAULT NULL COMMENT 'Vehicle Drive',
	`tag` varchar(35) DEFAULT NULL COMMENT 'Unit Tag #',
	`ro` varchar(35) DEFAULT NULL COMMENT 'RO #',
	`mileage` int(10) DEFAULT NULL COMMENT 'Vehicle Mileage',
	`claim` varchar(35) DEFAULT NULL COMMENT 'Claim #',
	`partnum` varchar(35) DEFAULT NULL COMMENT 'Part #',
	`serial` varchar(35) DEFAULT NULL COMMENT 'Serial #',
	`family` varchar(35) DEFAULT NULL COMMENT 'Family',
	`alt_partnum` varchar(35) DEFAULT NULL COMMENT 'Alt Part #',
	`unit_type` varchar(35) DEFAULT NULL COMMENT 'Unit Type',
	`warrenty_terms` varchar(35) DEFAULT NULL COMMENT 'Warranty Terms',
	`carrier` varchar(35) DEFAULT NULL COMMENT 'Carrier',
	`carrier_service` varchar(35) DEFAULT NULL COMMENT 'Carrier Service',
	`carrier_options` varchar(35) DEFAULT NULL COMMENT 'Carrier Options',
	`date_ship` date DEFAULT NULL COMMENT 'Ship By Date',
	`date_deliver` date DEFAULT NULL COMMENT 'Deliver By Date',
	`ship_from` varchar(35) DEFAULT NULL COMMENT 'Ship From',
	`tracknum` varchar(35) DEFAULT NULL COMMENT 'Tracking #',
	`original_invoice` varchar(35) DEFAULT NULL COMMENT 'Original Invoice',
	`return_auth` varchar(35) DEFAULT NULL COMMENT 'Return Auth #',
	`csi` varchar(35) DEFAULT NULL COMMENT 'CSI #',
	`unit_amount` decimal(10,2) DEFAULT NULL COMMENT 'Unit Amount',
	`core_amount` decimal(10,2) DEFAULT NULL COMMENT 'Core Amount',
	`parts_amount` decimal(10,2) DEFAULT NULL COMMENT 'Parts Amount',
	`tax_percent` int(3) DEFAULT NULL COMMENT 'Tax %',
	`tax_amount` decimal(10,2) DEFAULT NULL COMMENT 'Tax Amount',
	`ship_amount` decimal(10,2) DEFAULT NULL COMMENT 'Shipping Amount',
	`deposit_received` decimal(10,2) DEFAULT NULL COMMENT 'Deposit Received',
	`total_amount` decimal(10,2) DEFAULT NULL COMMENT 'Total Amount',
	`transaction_type` varchar(35) DEFAULT NULL COMMENT 'Transaction Type',
	`commercial_app` varchar(1) DEFAULT NULL COMMENT 'Commercial App',
	PRIMARY KEY (`order_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
