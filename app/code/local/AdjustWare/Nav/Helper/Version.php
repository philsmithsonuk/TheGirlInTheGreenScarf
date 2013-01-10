<?php
/**
 * Product:     Layered Navigation Pro - 07/06/12
 * Package:     AdjustWare_Nav_2.4.2_0.1.4_8_300402
 * Purchase ID: QmqwcKnSEMUDkX35fJBKkoOUk2rsivOit75vaVFw7E
 * Generated:   2012-06-12 14:40:26
 * File path:   app/code/local/AdjustWare/Nav/Helper/Version.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ qoIkiajaskBkEjaD('97b148935efe848629271020b5de4f56'); ?><?php

/**
 * 
 * @author ksenevich@aitoc.com
 */
class AdjustWare_Nav_Helper_Version extends Mage_Core_Helper_Abstract
{
    public function getProductIdChildColumn()
    {
        if (version_compare(Mage::getVersion(), '1.4') >= 0)
        {
            return 'child_id';
        }

        return 'product_id';
    }

    public function getProductRelationTable()
    {
        if (version_compare(Mage::getVersion(), '1.4') >= 0)
        {
            return 'catalog/product_relation';
        }

        return 'catalog/product_super_link';
    }
    
    public function getBaseIndexTable()
    {
        if (version_compare(Mage::getVersion(), '1.4') >= 0)
        {
            return 'catalog_product_index_eav';
        }

        return 'catalogindex_eav';
    }

    /** Configurable fix works with 1.4+ versions only 
     * 
     * @return boolean
     */
    public function hasConfigurableFix()
    {
        return (boolean)(version_compare(Mage::getVersion(), '1.4') >= 0);
    }

    /**
     * 
     * @return boolean
     */
    public function isNewReindexAllMethod()
    {
        return (boolean)(version_compare(Mage::getVersion(), '1.4.1') >= 0);
    }
    
    public function getVersionSkinJs()
    {
        $version = '13';
        if (version_compare(Mage::getVersion(), '1.4.0.0', '>='))
        {
            $version = '14';
        }

        return 'js/adjnav-'.$version.'.js';
    }
} } 