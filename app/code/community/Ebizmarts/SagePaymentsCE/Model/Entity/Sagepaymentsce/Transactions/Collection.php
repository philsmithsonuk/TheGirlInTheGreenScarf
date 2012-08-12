<?php
/*
 * Created on Apr 22, 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class Ebizmarts_SagePaymentsCE_Model_Entity_Sagepaymentsce_Transactions_Collection extends Mage_Eav_Model_Entity_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('sagepaymentsce/sagepaymentsce_transactions');
    }

    public function setOrderFilter($orderId)
    {
        $this->addAttributeToFilter('parent_id', $orderId);
        return $this;
    }

}