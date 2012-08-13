<?php
/*
 * Created on May 20, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class Ebizmarts_SagePaymentsCE_Block_Adminhtml_Sales_Order_Payment extends Mage_Adminhtml_Block_Sales_Order_Payment
 {
     protected function _prepareLayout()
    {
    	parent::_prepareLayout();

//		if(!$this->getRequest()->isXmlHttpRequest()){
//    		$this->getLayout()->getBlock('head')
//     		->addItem('skin_js', 'SagePaymentsPro.minified.js');
//		}
    }

    public function setPayment($payment)
    {
    	parent::setPayment($payment);
        $paymentInfoBlock = Mage::helper('payment')->getInfoBlock($payment);

        if ($payment->getMethod() == 'sagepaymentsce') {

            $paymentInfoBlock->setTemplate('payment/info/cc_sagepaymentsce.phtml');

        }
        $this->setChild('info', $paymentInfoBlock);
        $this->setData('payment', $payment);
        return $this;
    }

    protected function _toHtml()
    {
        return $this->getChildHtml('info');
    }

 }
?>
