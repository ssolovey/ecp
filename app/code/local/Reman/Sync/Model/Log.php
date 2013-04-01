<?php
/**
 * Logger model
 *
 * @category    Reman
 * @package     Reman_Sync
 * @author		Artem Petrosyan (artpetrosyan@gmail.com)
 */
class Reman_Sync_Model_Log extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('sync/log');
	}
	
	
	public function printStat()
	{
		
		$table = '<table width="600px" border="1" cellspacing="0" cellpadding="5">'
				.'<tr><td>ID</td><td>SYNC DATE</td><td>SYNC ITEMS</td><td>CRON DATE</td></tr>';
		
		foreach ( $this->getCollection() as $item ) {
			$table = $table . '<tr><td>'.$item->model_id.'</td><td>'.$item->sync_date.'</td><td>'.$item->sync_items.'</td><td>'.$item->cron_date.'</td></tr>';
		}
	
		$table = $table .'</table>';

		return $table;
	}
}
