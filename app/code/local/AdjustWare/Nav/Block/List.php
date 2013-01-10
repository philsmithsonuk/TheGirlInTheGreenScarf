<?php
/**
 * Product:     Layered Navigation Pro - 16/08/12
 * Package:     AdjustWare_Nav_2.4.7_0.1.4_8_357526
 * Purchase ID: RtE0qeQE7RRjsdRvhv07l9cGxzFoZAJ502qOJCvubx
 * Generated:   2012-12-20 08:02:01
 * File path:   app/code/local/AdjustWare/Nav/Block/List.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ owCahDBDsaMarBDe('48b5757d6dc8bbebb633d987aa77c419'); ?><?php
// wrapper for product list
class AdjustWare_Nav_Block_List extends Mage_Core_Block_Template
{
    protected $_productCollection;
    protected $_module='catalog';

    /**
     * @return Mage_Catalog_Block_Product_List
     */
    public function getListBlock()
    {
        return $this->getChild('product_list');
    }

    public function setListOrders() {
		$category = Mage::getSingleton('catalog/layer')
            ->getCurrentCategory();
        $availableOrders = $category->getAvailableSortByOptions();
        
        if ('catalogsearch' != $this->_module) {
        	$sortBy = $this->getRequest()->getParam('sort', $category->getDefaultSortBy());
        	$this->getListBlock()
	            ->setAvailableOrders($availableOrders)
//	            ->setDefaultDirection('desc')
	            ->setSortBy($sortBy);
        } else {
	        unset($availableOrders['position']);
	        $availableOrders = array_merge(array(
	            'relevance' => $this->__('Relevance')
	        ), $availableOrders);
	
	        $this->getListBlock()
	            ->setAvailableOrders($availableOrders)
//	            ->setDefaultDirection('desc')
	            ->setSortBy('relevance');
        }

        return $this;
    }

    /**
     * Set available view mode
     *
     * @return AdjustWare_Nav_Block_List
     */
    public function setListModes() {
        $this->getListBlock()
            ->setModes(array(
                'grid' => $this->__('Grid'),
                'list' => $this->__('List'))
            );
        return $this;
    }
    
    public function setIsSearchMode() 
    {
        $this->_module = 'catalogsearch';
        return $this;
    }

    
    /**
     * Set All products collection
     *
     * @return AdjustWare_Nav_Block_List
     */
    public function setListCollection() {
        $this->getListBlock()
           ->setCollection($this->_getProductCollection());
       return $this;
    }

    protected function _toHtml()
    {
        $this->setListOrders();
        $this->setListModes();
        $this->setListCollection();
        
        $html = $this->getChildHtml('product_list');
        $html = Mage::helper('adjnav')->wrapProducts($html);
        
        return $html;
    }

    /**
     * Retrieve loaded category collection
     *
     * @return Mage_CatalogSearch_Model_Mysql4_Fulltext_Collection
     */
    protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {
            $this->_productCollection = Mage::getSingleton($this->_module . '/layer')
                ->getProductCollection();
        }

        if($this->_module != 'catalogsearch')
        {
            Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($this->_productCollection);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($this->_productCollection);
            if (!is_null($this->_productCollection))
            {
                $visibleIds = Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds();
                $this->_productCollection->addAttributeToFilter('visibility',$visibleIds);
            }
        }
        else
        {
            Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($this->_productCollection);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($this->_productCollection);
            if (!is_null($this->_productCollection))
            {
                $visibleIds = Mage::getSingleton('catalog/product_visibility')->getVisibleInSearchIds();
                $this->_productCollection->addAttributeToFilter('visibility',$visibleIds);
            }
        }

        
        return $this->_productCollection;
    }

} } 