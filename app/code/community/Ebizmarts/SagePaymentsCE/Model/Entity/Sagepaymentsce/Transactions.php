<?php

 class Ebizmarts_SagePaymentsCE_Model_Entity_Sagepaymentsce_Transactions extends Mage_Eav_Model_Entity_Abstract
{

    public function __construct()
    {
        $resource = Mage::getSingleton('core/resource');
        $this->setType('sagepaymentsce_transactions');
        $read = $resource->getConnection('sagepaymentsce_setup');
        $write = $resource->getConnection('sagepaymentsce_write');
        $this->setConnection($read, $write);
    }

    public function setOrderFilter($orderId)
    {
        $this->addAttributeToFilter('parent_id', $orderId);
        return $this;
    }
}