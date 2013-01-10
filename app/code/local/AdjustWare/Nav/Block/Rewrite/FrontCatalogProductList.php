<?php
/**
 * Product:     Layered Navigation Pro - 07/06/12
 * Package:     AdjustWare_Nav_2.4.2_0.1.4_8_300402
 * Purchase ID: QmqwcKnSEMUDkX35fJBKkoOUk2rsivOit75vaVFw7E
 * Generated:   2012-06-12 14:40:26
 * File path:   app/code/local/AdjustWare/Nav/Block/Rewrite/FrontCatalogProductList.data.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ piNVCMkMsVaVdkWy('ac62b458de198af2c4a1ce0fa195a7bb'); ?><?php
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