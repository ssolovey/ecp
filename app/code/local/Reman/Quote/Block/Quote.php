<?php
class Reman_Quote_Block_Quote extends Mage_Core_Block_Template
{
    public function getMake()
    {
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
		
		$readresult=$write->query('SELECT make FROM reman_make');
		
		while ($row = $readresult->fetch() ) {
			$makers[]=$row['make'];
		}
		
		return $makers;
	
    }
	
	public function onMakeSelect($make)
	{
	
	}
	
}