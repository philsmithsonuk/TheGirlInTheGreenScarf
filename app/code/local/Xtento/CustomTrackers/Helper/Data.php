<?php

/**
 * Product:       Xtento_CustomTrackers (1.2.1)
 * ID:            hVPLLqeEzuQ5DkfzBxKNNCKve+ibrwXHzqE3QSFFjvk=
 * Packaged:      2012-09-22T09:34:21+00:00
 * Last Modified: 2012-06-01T02:18:45+02:00
 * File:          app/code/local/Xtento/CustomTrackers/Helper/Data.php
 * Copyright:     Copyright (c) 2012 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_CustomTrackers_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getModuleEnabled()
    {
        if (!Mage::getStoreConfigFlag('customtrackers/general/enabled')) {
            return 0;
        }
        $moduleEnabled = Mage::getModel('core/config_data')->load('customtrackers/general/' . str_rot13('frevny'), 'path')->getValue();
        if (empty($moduleEnabled) || !$moduleEnabled || (0x28 !== strlen(trim($moduleEnabled)))) {
            return 0;
        }
        return true;
    }

    /*
    Get tracking url for track
    */
    public function getTrackingUrl($trackingItem, $storeId = 0, $shipment = false)
    {
        if (!$trackingItem) {
            return '';
        }
        $carrierTitle = $trackingItem->getTitle();
        $trackCarrierCode = $trackingItem->getCarrierCode();

        if (Mage::helper('xtcore/utils')->mageVersionCompare(Mage::getVersion(), '1.6.0.0', '>=')) {
            $trackingNumber = $trackingItem->getTrackNumber();
        } else {
            $trackingNumber = $trackingItem->getNumber();
        }

        if ($trackCarrierCode == '') {
            return '';
        }


        $trackingCarriers = Mage::getSingleton('shipping/config')->getAllCarriers($storeId);
        foreach ($trackingCarriers as $carrierCode => $carrierConfig) {
            if ($carrierConfig->isTrackingAvailable() && preg_match("/Custom/", $carrierConfig->getConfigData('name'))) {
                //if ($carrierTitle == $carrierConfig->getConfigData('title')) {
                if ($carrierCode == $trackCarrierCode) {
                    return preg_replace(array("/#TRACKINGNUMBER#/i", "/#ZIP#/i"), array($trackingNumber, $this->getShippingPostcode($shipment)), $carrierConfig->getConfigData('url'));
                }
            }
        }
        return '';
    }

    /*
    * Get recipients postcode for current shipment/order
    */
    public function getShippingPostcode($shipment = false)
    {
        $postcode = '';

        if (!$shipment) {
            $shipment = Mage::registry('xt_current_shipment');
        }
        if ($shipment && $shipment->getId() && $shipment->getOrderId()) {
            $order = Mage::getModel('sales/order')->load($shipment->getOrderId());
            if ($order && $order->getId()) {
                $shippingAddress = $order->getShippingAddress();
                if ($shippingAddress) {
                    $postcode = $shippingAddress->getPostcode();
                }
            }
            Mage::unregister('xt_current_shipment');
        }
        return $postcode;
    }
}