<?php

class Ebizmarts_SagePaymentsCE_Block_Payment_Form_SagePaymentsCE extends Mage_Payment_Block_Form_Cc
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payment/form/sagePaymentsCE.phtml');
    }
    public function getSagePaymentsAvailableTypes()
    {
        $types = $this->_getConfig()->getCcTypesSagePayments();
        if ($method = $this->getMethod()) {
            $availableTypes = $method->getConfigDataSagePaymentsCE('cctypesSagePaymentsCE');
            if ($availableTypes) {
                $availableTypes = explode(',', $availableTypes);
                foreach ($types as $code=>$name) {
                    if (!in_array($code, $availableTypes)) {
                        unset($types[$code]);
                    }
                }
            }
        }
        return $types;
    }

        /**
     * Retrieve credit card expire months
     *
     * @return array
     */
    public function getCcStartMonths()
    {
        $months = $this->getData('cc_months');
        if (is_null($months)) {
            $months[0] =  $this->__('Month');
            $months = array_merge($months, $this->_getConfig()->getMonths());
            $this->setData('cc_months', $months);
        }
        return $months;
    }

    /**
     * Retrieve credit card expire years
     *
     * @return array
     */
    public function getCcStartYears()
    {
        $years = $this->_getConfig()->getYearsStart();
        $years = array(0=>$this->__('Year'))+$years;
        $this->setData('cc_years', $years);

        return $years;
    }
}