<?php
/**
 * Product:     Layered Navigation Pro - 07/06/12
 * Package:     AdjustWare_Nav_2.4.2_0.1.4_8_300402
 * Purchase ID: QmqwcKnSEMUDkX35fJBKkoOUk2rsivOit75vaVFw7E
 * Generated:   2012-06-12 14:40:26
 * File path:   app/code/local/AdjustWare/Nav/Block/Rewrite/FrontCatalogProductListToolbar.data.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ qofdcgEgsdrdtyOm('8ccf3a80f8fca47564282a25578a3b33'); ?><?php
class AdjustWare_Nav_Block_Rewrite_FrontCatalogProductListToolbar extends Mage_Catalog_Block_Product_List_Toolbar
{
    
    protected function _beforeToHtml()
    {
    	$this->getRequest()->setParam('cat', '');
    	
        if(Mage::helper('adjnav')->isPageAutoload())
        {
            $this->setTemplate('adjnav/product_list_toolbar.phtml');
        }
        return parent::_beforeToHtml();
    }
    
    public function getLimit()
    {
        if (Mage::helper('adjnav')->isPageAutoload())
        {
            $mode = $this->getCurrentMode();
            $limit = Mage::getStoreConfig('design/adjnav_endless_page/products_on_' . $mode . '_page');
            if ($limit)
            {
                $this->setData('_current_limit', $limit);
                return $limit;
            }
        }
        return parent::getLimit();
    }   
}
 } ?>