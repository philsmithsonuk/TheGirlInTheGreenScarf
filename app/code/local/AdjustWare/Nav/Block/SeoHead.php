<?php
/**
 * Product:     Layered Navigation Pro - 16/08/12
 * Package:     AdjustWare_Nav_2.4.7_0.1.4_8_357526
 * Purchase ID: RtE0qeQE7RRjsdRvhv07l9cGxzFoZAJ502qOJCvubx
 * Generated:   2012-12-20 08:02:01
 * File path:   app/code/local/AdjustWare/Nav/Block/SeoHead.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ qofdcgEgsdrdtyOm('e732f9a7817067cb23d41eb8fb37f295'); ?><?php
class AdjustWare_Nav_Block_SeoHead extends Mage_Core_Block_Template
{
    
    public function _toHtml()
    {
        if(!Mage::getStoreConfig('design/adjnav/rel_prev_next'))
        {
            return;
        }
        
        $actionName = $this->getAction()->getFullActionName();
        if ($actionName == 'catalog_category_view') // Category Page
        {
            $category = Mage::registry('current_category');
            $prodCol = $category->getProductCollection()->addAttributeToFilter('status', 1)->addAttributeToFilter('visibility', array('in' => array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG, Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)));
            $tool = $this->getLayout()->createBlock('page/html_pager')->setLimit($this->getLayout()->createBlock('catalog/product_list_toolbar')->getLimit())->setCollection($prodCol);
            $linkPrev = false;
            $linkNext = false;
            if ($tool->getCollection()->getSelectCountSql()) {
                if ($tool->getLastPageNum() > 1) {
                    if (!$tool->isFirstPage()) {
                        $linkPrev = true;
                        if ($tool->getCurrentPage() == 2) {
                            $url = explode('?', $tool->getPreviousPageUrl());
                            $prevUrl = @$url[0];
                        }
                        else {
                            $prevUrl = $tool->getPreviousPageUrl();
                        }
                    }
                    if (!$tool->isLastPage()) {
                        $linkNext = true;
                        $nextUrl = $tool->getNextPageUrl();
                    }
                }
            }    
            
        }
        
        $html = '';
        
        if ($linkPrev)
        {
            $html .= '<link rel="prev" href="'.$prevUrl.'" />';
        }
        
        if ($linkNext)
        {
            $html .= '<link rel="next" href="'.$nextUrl.'" />';
        }
        
        return $html;
        
    }
    
} } 