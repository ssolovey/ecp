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
	`order_status` varchar(35) DEFAULT NULL COMMENT 'Order Status',
	`order_type` varchar(1) DEFAULT NULL COMMENT 'Order Type',
	`ete_cust` varchar(35) DEFAULT NULL COMMENT 'ETE Cust Rep',
	`so_cust_num` varchar(35) DEFAULT NULL COMMENT 'Sold To Customer Number',
	`so_cust_name` varchar(35) DEFAULT NULL COMMENT 'Sold To Customer Number',
	`so_cont_name` varchar(35) DEFAULT NULL COMMENT 'Sold To Contact Name',
	`so_cont_email` varchar(35) DEFAULT NULL COMMENT 'Sold To Contact Email',
	`so_phone` varchar(35) DEFAULT NULL COMMENT 'Sold To Phone',
	`so_phone_ext` varchar(35) DEFAULT NULL COMMENT 'Sold To Phone Ext',
	`po` varchar(35) DEFAULT NULL COMMENT 'PO#',
	`bt_cust_num` varchar(35) DEFAULT NULL COMMENT 'Bill To Customer Number',
	`bt_cust_name` varchar(35) DEFAULT NULL COMMENT 'Bill To Customer Name',
	`bt_addr1` varchar(35) DEFAULT NULL COMMENT 'Bill To Address 1',
	`bt_addr2` varchar(35) DEFAULT NULL COMMENT 'Bill To Address 2',
	`bt_city` varchar(35) DEFAULT NULL COMMENT 'Bill To City',
	`bt_state` varchar(35) DEFAULT NULL COMMENT 'Bill To State',
	`bt_zip` varchar(35) DEFAULT NULL COMMENT 'Bill To Zip',	
	`st_cust_num` varchar(35) DEFAULT NULL COMMENT 'Ship To Customer Number',
	`st_cust_name` varchar(35) DEFAULT NULL COMMENT 'Ship To Customer Name',
	`st_addr1` varchar(35) DEFAULT NULL COMMENT 'Ship To Address 1',
	`st_addr2` varchar(35) DEFAULT NULL COMMENT 'Ship To Address 2',
	`st_city` varchar(35) DEFAULT NULL COMMENT 'Ship To City',
	`st_state` varchar(35) DEFAULT NULL COMMENT 'Ship To State',
	`st_zip` varchar(35) DEFAULT NULL COMMENT 'Ship To Zip',
	`st_cont_name` varchar(35) DEFAULT NULL COMMENT 'Ship To Contact Name',
	`st_phone` varchar(35) DEFAULT NULL COMMENT 'Ship To Phone',
	`st_phone_ext` varchar(35) DEFAULT NULL COMMENT 'Ship To Phone Ext',
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
	`end_username` varchar(35) DEFAULT NULL COMMENT 'End User Name',
	`ro` varchar(35) DEFAULT NULL COMMENT 'RO #',
	`mileage` int(10) DEFAULT NULL COMMENT 'Vehicle Mileage',
	`claim` varchar(35) DEFAULT NULL COMMENT 'Claim #',
	`partnum` varchar(35) DEFAULT NULL COMMENT 'Part #',
	`serial` varchar(35) DEFAULT NULL COMMENT 'Serial #',
	`family` varchar(35) DEFAULT NULL COMMENT 'Family',
	`alt_partnum` varchar(35) DEFAULT NULL COMMENT 'Alt Part #',
	`unit_type` varchar(35) DEFAULT NULL COMMENT 'Unit Type',
	`warranty_id` int(2) DEFAULT NULL COMMENT 'Warranty Terms',
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
	`unit_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Unit Amount',
	`core_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Core Amount',
	`fluid_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Parts Amount',
	`tax_percent` decimal(2,2) NOT NULL DEFAULT '0.00' COMMENT 'Tax %',
	`tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Tax Amount',
	`ship_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Shipping Amount',
	`deposit_received` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Deposit Received',
	`total_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Total Amount',
	`transaction_type` varchar(35) DEFAULT NULL COMMENT 'Transaction Type',
	`commercial_app` tinyint(1) DEFAULT '0' COMMENT 'Commercial App',
	`product_name` varchar(40) DEFAULT NULL COMMENT 'Product Name',
	`order_notes` varchar(40) DEFAULT NULL COMMENT 'Order Notes',
	`vehicle_notes` varchar(160) DEFAULT NULL COMMENT 'Vehicle Notes',
	`fluid` varchar(25) DEFAULT NULL COMMENT 'Fluid',
	PRIMARY KEY (`order_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
