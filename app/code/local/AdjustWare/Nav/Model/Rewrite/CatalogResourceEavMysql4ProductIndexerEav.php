<?php
/**
 * Product:     Layered Navigation Pro - 07/06/12
 * Package:     AdjustWare_Nav_2.4.2_0.1.4_8_300402
 * Purchase ID: QmqwcKnSEMUDkX35fJBKkoOUk2rsivOit75vaVFw7E
 * Generated:   2012-06-12 14:40:26
 * File path:   app/code/local/AdjustWare/Nav/Model/Rewrite/CatalogResourceEavMysql4ProductIndexerEav.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ picrwZeZsrgrkeZj('ab11072a87cb1bef7a31ab4fae2a0470'); ?><?php

class AdjustWare_Nav_Model_Rewrite_CatalogResourceEavMysql4ProductIndexerEav extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Indexer_Eav
{
    protected function _construct()
    {
        parent::_construct();

        $this->getIndexers();
        $this->_types['configurable'] = Mage::getResourceModel('adjnav/catalog_product_indexer_configurable');
    }
} } 