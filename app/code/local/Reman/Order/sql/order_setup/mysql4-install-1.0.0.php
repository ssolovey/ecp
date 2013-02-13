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
	$installer->addAttribute(
	'order', 
	'order_po', 
		array(
			'type' => 'int', /* varchar, text, decimal, datetime */
			'grid' => true
		)
	);
$installer->endSetup();