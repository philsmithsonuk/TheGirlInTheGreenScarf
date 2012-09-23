<?php

/**
 * Product:       Xtento_XtCore (1.0.0)
 * ID:            hVPLLqeEzuQ5DkfzBxKNNCKve+ibrwXHzqE3QSFFjvk=
 * Packaged:      2012-09-22T09:34:21+00:00
 * Last Modified: 2012-02-26T01:08:29+01:00
 * File:          app/code/local/Xtento/XtCore/Model/System/Config/Source/Carriers.php
 * Copyright:     Copyright (c) 2012 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_XtCore_Model_System_Config_Source_Carriers
{
    public function toOptionArray()
    {
        $carriers = array();
        $carriers[] = array('value' => 'custom', 'label' => Mage::helper('adminhtml')->__('Custom Carrier'));
        foreach (Mage::getSingleton('shipping/config')->getAllCarriers() as $carrierCode => $carrierConfig) {
            if ($carrierConfig->isTrackingAvailable()) {
                $carriers[] = array('value' => $carrierCode, 'label' => $this->_determineCarrierTitle($carrierCode));
            }
        }
        return $carriers;
    }

    private function _determineCarrierTitle($carrierCode)
    {
        if (!isset($this->_carriers[$carrierCode])) {
            if ($carrierCode == 'custom') {
                $this->_carriers[$carrierCode] = 'Custom';
            } else {
                $this->_carriers[$carrierCode] = Mage::getStoreConfig('carriers/' . $carrierCode . '/title');
                if (empty($this->_carriers[$carrierCode])) {
                    $this->_carriers[$carrierCode] = Mage::getStoreConfig('customtrackers/' . $carrierCode . '/title');
                }
            }
        }
        return $this->_carriers[$carrierCode];
    }

}
