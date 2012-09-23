<?php

/**
 * Product:       Xtento_CustomTrackers (1.2.1)
 * ID:            hVPLLqeEzuQ5DkfzBxKNNCKve+ibrwXHzqE3QSFFjvk=
 * Packaged:      2012-09-22T09:34:21+00:00
 * Last Modified: 2012-02-10T00:33:09+01:00
 * File:          app/code/local/Xtento/CustomTrackers/Model/Shipping/Carrier/Abstract.php
 * Copyright:     Copyright (c) 2012 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_CustomTrackers_Model_Shipping_Carrier_Abstract extends Mage_Shipping_Model_Carrier_Abstract
{
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        // Not used for shipping.. so just return an empty result.
        return Mage::getModel('shipping/rate_result');
    }

    public function getConfigData($field)
    {
        if (empty($this->_code)) {
            return false;
        }
        $path = 'customtrackers/' . $this->_code . '/' . $field;
        return Mage::getStoreConfig($path, $this->getStore());
    }

    public function getConfigFlag($field)
    {
        if (empty($this->_code)) {
            return false;
        }
        $path = 'customtrackers/' . $this->_code . '/' . $field;
        return Mage::getStoreConfigFlag($path, $this->getStore());
    }

    public function isTrackingAvailable()
    {
        if (!Mage::helper('customtrackers/data')->getModuleEnabled()) {
            return false;
        }
        if (!$this->isActive()) {
            return false;
        }
        return true;
    }

    /*
     * Taken from Mage_Usa_Model_Shipping_Carrier_Abstract
     */
    public function getTrackingInfo($tracking)
    {
        $result = $this->getTracking($tracking);

        if ($result instanceof Mage_Shipping_Model_Tracking_Result) {
            if ($trackings = $result->getAllTrackings()) {
                return $trackings[0];
            }
        }
        elseif (is_string($result) && !empty($result)) {
            return $result;
        }

        return false;
    }

    /*
     * Based on Mage_Usa_Model_Shipping_Carrier_Dhl
     */
    public function getTracking($trackings)
    {
        $r = new Varien_Object();
        $id = $this->getConfigData('id');
        $r->setId($id);
        $this->_rawTrackRequest = $r;

        if (!is_array($trackings)) {
            $trackings = array($trackings);
        }
        $this->_getCgiTracking($trackings);

        return $this->_result;
    }

    /*
     * Based on Mage_Usa_Model_Shipping_Carrier_Ups
     */
    protected function _getCgiTracking($trackings)
    {
        $result = Mage::getModel('shipping/tracking_result');
        //$defaults = $this->getDefaults();
        foreach ($trackings as $trackingNumber) {
            $status = Mage::getModel('shipping/tracking_result_status');
            $status->setCarrierTitle($this->getConfigData('title'));
            $status->setCarrier('tracker');
            $status->setTracking($trackingNumber);
            $status->setPopup(true);
            $status->setUrl(preg_replace(array("/#TRACKINGNUMBER#/i", "/#ZIP#/i"), array($trackingNumber, Mage::helper('customtrackers')->getShippingPostcode()), $this->getConfigData('url')));
            $result->append($status);
        }

        $this->_result = $result;
        return $result;
    }
}