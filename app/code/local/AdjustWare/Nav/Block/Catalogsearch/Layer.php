<?php
/**
 * Product:     Layered Navigation Pro - 16/08/12
 * Package:     AdjustWare_Nav_2.4.7_0.1.4_8_357526
 * Purchase ID: RtE0qeQE7RRjsdRvhv07l9cGxzFoZAJ502qOJCvubx
 * Generated:   2012-12-20 08:02:01
 * File path:   app/code/local/AdjustWare/Nav/Block/Catalogsearch/Layer.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ hpAtIBPBstktlmVM('2ed961bf18391dc037c917a3ddbf89f8'); ?><?php
class AdjustWare_Nav_Block_Catalogsearch_Layer extends AdjustWare_Nav_Block_Catalog_Layer_View
{

    public function getLayer()
    {
        return Mage::getSingleton('catalogsearch/layer');
    }

    /**
     * Check availability display layer block
     *
     * @return bool
     */
    public function canShowBlock()
    {
        $availableResCount = (int) Mage::app()->getStore()
            ->getConfig(Mage_CatalogSearch_Model_Layer::XML_PATH_DISPLAY_LAYER_COUNT );

        if (!$availableResCount
            || ($availableResCount>=$this->getLayer()->getProductCollection()->getSize())) {
            return parent::canShowBlock();
        }
        return false;
    }
    
    
    protected function createCategoriesBlock(){
        $categoryBlock = $this->getLayout()
            ->createBlock('adjnav/catalog_layer_filter_categorysearch')
            ->setLayer($this->getLayer())
            ->init();
        $this->setChild('category_filter', $categoryBlock);
    }

    public function getAttributesCount()
    {
        $count = 0;
        foreach ($this->_filterBlocks as $filter)
        {
            if ($filter instanceof AdjustWare_Nav_Block_Catalog_Layer_Filter_Attribute && $filter->getItemsCount())
            {
                $count++;
            }
        }

        return $count;
    }

    public function isShowMoreAttributesButton()
    {
        $featuredLimit = Mage::helper('adjnav/featured')->getFeaturedAttrsLimit();

        return ($featuredLimit && $featuredLimit < $this->getAttributesCount());
    }
} } 