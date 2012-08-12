<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
		ALTER TABLE {$this->getTable('sagepaymentsce_transactions')}
				ADD COLUMN `cvv_indicator` CHAR(1),
				ADD COLUMN `avs_indicator` CHAR(1)
");


$installer->endSetup();