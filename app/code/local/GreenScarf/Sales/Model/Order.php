<?php 
class GreenScarf_Sales_Model_Order extends Mage_Sales_Model_Order
{ 
    public function getCreatedAtFormated($format,$custom = false)
    {
		if($custom){
			$date  =  Mage::helper('core')->formatDate($this->getCreatedAtStoreDate(), $format, false); 
			$now   =  Mage::getModel('core/date')->timestamp($this->getCreatedAt());
			$time  =  date('h:i:s', $now);
			return $date ." ". $time;
		}		
		return parent::getCreatedAtFormated($format);		
    }
}
