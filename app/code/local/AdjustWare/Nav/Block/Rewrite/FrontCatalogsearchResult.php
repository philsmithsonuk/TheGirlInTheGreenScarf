<?php
/**
 * Product:     Layered Navigation Pro - 07/06/12
 * Package:     AdjustWare_Nav_2.4.2_0.1.4_8_300402
 * Purchase ID: QmqwcKnSEMUDkX35fJBKkoOUk2rsivOit75vaVFw7E
 * Generated:   2012-06-12 14:40:26
 * File path:   app/code/local/AdjustWare/Nav/Block/Rewrite/FrontCatalogsearchResult.data.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ ihRWqyaysWDWOaEr('3de73cd6560d1ee09a184fde18168e2f'); ?><?php
class AdjustWare_Nav_Block_Rewrite_FrontCatalogsearchResult extends Mage_CatalogSearch_Block_Result
{
    /**
     * Retrieve Search result list HTML output, wrapped with <div>
     *
     * @return string
     */
    public function getProductListHtml()
    {
        $html = parent::getProductListHtml();
        $html = Mage::helper('adjnav')->wrapProducts($html);
        return $html;
    }
    
    /**
     * Set Search Result collection
     *
     * @return Mage_CatalogSearch_Block_Result
     */ 
    public function setListCollection()
    {
            $this->getListBlock()
               ->setCollection($this->_getProductCollection());
        return $this;
    }
    
    /**
     * Retrieve loaded category collection
     *
     * @return Mage_CatalogSearch_Model_Mysql4_Fulltext_Collection
     */
    protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) 
        {
            $this->_productCollection = Mage::getSingleton('catalogsearch/layer')->getProductCollection();
        }
        return $this->_productCollection;
    }
    
    protected function _toHtml()
    {
        $html = parent::_toHtml();
        if(!$this->getResultCount())
        {
            $html = Mage::helper('adjnav')->wrapProducts($html);
        }    
        return $html;
    }
} } 