 <?php

$this->startSetup()->run("  
ALTER TABLE {$this->getTable('bannerslider')}
	CHANGE `content` `banner_content` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '', 
	ADD COLUMN `sub_title` varchar(255) NOT NULL default ''  AFTER `banner_content`,
	ADD COLUMN `button_text` varchar(255) NOT NULL default ''  AFTER `sub_title`;	
")->endSetup();

?>