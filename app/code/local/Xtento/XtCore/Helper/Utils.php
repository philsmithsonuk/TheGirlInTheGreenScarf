<?php

/**
 * Product:       Xtento_XtCore (1.0.0)
 * ID:            hVPLLqeEzuQ5DkfzBxKNNCKve+ibrwXHzqE3QSFFjvk=
 * Packaged:      2012-09-22T09:34:21+00:00
 * Last Modified: 2012-05-26T16:17:32+02:00
 * File:          app/code/local/Xtento/XtCore/Helper/Utils.php
 * Copyright:     Copyright (c) 2012 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_XtCore_Helper_Utils extends Mage_Core_Helper_Abstract
{
    protected $_modules = false;

    protected $_versionCorrelationEE_CE = array(
        '1.9.1.1' => '1.4.2.0',
        '1.10.0.1' => '1.5.0.1',
        '1.10.1.1' => '1.5.1.0',
        '1.11.0.0' => '1.6.0.0',
        '1.11.1.0' => '1.6.1.0',
    );

    protected $_versionCorrelationPE_CE = array(
        '1.10.0.1' => '1.5.0.1',
        '1.11.0.0' => '1.6.0.0',
        '1.11.1.0' => '1.6.1.0',
    );

    /* Thanks for the inspiration to Sortal. */
    public function mageVersionCompare($version1, $version2, $operator)
    {
        // Detect edition by included modules
        if (!$this->_modules) {
            $this->_modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());
        }

        if (in_array('Enterprise_CatalogPermissions', $this->_modules)) {
            // Detected enterprise edition
            if (!isset($this->_versionCorrelationEE_CE[$version1])) {
                return version_compare($version1, $version2, $operator);
            } else {
                return version_compare($this->_versionCorrelationEE_CE[$version1], $version2, $operator);
            }
        } elseif (in_array('Enterprise_Enterprise', $this->_modules)) {
            // Detected professional edition
            if (!isset($this->_versionCorrelationPE_CE[$version1])) {
                return version_compare($version1, $version2, $operator);
            } else {
                return version_compare($this->_versionCorrelationPE_CE[$version1], $version2, $operator);
            }
        } else {
            // Detected community edition
            return version_compare($version1, $version2, $operator);
        }
    }

    // Check if a third party extension is installed and enabled
    public function isExtensionInstalled($extensionIdentifer)
    {
        if (!$this->_modules) {
            $this->_modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());
        }
        if (in_array($extensionIdentifer, $this->_modules)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Is the module running inside professional or enterprise edition?
     */
    public function getIsPEorEE()
    {
        // Detect edition by included modules
        if (!$this->_modules) {
            $this->_modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());
        }

        if (in_array('Enterprise_CatalogPermissions', $this->_modules)) {
            // Detected enterprise edition
            return true;
        } elseif (in_array('Enterprise_Enterprise', $this->_modules)) {
            // Detected professional edition
            return true;
        } else {
            // Detected community edition
            return false;
        }
    }

    public function isEECECompatbile()
    {
        // Todo: <platform> tag
    }
}