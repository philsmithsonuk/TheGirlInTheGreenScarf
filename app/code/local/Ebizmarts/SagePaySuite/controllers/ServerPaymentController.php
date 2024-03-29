<?php

/**
 * SERVER payment controller
 *
 * @category   Ebizmarts
 * @package    Ebizmarts_SagePaySuite
 * @author     Ebizmarts <info@ebizmarts.com>
 */

class Ebizmarts_SagePaySuite_ServerPaymentController extends Mage_Core_Controller_Front_Action {
	/*
	 * Define end of line character used to correctly format response to Sage Pay Server
	 * @access public
	 */
	public $eoln = "\r\n";

	public function preDispatch()
    {
		$storeId = $this->getRequest()->getParam('storeid');
		if($storeId){
			Mage::app()->setCurrentStore((int)$storeId);
		}

		Mage::getModel('sagepaysuite/api_payment')->getQuote();

        parent::preDispatch();

    	return $this;
    }

	/**
	 * Load and order by its incremental ID attribute
	 *
	 * @access protected
	 * @param $orderId integer The ID of the order
	 * @return Order Object
	 */
	protected function _loadOrderById($orderId) {
		return Mage :: getModel('sales/order')->loadByAttribute('entity_id', (int) $orderId);
	}

	public function getServerModel() {
		return Mage :: getModel('sagepaysuite/sagePayServer');
	}

	public function saveOrderAction() {
		$this->_expireAjax();

		$resultData = array ();

		try {

			Mage::helper('sagepaysuite')->validateQuote();

			$result = $this->getServerModel()->registerTransaction($this->getRequest()->getPost());
			$resultData = $result->getData();

			if ($result->getResponseStatus() == Ebizmarts_SagePaySuite_Model_Api_Payment :: RESPONSE_CODE_APPROVED) {
				$redirectUrl = $result->getNextUrl();
				$resultData['success'] = true;
				$resultData['error'] = false;
			}

		} catch (Exception $e) {
			$resultData['response_status'] = 'ERROR';
			$resultData['response_status_detail'] = $e->getMessage();
		}

		if (isset ($redirectUrl)) {
			$resultData['redirect'] = $redirectUrl;
		}

		return $this->getResponse()->setBody(Zend_Json :: encode($resultData));
	}

	public function getSPSModel() {
		return Mage :: getModel('sagepaysuite/sagePayServer');
	}

	protected function _expireAjax() {
		if (!Mage :: getSingleton('checkout/session')->getQuote()->hasItems()) {
			$this->getResponse()->setHeader('HTTP/1.1', '403 Session Expired')->setHeader('Login-Required', 'true')->sendResponse();
			die();
		}
	}

	public function registertrnAction() {
		if (!Mage :: getSingleton('admin/session')->isLoggedIn()) {
			$this->_expireAjax();
		}


		$result = $this->getSPSModel()->registerTransaction($this->getRequest()->getPost());
		$resultData = $result->getData();

		$response = json_encode($resultData);
		return $this->getResponse()->setBody($response);
	}

	protected function _setAdditioanlPaymentInfo($status) {
		$requestParams = $this->getRequest()->getParams();
		unset ($requestParams['SID']);
		unset ($requestParams['VPSProtocol']);
		unset ($requestParams['TxType']);
		unset ($requestParams['VPSSignature']);

		$requestParams['CustomStatusCode'] = $this->_getSagePayServerSession()->getTrnDoneStatus();
		$info = serialize($requestParams);

		$this->_getSagePayServerSession()->setTrnDoneStatus(null);

		return $info;
	}

	protected function _getAbortRedirectUrl() {
		$url = Mage :: getUrl('sgps/ServerPayment/abortredirect', array (
			'_secure' => true,
			'_current' => true,
		));

		return $url;
	}

	protected function _getSuccessRedirectUrl() {
		$url = Mage :: getUrl('sgps/ServerPayment/success', array (
			'_secure' => true,
			'_current' => true,
		));

		return $url;
	}

	protected function _getFailedRedirectUrl() {
		$url = Mage :: getUrl('sgps/ServerPayment/failure', array (
			'_secure' => true,
			'_current' => true,
		));

		return $url;
	}

	protected function _trn() {
		return Mage :: getModel('sagepaysuite2/sagepaysuite_transaction')->loadByVendorTxCode($this->getRequest()->getParam('VendorTxCode'));
	}

	private function _returnOkAbort()
	{
		$strResponse = 'Status=OK' . $this->eoln;
		$strResponse .= 'StatusDetail=Transaction ABORTED successfully' . $this->eoln;
		$strResponse .= 'RedirectURL=' . $this->_getAbortRedirectUrl() . $this->eoln;

		$this->getResponse()->setHeader('Content-type', 'text/plain');
		$this->getResponse()->setBody($strResponse);
		return;
	}

	private function _returnOk() {
		$strResponse = 'Status=OK' . $this->eoln;
		$strResponse .= 'StatusDetail=Transaction completed successfully' . $this->eoln;
		$strResponse .= 'RedirectURL=' . $this->_getSuccessRedirectUrl() . $this->eoln;

		$this->getResponse()->setHeader('Content-type', 'text/plain');
		$this->getResponse()->setBody($strResponse);
		return;
	}

	private function _returnInvalid($message = 'Unable to find the transaction in our database.') {
		$response = 'Status=INVALID' . $this->eoln;
		$response .= 'RedirectURL=' . $this->_getFailedRedirectUrl() . $this->eoln;
		$response .= 'StatusDetail=' . $message . $this->eoln;

		if(!$this->_getSagePayServerSession()->getFailStatus()){
			$this->_getSagePayServerSession()->setFailStatus($message);
		}

		Sage_Log::log($message);
		Sage_Log::log($this->getRequest()->getPost());
		Sage_log::log($this->_getSagePayServerSession()->getData());

		$this->getResponse()->setHeader('Content-type', 'text/plain');
		$this->getResponse()->setBody($response);
		return;
	}

	protected function _getHRStatus($strStatus, $strStatusDetail) {
		if ($strStatus == 'OK')
			$strDBStatus = 'AUTHORISED - The transaction was successfully authorised with the bank.';
		elseif ($strStatus == 'NOTAUTHED') $strDBStatus = 'DECLINED - The transaction was not authorised by the bank.';
		elseif ($strStatus == 'ABORT') $strDBStatus = 'ABORTED - The customer clicked Cancel on the payment pages, or the transaction was timed out due to customer inactivity.';
		elseif ($strStatus == 'REJECTED') $strDBStatus = 'REJECTED - The transaction was failed by your 3D-Secure or AVS/CV2 rule-bases.';
		elseif ($strStatus == 'AUTHENTICATED') $strDBStatus = 'AUTHENTICATED - The transaction was successfully 3D-Secure Authenticated and can now be Authorised.';
		elseif ($strStatus == 'REGISTERED') $strDBStatus = 'REGISTERED - The transaction was could not be 3D-Secure Authenticated, but has been registered to be Authorised.';
		elseif ($strStatus == 'ERROR') $strDBStatus = 'ERROR - There was an error during the payment process.  The error details are: ' . $strStatusDetail;
		else
			$strDBStatus = 'UNKNOWN - An unknown status was returned from Sage Pay.  The Status was: ' . $strStatus . ', with StatusDetail:' . $strStatusDetail;

		return $strDBStatus;
	}

	public function notifyAction() {

			Sage_Log::log($_POST, null, 'SagePaySuite_POST_Requests.log');

			$request = $this->getRequest();
			$dbtrn = $this->_trn();

			$dbtrn->addData(Mage::helper('sagepaysuite')->arrayKeysToUnderscore($_POST))
				->setPostcodeResult($this->getRequest()->getPost('PostCodeResult'))
				->setData('cv2result', $this->getRequest()->getPost('CV2Result'))
				->setThreedSecureStatus($this->getRequest()->getPost('3DSecureStatus'))
				->setLastFourDigits($this->getRequest()->getPost('Last4Digits'))				
				->save();

			/**
			 * Handle ABORT
			 */
			$sageStatus = $request->getParam('Status');
			if($sageStatus == 'ABORT'){
				$this->_getSagePayServerSession()->setFailStatus($request->getParam('StatusDetail'));
				$dbtrn->setStatus($sageStatus)
				->setStatusDetail($request->getParam('StatusDetail'))
				->save();
				$this->_returnOkAbort();
				return;
			}
			/**
			 * Handle ABORT
			 */

			if ($dbtrn->getId() && $dbtrn->getOrderId()) {
				$dbtrn
					->setStatusDetail("SagePay Retry. " . $dbtrn->getStatusDetail())
					->save();				
				$this->_returnOk();
				return;
			}

			$sagePayServerSession = $this->_getSagePayServerSession();

			$strVendorName = strtolower($this->getSPSModel()->getConfigData('vendor'));

			$strStatus = $request->getParam('Status', '');
			$strVendorTxCode = $request->getParam('VendorTxCode', '');
			$strVPSTxId = $request->getParam('VPSTxId', '');

			$strSecurityKey = '';
			if ($sagePayServerSession->getVendorTxCode() == $strVendorTxCode && $sagePayServerSession->getVpsTxId() == $strVPSTxId) {
				$strSecurityKey = $sagePayServerSession->getSecurityKey();
				$sagePayServerSession->setVpsTxId($strVPSTxId);
			}
			$response = '';
			if (strlen($strSecurityKey) == 0) {
				
				$dbtrn->setStatus('MAGE_ERROR')
					->setStatusDetail("Security Key invalid. " . $dbtrn->getStatusDetail())
					->save();

				$this->_returnInvalid('Security Key invalid');

			} else {

				$strStatusDetail = $strTxAuthNo = $strAVSCV2 = $strAddressResult = $strPostCodeResult = $strCV2Result = $strGiftAid = $str3DSecureStatus = $strCAVV = $strAddressStatus = $strPayerStatus = $strCardType = $strPayerStatus = $strLast4Digits = $strMySignature = '';

				$strVPSSignature = $request->getParam('VPSSignature', '');
				$strStatusDetail = $request->getParam('StatusDetail', '');

				if (strlen($request->getParam('TxAuthNo', '')) > 0) {
					$strTxAuthNo = $request->getParam('TxAuthNo', '');

					$sagePayServerSession->setTxAuthNo($strTxAuthNo);
				}

				$strAVSCV2 = $request->getParam('AVSCV2', '');
				$strAddressResult = $request->getParam('AddressResult', '');
				$strPostCodeResult = $request->getParam('PostCodeResult', '');
				$strCV2Result = $request->getParam('CV2Result', '');
				$strGiftAid = $request->getParam('GiftAid', '');
				$str3DSecureStatus = $request->getParam('3DSecureStatus', '');
				$strCAVV = $request->getParam('CAVV', '');
				$strAddressStatus = $request->getParam('AddressStatus', '');
				$strPayerStatus = $request->getParam('PayerStatus', '');
				$strCardType = $request->getParam('CardType', '');
				$strLast4Digits = $request->getParam('Last4Digits', '');

				$strMessage = $strVPSTxId . $strVendorTxCode . $strStatus . $strTxAuthNo . $strVendorName . $strAVSCV2 . $strSecurityKey . $strAddressResult . $strPostCodeResult . $strCV2Result . $strGiftAid . $str3DSecureStatus . $strCAVV . $strAddressStatus . $strPayerStatus . $strCardType . $strLast4Digits;

				$strMySignature = strtoupper(md5($strMessage));

				$response = '';

				/** We can now compare our MD5 Hash signature with that from Sage Pay Server **/
				$validSignature = ($strMySignature !== $strVPSSignature);

				if ($validSignature) {

				$dbtrn->setStatus('MAGE_ERROR')
					->setStatusDetail("Cannot match the MD5 Hash. " . $dbtrn->getStatusDetail())
					->save();

					$this->_returnInvalid('Cannot match the MD5 Hash. Order might be tampered with. ' . $strStatusDetail);

				} else {

					$strDBStatus = $this->_getHRStatus($strStatus, $strStatusDetail);

					if ($strStatus == 'OK' || $strStatus == 'AUTHENTICATED' || $strStatus == 'REGISTERED') {

						try {
							$sagePayServerSession->setTrnhData($this->_setAdditioanlPaymentInfo($strDBStatus));

							$checkout_session = Mage::getSingleton('checkout/session');
                            if($checkout_session->getSagePayRewInst()){
                                $this->getOnepage()->getQuote()
                                    ->setUseRewardPoints(1)
                                    ->setRewardInstance($checkout_session->getSagePayRewInst());
                            }

                            if($checkout_session->getSagePayCustBalanceInst()){
                                $this->getOnepage()->getQuote()
                                    ->setUseCustomerBalance(1)
                                    ->setCustomerBalanceInstance($checkout_session->getSagePayCustBalanceInst());
                            }

							$this->_getSagePayServerSession()->setInvoicePayment(true);

							Mage::register('sageserverpost', new Varien_Object($_POST));

							$sOrder = $this->_saveMagentoOrder();

							if ($sOrder !== true) {

								$sagePayServerSession->setFailStatus('An error ocurred: ' . $sOrder);

								/** The status indicates a failure of one state or another, so send the customer to orderFailed instead **/
								$strRedirectPage = $this->_getFailedRedirectUrl();

								//Mage::helper('sagepaysuite')->cancelTransaction($dbtrn);

								$dbtrn->setStatus('MAGE_ERROR')
									->setStatusDetail('Could not save order: ' . $sOrder . $dbtrn->getStatusDetail())
									->save();

								$this->_returnInvalid('Could not save order: ' . $sOrder);
							} else {

								$orderId = Mage::registry('last_order_id');
								$msOrderIds = $this->_getMsOrderIds();
								if($orderId || $msOrderIds){

									if(false !== $msOrderIds){
										$aidis = array_keys($msOrderIds);
										$orderId = $aidis[0];
										#Mage::register('ms_parent_trn_id', $dbtrn->getId());
										$dbtrn->setOrderId($aidis[0])->save();

										unset($aidis[0]);
										$trns = Mage::getModel('sagepaysuite2/sagepaysuite_transaction')
												->getCollection()
												->getChilds($dbtrn->getId())
												->load()->toArray();
										foreach($aidis as $_order){
											foreach($trns['items'] as $ka => $_t){
												Mage::getModel('sagepaysuite2/sagepaysuite_transaction')
												->load($_t['id'])->setOrderId($_order)->save();
												unset($trns['items'][$ka]);
												break;
											}
										}
									}

								}

								$dbtrn
									->setOrderId($orderId)
									->save();

								$sagePayServerSession->setSuccessStatus($strDBStatus);

							}

							Mage :: getSingleton('checkout/session')->setSagePayRewInst(null)->setSagePayCustBalanceInst(null);

							$this->_returnOk();
							return;

						} catch (Exception $e) {

						$dbtrn->setStatus('MAGE_ERROR')
							->setStatusDetail($e->getMessage() . $dbtrn->getStatusDetail())
							->save();							
							Mage :: logException($e);
							Mage :: log($e->getMessage());
						}

					} else {

						//Mage::helper('sagepaysuite')->cancelTransaction($this->_trn());

						$dbtrn->setStatus('MAGE_ERROR')
							->setStatusDetail($strDBStatus . $dbtrn->getStatusDetail())
							->save();

						$sagePayServerSession->setFailStatus($strDBStatus);
						/** The status indicates a failure of one state or another, so send the customer to orderFailed instead **/
						$this->_returnInvalid($strDBStatus);
						return;
					}
				}

			}
	}

	public function redirectAction() {
		$this->getResponse()->setBody($this->getLayout()->createBlock('sagepaysuite/checkout_servercallback')->toHtml());
	}

	public function failedAction() {
		$this->getResponse()->setBody($this->getLayout()->createBlock('sagepaysuite/checkout_serverfail')->toHtml());
	}

	public function failureAction() {
		$this->getResponse()->setBody($this->getLayout()->createBlock('sagepaysuite/checkout_serverfail')->toHtml());
	}

	public function successAction() {
		Mage::helper('sagepaysuite')->deleteQuote();
		$this->getResponse()->setBody($this->getLayout()->createBlock('sagepaysuite/checkout_serversuccess')->setMultiShipping($this->_isMultishippingCheckout())->toHtml());
	}

	public function abortredirectAction(){
		$this->getResponse()->setBody($this->getLayout()->createBlock('sagepaysuite/checkout_serverfail')->toHtml());
	}

	protected function _getSagePayServerSession() {
		return Mage::getSingleton('sagepaysuite/session');
	}

	public function getOnepage() {
		return Mage :: getSingleton('checkout/type_onepage');
	}

	/**
	 * Retrieve checkout model
	 *
	 * @return Mage_Checkout_Model_Type_Multishipping
	 */
	public function getMultishipping() {
		return Mage :: getSingleton('checkout/type_multishipping');
	}

	protected function _getMsOrderIds()
    {
        $ids = Mage::getSingleton('core/session')->getOrderIds();
        if ($ids && is_array($ids)) {
            return $ids;
        }
        return false;
    }

	/**
	 * Check if current quote is multishipping
	 */
	protected function _isMultishippingCheckout() {
		return (bool) Mage :: getSingleton('checkout/session')->getQuote()->getIsMultiShipping();
	}

	protected function _getMsState() {
		return Mage :: getSingleton('checkout/type_multishipping_state');
	}

	private function _saveMagentoOrder()
	{
        try {

        	if($this->_isMultishippingCheckout()){

                $this->getOnepage()->getQuote()->collectTotals();

				$this->getMultishipping()->createOrders();
				$this->_getMsState()->setActiveStep(
				                Mage_Checkout_Model_Type_Multishipping_State::STEP_SUCCESS
				            );
	            $this->_getMsState()->setCompleteStep(
	                Mage_Checkout_Model_Type_Multishipping_State::STEP_OVERVIEW
	            );
            	$this->getMultishipping()->getCheckoutSession()->clear();
            	$this->getMultishipping()->getCheckoutSession()->setDisplaySuccess(true);

				return true;

        	}else{

                $this->getOnepage()->getQuote()->collectTotals();
        		$order = $this->getOnepage()->saveOrder();

        		Mage::register('last_order_id', Mage::getSingleton('checkout/session')->getLastOrderId());

				Mage::helper('sagepaysuite')->deleteQuote();

        		return true;

        	}

        }catch (Exception $e) {

        	Mage::log($e->getMessage());
            Mage::logException($e);

            $this->getOnepage()->getQuote()->save();

        	$message = "MAGENTO ERROR: " . $e->getMessage() . ". ";
        	$trn = $this->_trn();
            $trn->setStatusDetail($message . $trn->getStatusDetail())
            ->save();

            return $e->getMessage();
        }
	}

}