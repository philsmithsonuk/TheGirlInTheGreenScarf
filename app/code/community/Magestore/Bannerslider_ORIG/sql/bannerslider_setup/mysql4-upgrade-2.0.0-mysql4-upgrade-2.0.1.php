 <?php

$this->startSetup()->run("  
ALTER TABLE {$this->getTable('bannerslider')}
	CHANGE `content` `banner_content` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''; 
")->endSetup();

?>