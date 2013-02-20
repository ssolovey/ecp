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
	PRIMARY KEY (`order_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
