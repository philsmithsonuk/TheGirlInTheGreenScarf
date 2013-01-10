 <?php

$this->startSetup()->run("  
ALTER TABLE {$this->getTable('bannerslider')} 
	ADD COLUMN `thumbnail` varchar(255) NOT NULL default ''  AFTER `button_text`;	
")->endSetup();
 
?>