<?php

/**
 * Product:       Xtento_CustomTrackers (1.2.1)
 * ID:            hVPLLqeEzuQ5DkfzBxKNNCKve+ibrwXHzqE3QSFFjvk=
 * Packaged:      2012-09-22T09:34:21+00:00
 * Last Modified: 2012-01-23T13:23:07+01:00
 * File:          app/code/local/Xtento/CustomTrackers/Model/System/Config/Source/Order/Status.php
 * Copyright:     Copyright (c) 2012 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_CustomTrackers_Model_System_Config_Source_Order_Status
{
    public function toOptionArray()
    {
        $statuses[] = array('value' => 'no_change', 'label' => Mage::helper('adminhtml')->__('-- No custom status --'));

        if (Mage::helper('xtcore/utils')->mageVersionCompare(Mage::getVersion(), '1.5.0.0', '>=')) {
            # Support for custom order status introduced in Magento 1.5
            $orderStatus = Mage::getModel('sales/order_config')->getStatuses();
            foreach ($orderStatus as $status => $label) {
                $statuses[] = array('value' => $status, 'label' => Mage::helper('adminhtml')->__((string)$label));
            }
        } else {
            $orderStatus = Mage::getModel('adminhtml/system_config_source_order_status')->toOptionArray();
            foreach ($orderStatus as $status) {
                if ($status['value'] == '') {
                    continue;
                }
                $statuses[] = array('value' => $status['value'], 'label' => Mage::helper('adminhtml')->__((string)$status['label']));
            }
        }
        return $statuses;
    }

    // Function to just put all order status "codes" into an array.
    public function toArray()
    {
        $statuses = $this->toOptionArray();
        $statusArray = array();
        foreach ($statuses as $status) {
            if (!isset($statusArray[$status['value']])) {
                array_push($statusArray, $status['value']);
            }
        }
        return $statusArray;
    }

    static function isEnabled()
    {
        return eval(call_user_func('ba' . 'se64_' . 'dec' . 'ode', "JGV4dElkID0gJ1h0ZW50b19DdXN0b21UcmFja2Vyczk5MTIyOSc7DQokc1BhdGggPSAnY3VzdG9tdHJhY2tlcnMvZ2VuZXJhbC8nOw0KJHNOYW1lID0gTWFnZTo6Z2V0TW9kZWwoJ2N1c3RvbXRyYWNrZXJzL3N5c3RlbV9jb25maWdfYmFja2VuZF9pbXBvcnRfc2VydmVyJyktPmdldEZpcnN0TmFtZSgpOw0KJHNOYW1lMiA9IE1hZ2U6OmdldE1vZGVsKCdjdXN0b210cmFja2Vycy9zeXN0ZW1fY29uZmlnX2JhY2tlbmRfaW1wb3J0X3NlcnZlcicpLT5nZXRTZWNvbmROYW1lKCk7DQokcyA9IHRyaW0oTWFnZTo6Z2V0TW9kZWwoJ2NvcmUvY29uZmlnX2RhdGEnKS0+bG9hZCgkc1BhdGggLiAnc2VyaWFsJywgJ3BhdGgnKS0+Z2V0VmFsdWUoKSk7DQppZiAoKCRzICE9PSBzaGExKHNoYTEoJGV4dElkIC4gJ18nIC4gJHNOYW1lKSkpICYmICRzICE9PSBzaGExKHNoYTEoJGV4dElkIC4gJ18nIC4gJHNOYW1lMikpKSB7DQpNYWdlOjpnZXRDb25maWcoKS0+c2F2ZUNvbmZpZygkc1BhdGggLiAnZW5hYmxlZCcsIDApOw0KTWFnZTo6Z2V0Q29uZmlnKCktPmNsZWFuQ2FjaGUoKTsNCk1hZ2U6OmdldFNpbmdsZXRvbignYWRtaW5odG1sL3Nlc3Npb24nKS0+YWRkRXJyb3IoWHRlbnRvX0N1c3RvbVRyYWNrZXJzX01vZGVsX1N5c3RlbV9Db25maWdfQmFja2VuZF9JbXBvcnRfU2VydmVybmFtZTo6TU9EVUxFX01FU1NBR0UpOw0KcmV0dXJuIGZhbHNlOw0KfSBlbHNlIHsNCnJldHVybiB0cnVlOw0KfQ=="));
    }
}