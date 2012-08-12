<?php
/*
 * Created on Jun 1, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class Ebizmarts_SagePaymentsCE_Model_Sales_Order_Api extends Mage_Sales_Model_Order_Api
 {
   public function info($orderIncrementId)
    {
    	Mage::log(__METHOD__);
        $order = $this->_initOrder($orderIncrementId);

        $result = $this->_getAttributes($order, 'order');

        $result['shipping_address'] = $this->_getAttributes($order->getShippingAddress(), 'order_address');
        $result['billing_address']  = $this->_getAttributes($order->getBillingAddress(), 'order_address');
        $result['items'] = array();

        foreach ($order->getAllItems() as $item) {
            $result['items'][] = $this->_getAttributes($item, 'order_item');
        }

        $payment = $this->_getAttributes($order->getPayment(), 'order_payment');
        /*
         * retrieve the data saved from the module and put into the payment
         */
        $sageInfo = $this->_getSagePayInfo($order->getId());
        if($sageInfo) {
	        foreach($sageInfo->getData() as $attribute=>$value)
	        {
	        	$payment[$attribute] = $value;
	        }
        }
        $result['payment'] = $payment;



        $result['status_history'] = array();

        foreach ($order->getAllStatusHistory() as $history) {
            $result['status_history'][] = $this->_getAttributes($history, 'order_status_history');
        }

        return $result;
    }


    public function items($filters = null)
    {

        //TODO: add full name logic
        $billingAliasName = 'billing_o_a';
        $shippingAliasName = 'shipping_o_a';

        $collection = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToSelect('*')
            ->addAddressFields()
            ->addExpressionFieldToSelect(
                'billing_firstname', "{{billing_firstname}}", array('billing_firstname'=>"$billingAliasName.firstname")
            )
            ->addExpressionFieldToSelect(
                'billing_lastname', "{{billing_lastname}}", array('billing_lastname'=>"$billingAliasName.lastname")
            )
            ->addExpressionFieldToSelect(
                'shipping_firstname', "{{shipping_firstname}}", array('shipping_firstname'=>"$shippingAliasName.firstname")
            )

            ->addExpressionFieldToSelect(
                'shipping_lastname', "{{shipping_lastname}}", array('shipping_lastname'=>"$shippingAliasName.lastname")
            )

            ->addExpressionFieldToSelect(
                    'billing_name',
                    "CONCAT({{billing_firstname}}, ' ', {{billing_lastname}})",
                    array('billing_firstname'=>"$billingAliasName.firstname", 'billing_lastname'=>"$billingAliasName.lastname")
            )
            ->addExpressionFieldToSelect(
                    'shipping_name',
                    'CONCAT({{shipping_firstname}}, " ", {{shipping_lastname}})',
                    array('shipping_firstname'=>"$shippingAliasName.firstname", 'shipping_lastname'=>"$shippingAliasName.lastname")

            );



        if (is_array($filters)) {

            try {

                foreach ($filters as $field => $value) {
                    if (isset($this->_attributesMap['order'][$field])) {
                        $field = $this->_attributesMap['order'][$field];
                    }

                    $collection->addFieldToFilter($field, $value);
                }
            } catch (Mage_Core_Exception $e) {
                $this->_fault('filters_invalid', $e->getMessage());
            }
        }

        $result = array();

        foreach ($collection as $order){

            $result[] = $this->_getAttributes($order, 'order');

            $sageInfo = $this->_getSagePayInfo($order->getId());

              if($sageInfo) {
                    foreach($sageInfo->getData() as $attribute=>$value){
                        $result[count($result)-1]['payment'][$attribute] = $value;
                    }
              }

        }

        return $result;
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

 }
?>
