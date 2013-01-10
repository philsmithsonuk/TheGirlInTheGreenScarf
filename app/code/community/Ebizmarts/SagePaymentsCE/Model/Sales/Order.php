<?php


class Ebizmarts_SagePaymentsCE_Model_Sales_Order extends Mage_Sales_Model_Order {

    protected $sage_info;

    protected function _beforeSave()
    {
		Mage::log(__METHOD__);
        parent::_beforeSave();
        $payment = $this->getPayment();

        if ($payment->getMethod() != 'sagepaymentsce') {
            return parent::_beforeSave();
        }
        $this->sage_info = $this->_getSagePayInfo($this->getId());
        if(!$this->sage_info && !$this->getOrigData()) {
        	if($payment->getAuthorized()==1) {
        		$authorized = 1;
        	}
        	else {
        		$authorized = 0;
        	}
        	Mage::getModel('sagepaymentsce/sagepaymentsce_transaction')->addTransaction($this->getId(), $authorized, $payment);
        }

        return $this;
    }
    private function _getSagePayInfo($orderId) {
        $sage_info_collection = Mage::getResourceModel('sagepaymentsce/sagepaymentsce_transaction_collection');
        $sage_info_collection->setOrderFilter($orderId)
                ->load();
        $sageInfo = null;
        foreach ($sage_info_collection as $sage) {
            $sageInfo = $sage;
            break;
        }

        return $sageInfo;
    }
    /**
     * Processing object after load data
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _afterLoad()
    {
        parent::_afterLoad();
		$this->sage_info = $this->_getSagePayInfo($this->getId());

        return $this;
    }


}
