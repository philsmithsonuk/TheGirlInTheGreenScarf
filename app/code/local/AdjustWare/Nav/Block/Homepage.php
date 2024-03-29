<?php
/**
 * Product:     Layered Navigation Pro - 16/08/12
 * Package:     AdjustWare_Nav_2.4.7_0.1.4_8_357526
 * Purchase ID: RtE0qeQE7RRjsdRvhv07l9cGxzFoZAJ502qOJCvubx
 * Generated:   2012-12-20 08:02:01
 * File path:   app/code/local/AdjustWare/Nav/Block/Homepage.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ qofdcgEgsdrdtyOm('c63e6c94c1b2516841f4aaffa5d6f00b'); ?><?php
// wrapper for product list on home page
class AdjustWare_Nav_Block_Homepage extends AdjustWare_Nav_Block_List
{
    protected function _prepareLayout()
    {
        $staticBlock = $this->getLayout()
            ->createBlock('cms/block', 'adj_nav_homepage')
            ->setBlockId('adj_nav_homepage');
        if ($staticBlock){
            $this->insert($staticBlock);
        }
        
        $productsBlock = $this->getLayout()
            ->createBlock('catalog/product_list', 'product_list')
            //->setColumnsCount(4)  
            ->setTemplate('catalog/product/list.phtml');
        //@todo  check gift registry compatibility     
        if ($productsBlock)
            $this->insert($productsBlock);

        return parent::_prepareLayout();
    } 
    
    protected function _toHtml()
    {
        $hlp = Mage::helper('adjnav');
        
        $html = $this->getChildHtml('adj_nav_homepage');
        if ($html && !$hlp->getParams()){
            $html = $hlp->wrapHomepage($html);
        }
        else{
            $html = parent::_toHtml();
        }
        
        return $html;
    }    
    
} } 