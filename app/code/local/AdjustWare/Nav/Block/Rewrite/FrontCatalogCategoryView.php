<?php
/**
 * Product:     Layered Navigation Pro - 16/08/12
 * Package:     AdjustWare_Nav_2.4.7_0.1.4_8_357526
 * Purchase ID: RtE0qeQE7RRjsdRvhv07l9cGxzFoZAJ502qOJCvubx
 * Generated:   2012-12-20 08:02:01
 * File path:   app/code/local/AdjustWare/Nav/Block/Rewrite/FrontCatalogCategoryView.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ hpAtIBPBstktlmVM('a16de3e97af56196315e65cbaf148024'); ?><?php
class AdjustWare_Nav_Block_Rewrite_FrontCatalogCategoryView extends Mage_Catalog_Block_Category_View
{
    public function getProductListHtml()
    {
        $html = parent::getProductListHtml();
        if (parent::getCurrentCategory()->getIsAnchor()){
            $html = Mage::helper('adjnav')->wrapProducts($html);
        }
        return $html;
    }   

    public function getCmsBlockHtml()
    {
        if (parent::isContentMode())
        {
            return Mage::helper('adjnav')->wrapProducts(parent::getCmsBlockHtml());
        } 
        return parent::getCmsBlockHtml();
    }
    
    /**
     * Check if category display mode is "Static Block Only"
     * For anchor category with applied filter Static Block Only mode not allowed
     *
     * @return bool
     */
    public function isContentMode()
    {
        $res = parent::isContentMode();
        $category = $this->getCurrentCategory();
        $filters = Mage::helper('adjnav')->getParams();
        if ($res && $category->getIsAnchor() && sizeof($filters)>0) {
            $res = false;
        }
        return $res;
    }

     /**
     * Retrieve current category model object
     *
     * @return Mage_Catalog_Model_Category
     */
    public function getCurrentCategory()
    {
        $categoryId =(int)$this->getRequest()->getQuery('cat');
        if(!$categoryId)
        {
            return parent::getCurrentCategory();
        }
        else
        {
            return Mage::getModel('catalog/category')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($categoryId);
        }
    }
    
} } 