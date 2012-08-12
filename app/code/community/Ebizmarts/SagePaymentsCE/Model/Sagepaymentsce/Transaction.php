<?php

 class Ebizmarts_SagePaymentsCE_Model_Sagepaymentsce_Transaction extends Mage_Core_Model_Abstract
{
	 /** Order instance
     *
     * @var Mage_Sales_Model_Order
     */
    protected $_order;

    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init('sagepaymentsce/sagepaymentsce_transaction');
    }
	public function addTransaction($parentId,$authorized,$payment)
	{
		if($parentId) {
			$this->setParentId($parentId)
				->setEntityId($parentId)
				->setAuthorised($authorized)
				->setSecurityKey($payment->getSecurityKey());
			if($payment->getAvsIndicator()) {
				$this->setAvsIndicator($payment->getAvsIndicator());
			}
			if($payment->getCvvIndicator()) {
				$this->setCvvIndicator($payment->getCvvIndicator());

			}
			$this->save();
		}
	}
}