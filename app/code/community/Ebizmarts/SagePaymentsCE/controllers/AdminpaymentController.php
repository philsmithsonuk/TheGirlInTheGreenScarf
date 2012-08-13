<?php
class Ebizmarts_SagePaymentsCE_AdminpaymentController extends Mage_Adminhtml_Controller_Action
 {
    public function errorAction()
    {
		$this->_redirect('checkout/cart');
    }


/*	public function cancelPaymentAction()
	{
		$params = $this->getRequest()->getParams();

		$order = Mage::getModel('sales/order')->loadByAttribute('entity_id', (int)$params['orderID']);
    	$payment = $order->getPayment();

		$payment->setLggdInAdminUname($params['usrname']);
		try {
			$result = Mage::getModel('sagepaymentsce/sageMethod')->cancel($payment);
		}
		catch(Mage_Core_Exception $e)
		{
			$result = $e->getMessage();
		}
		catch(Exception $e1)
		{
			$result = $e1->getMessage();
		}

		return $this->getResponse()->setBody('OK');
	}

*/
	public function captureAction()
	{
		$params = $this->getRequest()->getParams();

		$order = Mage::getModel('sales/order')->loadByAttribute('entity_id', (int)$params['orderID']);
    	$payment = $order->getPayment();

//		$payment->setRefundDescrPrtx($params['descr']);
		try {
			$result = Mage::getModel('sagepaymentsce/sageMethod')->capturePayment($payment, $params['amount']);
		}
		catch(Mage_Core_Exception $e)
		{
			$result = $e->getMessage();
		}
		catch(Exception $e1)
		{
			$result = $e1->getMessage();
		}
		return $this->getResponse()->setBody($result);

	}


 }
