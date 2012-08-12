<?php

class Ebizmarts_SagePaymentsCE_Model_Payment_Config extends Mage_Payment_Model_Config
{

    /**
     * Retrieve array of credit card types
     *
     * @return array
     */
    public function getCcTypesSagePayments()
    {
        $types = array();
        foreach (Mage::getConfig()->getNode('global/payment/sagepayments_cards/types')->asArray() as $data) {
        	$types[$data['code']] = $data['name'];
        }
        return $types;
    }

}