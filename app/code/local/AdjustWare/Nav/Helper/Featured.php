<?php
/**
 * Product:     Layered Navigation Pro - 07/06/12
 * Package:     AdjustWare_Nav_2.4.2_0.1.4_8_300402
 * Purchase ID: QmqwcKnSEMUDkX35fJBKkoOUk2rsivOit75vaVFw7E
 * Generated:   2012-06-12 14:40:26
 * File path:   app/code/local/AdjustWare/Nav/Helper/Featured.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ ihRWqyaysWDWOaEr('2f88d6ba9948274bf94d12b52c77254f'); ?><?php

/**
 * 
 * @author ksenevich
 */
class AdjustWare_Nav_Helper_Featured extends Mage_Core_Helper_Abstract
{
    public function isAutoRange()
    {
        return (Mage::getStoreConfig('design/adjnav_featured/collect_period') > 0);
    }

    public function collectPeriod()
    {
        return (int)Mage::getStoreConfig('design/adjnav_featured/collect_period');
    }

    public function getFeaturedAttrsLimit()
    {
        return (int)Mage::getStoreConfig('design/adjnav_featured/featured_attrs_limit');
    }

    public function getFeaturedValuesLimit()
    {
        return (int)Mage::getStoreConfig('design/adjnav_featured/featured_vals_limit');
    }

    public function isRangeAttributes()
    {
        return ($this->isAutoRange() && Mage::getStoreConfig('design/adjnav_featured/use_ranges_attr'));
    }

    public function isRangeValues()
    {
        return ($this->isAutoRange() && Mage::getStoreConfig('design/adjnav_featured/use_ranges_val'));
    }
} } 