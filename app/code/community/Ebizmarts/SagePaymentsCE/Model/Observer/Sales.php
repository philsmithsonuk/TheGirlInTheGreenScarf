<?php
class Ebizmarts_SagePaymentsCE_Model_Observer_Sales
{
	public function saveBefore($o)
	{
        $order = $o->getEvent()->getOrder();

        $payment = $order->getPayment();

        if ($payment->getMethod() != 'sagepaymentsce') {
            return;
        }
        $order->sage_info = $this->_getSagePayInfo($order->getId());
        if(!$order->sage_info && !$order->getOrigData()) {
        	if($payment->getAuthorized()==1) {
        		$authorized = 1;
        	}
        	else {
        		$authorized = 0;
        	}
        	Mage::getModel('sagepaymentsce/sagepaymentsce_transaction')->addTransaction($order->getId(), $authorized, $payment);
        }
//        return $this;
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

    public function loadAfter($o)
    {
        $order = $o->getEvent()->getOrder();
		$this->sage_info = $this->_getSagePayInfo($order->getId());

//        return $this;
    }



}