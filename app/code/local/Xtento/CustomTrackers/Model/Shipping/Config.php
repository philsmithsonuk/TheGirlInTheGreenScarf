<?php

/**
 * Product:       Xtento_CustomTrackers (1.2.1)
 * ID:            hVPLLqeEzuQ5DkfzBxKNNCKve+ibrwXHzqE3QSFFjvk=
 * Packaged:      2012-09-22T09:34:21+00:00
 * Last Modified: 2012-02-10T12:57:52+01:00
 * File:          app/code/local/Xtento/CustomTrackers/Model/Shipping/Config.php
 * Copyright:     Copyright (c) 2012 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_CustomTrackers_Model_Shipping_Config extends Mage_Shipping_Model_Config
{
    public function getActiveCarriers($store = null)
    {
        $originalCarriers = parent::getActiveCarriers($store);

        if (!Mage::helper('customtrackers/data')->getModuleEnabled()) {
            return $originalCarriers;
        }

        $disabledCarriers = explode(",", Mage::getStoreConfig('customtrackers/general/disabled_carriers'));
        foreach ($originalCarriers as $code => $carrierConfig) {
            if (in_array($code, $disabledCarriers)) {
                unset($originalCarriers[$code]);
            }
        }

        /* Get new trackers */
        $carriers = array();
        $config = Mage::getStoreConfig('customtrackers', $store);
        foreach ($config as $code => $carrierConfig) {
            if ($code == 'general') continue;
            if (Mage::getStoreConfigFlag('customtrackers/' . $code . '/active', $store)) {
                $carrierModel = $this->_getCarrier($code, $carrierConfig, $store);
                if ($carrierModel) {
                    $carriers[$code] = $carrierModel;
                }
            }
        }
        return array_merge($originalCarriers, $carriers);
    }

    public function getAllCarriers($store = null, $showAllCarriers = false)
    {
        $originalCarriers = parent::getAllCarriers($store);

        if (!Mage::helper('customtrackers/data')->getModuleEnabled()) {
            return $originalCarriers;
        }

        if (!$showAllCarriers) {
            $disabledCarriers = explode(",", Mage::getStoreConfig('customtrackers/general/disabled_carriers'));
            foreach ($originalCarriers as $code => $carrierConfig) {
                if (in_array($code, $disabledCarriers)) {
                    unset($originalCarriers[$code]);
                }
            }
        }

        /* Get new trackers */
        $carriers = array();
        $config = Mage::getStoreConfig('customtrackers', $store);
        foreach ($config as $code => $carrierConfig) {
            if ($code == 'general') continue;
            $model = $this->_getCarrier($code, $carrierConfig, $store);
            if ($model) {
                $carriers[$code] = $model;
            }
        }
        return array_merge($originalCarriers, $carriers);
    }

    public function getCarrierInstance($carrierCode, $store = null)
    {
        if (!Mage::helper('customtrackers/data')->getModuleEnabled()) {
            return parent::getCarrierInstance($carrierCode, $store);
        }
        $carrierConfig = Mage::getStoreConfig('carriers/' . $carrierCode, $store);
        if (empty($carrierConfig)) {
            $carrierConfig = Mage::getStoreConfig('customtrackers/' . $carrierCode, $store);
        }
        if (!empty($carrierConfig)) {
            return $this->_getCarrier($carrierCode, $carrierConfig, $store);
        }
        return false;
    }
}
