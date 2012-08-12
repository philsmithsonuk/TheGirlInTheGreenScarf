<?php
class Ebizmarts_SagePaymentsCE_Model_SageMethod extends Mage_Payment_Model_Method_Cc
{

	protected $_code = 'sagepaymentsce';
	protected $_formBlockType = 'payment/form_sagepaymentsce';
	protected $_isGateway               = true;
	protected $_canAuthorize            = false;
	protected $_canCapture              = true;
	protected $_canCapturePartial       = false;
	protected $_canRefund               = true;
	protected $_canVoid                 = false;
	protected $_canUseInternal          = false;
	protected $_canUseCheckout          = true;
	protected $_canUseForMultishipping  = false;
	protected $_canSaveCc 				= false;

	const REQUEST_TYPE_PAYMENT = 'PAYMENT';

	const RESPONSE_CODE_APPROVED = 'A';
	const RESPONSE_CODE_REJECTED = 'X';
	const RESPONSE_CODE_NOTAUTHED = 'E';
	const STATUS_OK = 'OK';
	const STATUS_AUTH = 'OK_AUTH';
	const ACTION_PAYMENT = 1;
	const CODE_PAYMENT = '01';

    public function getConfigDataSagePaymentsCE($field) {
        $path = 'payment/sagepaymentsce/'.$field;
        $config = Mage::getStoreConfig($path, $this->getStore());

        return $config;
    }

	private function cleanString($text)
	{
	        $pattern = '|[^a-zA-Z0-9\-\._]+|';
	        $text = preg_replace($pattern, '', $text);

	        return $text;
	}

   private function _getSagePayInfo($orderId) {
		Mage::log(__METHOD__);
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

	public function gtCnfgDta($field)
	{
	    $path = 'payment/sagepaymentsce/'.$field;
		$config = Mage::getStoreConfig($path, $this->getStore());

	    return $config;
	}

	public function assignData($data)
	{
	    if (!($data instanceof Varien_Object)) {
	        $data = new Varien_Object($data);
	    }
	    $info = $this->getInfoInstance();
	    $info->setCcType($data->getCcType())
	        ->setCcOwner($data->getCcOwner())
	        ->setCcLast4(substr($data->getCcNumber(), -4))
	        ->setCcNumber($data->getCcNumber())
	        ->setCcCid($data->getCcCid())
	        ->setCcExpMonth($data->getCcExpMonth())
	        ->setCcExpYear($data->getCcExpYear())
	    // New added fields
	        ->setCcIssue($data->getCcIssue())
	        ->setCcStartMonth($data->getCcStartMonth())
	        ->setCcStartYear($data->getCcStartYear());
	    return $this;
	}
	public function capture(Varien_Object $payment, $amount)
	{
		Mage::log(__METHOD__);
		Mage::getSingleton('checkout/session')->setMd(null)
			->setAcsurl(null)
			->setPareq(null);

		$error = false;

		$payment->setAnetTransType(self::REQUEST_TYPE_PAYMENT);
		$payment->setAmount($amount);

		$data= $this->_buildRequest($payment);
		$result = $this->_postRequest($data, self::CODE_PAYMENT);

		if ($result->getResponseStatus() == self::RESPONSE_CODE_APPROVED) {
		    $payment
		    	->setStatus(self::RESPONSE_CODE_APPROVED)
		    	->setCcTransId($result->getTrnSecuritykey())
				->setCcApproval(self::RESPONSE_CODE_APPROVED)
				->setSecurityKey($result->getPostCodeResult())
				->setCv2Result($result->getCV2Result())
				->setCvvIndicator($result->getCV2Result())
	        	->setAvsIndicator($result->getAVSCV2())
				->setCcCidStatus($result->getTxAuthNo())
				->setAuthorized(1);
		}
		else{
		    if ($result->getResponseStatusDetail()) {
		        $error = '';
		if ($result->getResponseStatus() == self::RESPONSE_CODE_NOTAUTHED) {
			$error = Mage::helper('sagepaymentsce')->__('Your credit card can not be authenticated: ');
		} else if ($result->getResponseStatus() == self::RESPONSE_CODE_REJECTED) {
			$error = Mage::helper('sagepaymentsce')->__('Your credit card was rejected: ');
		    }
		    $error .= $result->getResponseStatusDetail();
		}
		else {
		    $error = Mage::helper('sagepaymentsce')->__('Error in capturing the payment');
		        }
		    }
	    if ($error !== false) {
	        Mage::throwException($error);
	    }
	    return $this;
	}


	protected function _postRequest($data, $operation)
	{
		Mage::log($data);
		$storeId = $this->getStoreId();
		try {
			$client = new SoapClient(Mage::getStoreConfig('payment/sagepaymentsce/api',$storeId));
			switch($operation) {
				case self::CODE_PAYMENT:
					$rc = $client->BANKCARD_SALE($data);
					$res = $rc->BANKCARD_SALEResult->any;
					break;
				default:
					Mage::throwException("Operation not allowed with this module version, try with the Pro Version");
					break;
			}
		}
		catch(exception $e) {
			Mage::throwException("Communication error: your server is not capable to communicate with Sage Payment Solutions server");
		}
		Mage::log($res);
	    $result = Mage::getModel('sagepaymentsce/sagepaymentsce_result');
		$rDoc= new DOMDocument();
		$rDoc->loadXML($res);
		$approvalIndicator = $rDoc->getElementsByTagName('APPROVAL_INDICATOR');
		if($approvalIndicator->length>0) {
			$result->setResponseStatus($approvalIndicator->item(0)->nodeValue);
		}
		$code = $rDoc->getElementsByTagName('CODE');
		if($code->length>0) {
			$result->setPostCodeResult($code->item(0)->nodeValue);
		}
		$message = $rDoc->getElementsByTagName('MESSAGE');
		if($message->length >0) {
			$result->setResponseStatusDetail($message->item(0)->nodeValue);
		}
		$cvv_indicator = $rDoc->getElementsByTagName('CVV_INDICATOR');
		if($cvv_indicator->length>0) {
			$result->setCV2Result($cvv_indicator->item(0)->nodeValue);
		}
		$avs_indicator = $rDoc->getElementsByTagName('AVS_INDICATOR');
		if($avs_indicator->length>0) {
			$result->setAVSCV2($avs_indicator->item(0)->nodeValue);
		}
		$risk_indicator = $rDoc->getElementsByTagName('RISK_INDICATOR');
		if($risk_indicator->length>0) {
			$result->setRisk($risk_indicator->item(0)->nodeValue);
		}
		$reference = $rDoc->getElementsByTagName('REFERENCE');
		if($reference->length>0) {
			$result->setTrnSecuritykey($reference->item(0)->nodeValue);
		}
		$order_number = $rDoc->getElementsByTagName('ORDER_NUMBER');
		if($order_number->length>0) {
			$result->setOrderNum($order_number->item(0)->nodeValue);
		}
		Mage::log($result);
	    return $result;
	}
	protected function _buildRequest(Varien_Object $payment)
	{
		Mage::log(__METHOD__);

	    $order = $payment->getOrder();
		$data = array();
		$storeId = $this->getStoreId();
		$m_id = Mage::getStoreConfig('payment/sagepaymentsce/m_id',$storeId);
		$m_key = Mage::getStoreConfig('payment/sagepaymentsce/m_key',$storeId);
		$data['M_ID'] = $m_id;
		$data['M_KEY'] = $m_key;

	    if (!empty($order)) {
	        $billing = $order->getBillingAddress();
	        if (!empty($billing)) {
				$data['C_NAME'] = $billing->getData('lastname') . ' ' . $billing->getData('firstname');
				$data['C_ADDRESS'] = $billing->getStreet(1);
				$data['C_CITY'] = $billing->getCity();
				$data['C_STATE'] = $billing->getRegion();
				$data['C_ZIP'] = $billing->getPostcode();
				$data['C_COUNTRY'] = $billing->getCountry();
				if (!preg_match("/^([a-zA-Z0-9])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+/",
	            		$order->getData('customer_email'))) {
	            			$data['C_EMAIL']= "";
	            } else {
	            	$data['C_EMAIL']= $order->getData('customer_email');
	            }
	        }

	        $shipping = $order->getShippingAddress();
	        if (!empty($shipping)) {
				$data['C_SHIPPING_NAME'] = $shipping->getData('lastname') . ' ' . $shipping->getData('firstname');
				$data['C_SHIPPING_ADDRESS'] = $shipping->getStreet(1);
				$data['C_SHIPPING_CITY'] = $shipping->getCity();
				$data['C_SHIPPING_STATE'] = $shipping->getRegion();
				$data['C_SHIPPING_ZIP'] = $shipping->getPostcode();
				$data['C_SHIPPING_COUNTRY'] = $shipping->getCountry();
				$data['C_TELEPHONE'] = $shipping->getTelephone();

	        } else {
	        	#If the cart only has virtual products, I need to put an shipping address to Sage Pay.
	        	#Then the billing address will be the shipping address to
				$data['C_SHIPPING_NAME']= $billing->getLastName() . ' ' . $billing->getFirstName();
				$data['C_SHIPPING_NAME']= $billing->getStreet(1);
				$data['C_SHIPPING_CITY']= $billing->getCity();
				$data['C_SHIPPING_STATE']= $billing->getRegion();
				$data['C_SHIPPING_ZIP']= $billing->getPostcode();
				$data['C_SHIPPING_COUNTRY']= $billing->getCountry();
	        }
	    }

	    if($payment->getCcNumber()){
			$data['C_CARDNUMBER']= $payment->getCcNumber();
			$data['C_EXP']= sprintf('%02d%02d', $payment->getCcExpMonth(), substr($payment->getCcExpYear(), strlen($payment->getCcExpYear()) - 2));
	    }
		$data['T_AMT']= $order->getData('grand_total');
		$data['T_SHIPPING'] = '';
		$data['T_TAX'] = '';
		$data['T_ORDERNUM'] = $order->getIncrementId();
		$data['C_CVV'] = $payment->getCcCid();
	    return $data;
	}
}
?>