<?php
/**
 * Product:     Layered Navigation Pro - 16/08/12
 * Package:     AdjustWare_Nav_2.4.7_0.1.4_8_357526
 * Purchase ID: RtE0qeQE7RRjsdRvhv07l9cGxzFoZAJ502qOJCvubx
 * Generated:   2012-12-20 08:02:01
 * File path:   app/code/local/AdjustWare/Nav/Block/Rewrite/FrontCatalogProductList.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ owCahDBDsaMarBDe('ac62b458de198af2c4a1ce0fa195a7bb'); ?><?php
class AdjustWare_Nav_Block_Rewrite_FrontCatalogProductList extends Mage_Catalog_Block_Product_List
{
     public function __construct(){
        parent::__construct();
        if(Mage::helper('adjnav')->isModuleEnabled('Aitoc_Aitproductslists'))
        {
              $this->setTemplate('aitcommonfiles/design--frontend--base--default--template--catalog--product--list.phtml');
        }
        else
        {
              $this->setTemplate('catalog/product/list.phtml');
        }
    }
    
} } 