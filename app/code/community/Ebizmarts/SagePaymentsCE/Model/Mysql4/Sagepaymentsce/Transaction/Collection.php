<?php
/*
 * Created on Jul 19, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class Ebizmarts_SagePaymentsCE_Model_Mysql4_Sagepaymentsce_Transaction_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	protected function _construct()
	{
		$this->_init('sagepaymentsce/sagepaymentsce_transaction');
	}

    public function setOrderFilter($orderId)
    {
        $this->addFieldToFilter('entity_id', $orderId);
        return $this;
    }
}