<?php
$this->startSetup();

Mage::getConfig()->saveConfig('xtcore/adminnotification/installation_date', time());

$this->endSetup();