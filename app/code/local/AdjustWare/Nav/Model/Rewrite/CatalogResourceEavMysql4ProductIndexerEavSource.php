<?php
/**
 * Product:     Layered Navigation Pro - 07/06/12
 * Package:     AdjustWare_Nav_2.4.2_0.1.4_8_300402
 * Purchase ID: QmqwcKnSEMUDkX35fJBKkoOUk2rsivOit75vaVFw7E
 * Generated:   2012-06-12 14:40:26
 * File path:   app/code/local/AdjustWare/Nav/Model/Rewrite/CatalogResourceEavMysql4ProductIndexerEavSource.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ piNVCMkMsVaVdkWy('9e9d52dac36b0f863ed0d05ea965e9cf'); ?><?php

class AdjustWare_Nav_Model_Rewrite_CatalogResourceEavMysql4ProductIndexerEavSource extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Indexer_Eav_Source
{
    /** Reindex updated product children also with configurable update
     * 
     * @author ksenevich@aitoc.com
     */
    public function reindexEntities($processIds)
    {
        if (!is_array($processIds)) 
        {
            $processIds = array($processIds);
        }

        $childIds = $this->getRelationsByParent($processIds);
        if ($childIds) 
        {
            $processIds = array_unique(array_merge($processIds, $childIds));
        }

        return parent::reindexEntities($processIds);
    }
} } 