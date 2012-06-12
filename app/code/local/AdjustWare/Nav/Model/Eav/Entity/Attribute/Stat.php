<?php
/**
 * Product:     Layered Navigation Pro - 07/06/12
 * Package:     AdjustWare_Nav_2.4.2_0.1.4_8_300402
 * Purchase ID: QmqwcKnSEMUDkX35fJBKkoOUk2rsivOit75vaVFw7E
 * Generated:   2012-06-12 14:40:26
 * File path:   app/code/local/AdjustWare/Nav/Model/Eav/Entity/Attribute/Stat.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ piNVCMkMsVaVdkWy('72b45591ddc370275c8ba4e024318b32'); ?><?php

/**
 * @author ksenevich
 */
class AdjustWare_Nav_Model_Eav_Entity_Attribute_Stat extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('adjnav/eav_entity_attribute_stat');
    }

    public function rangeAttributes($attributes)
    {
        $sortedAttributes = array();
        $featuredLimit    = Mage::helper('adjnav/featured')->getFeaturedAttrsLimit();
        $iteration        = 0;
        $featuredLimitDisabled = 0 == $featuredLimit;

        if (Mage::helper('adjnav/featured')->isRangeAttributes())
        {
            $collection = $this->getCollection()
                ->addFieldToFilter('attribute_id', array('in' => array_keys($attributes)))
                ->addOrder('uses', 'DESC');

            foreach ($collection->getItems() as $item)
            {
                $attribute          = $attributes[$item->getAttributeId()];
                $sortedAttributes[] = $attribute;
                unset($attributes[$item->getAttributeId()]);

                if (($featuredLimit && $featuredLimit <= $iteration) && !$featuredLimitDisabled)
                {
                    $attribute->setIsOther(true);
                }

                $iteration++;
            }
        }

        foreach ($attributes as $attribute)
        {
            $sortedAttributes[] = $attribute;

            if (($featuredLimit && $featuredLimit <= $iteration) && !$featuredLimitDisabled)
            {
                $attribute->setIsOther(true);
            }
            if($attribute->getItemsCount()>0)
                $iteration++;
        }

        return $sortedAttributes;
    }
} } 