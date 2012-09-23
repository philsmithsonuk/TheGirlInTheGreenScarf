<?php

/**
 * Product:       Xtento_CustomTrackers (1.2.1)
 * ID:            hVPLLqeEzuQ5DkfzBxKNNCKve+ibrwXHzqE3QSFFjvk=
 * Packaged:      2012-09-22T09:34:21+00:00
 * Last Modified: 2012-02-10T00:37:18+01:00
 * File:          app/code/local/Xtento/CustomTrackers/Model/Shipping/Carrier/Tracker5.php
 * Copyright:     Copyright (c) 2012 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_CustomTrackers_Model_Shipping_Carrier_Tracker5 extends Xtento_CustomTrackers_Model_Shipping_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface
{
    protected $_code = 'tracker5';

    public function getAllowedMethods()
    {
        return array($this->_code => $this->getConfigData('name'));
    }

    public function isTrackingAvailable()
    {
        return parent::isTrackingAvailable();
    }
}