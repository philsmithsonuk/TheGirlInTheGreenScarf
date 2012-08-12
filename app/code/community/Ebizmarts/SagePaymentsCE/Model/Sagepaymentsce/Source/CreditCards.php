<?php

class Ebizmarts_SagePaymentsCE_Model_Sagepaymentsce_Source_CreditCards
{
    public function toOptionArray()
    {
        $options =  array();

        foreach (Mage::getSingleton('payment/config')->getCcTypesSagePayments() as $code => $name) {
        	$options[] = array(
        	   'value' => $code,
        	   'label' => $name
        	);
        }

        return $options;
    }
}