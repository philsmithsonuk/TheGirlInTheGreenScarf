<?php
/**
 * Product:     Layered Navigation Pro - 07/06/12
 * Package:     AdjustWare_Nav_2.4.2_0.1.4_8_300402
 * Purchase ID: QmqwcKnSEMUDkX35fJBKkoOUk2rsivOit75vaVFw7E
 * Generated:   2012-06-12 14:40:26
 * File path:   app/code/local/AdjustWare/Nav/Model/Catalog/Layer/Filter/Category.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ picrwZeZsrgrkeZj('7a8822a63cd7feb084b1a869c79a47fc'); ?><?php

class AdjustWare_Nav_Model_Catalog_Layer_Filter_Category extends Mage_Catalog_Model_Layer_Filter_Category
{
    protected $cat = null;
    
    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        // very small optimization
        $catId = (int) Mage::helper('adjnav')->getParam($this->getRequestVar());
        if ($catId){
            $request->setParam($this->getRequestVar(), $catId);
            parent::apply($request, $filterBlock);
        }
        return $this;
    }

    public function getRootCategory()
    {
        if (is_null($this->cat)){
            $this->cat = Mage::getModel('catalog/category')
                ->load($this->getLayer()->getCurrentStore()->getRootCategoryId());
        }
        return $this->cat;
    }
    
    protected function _getItemsData()
    {
        $key = $this->getLayer()->getStateKey().'_SUBCATEGORIES';
        $key .= Mage::helper('adjnav')->getCacheKey('cat');
        $pageKey  = Mage::getBlockSingleton('page/html_pager')->getPageVarName();
        $queryStr =  Mage::helper('adjnav')->getParams(true, $pageKey);
        $data = $this->getLayer()->getAggregator()->getCacheData($key);

        if ($data === null) {
            $category   = null;
            
            $showTopCategories = Mage::getStoreConfig('design/adjnav/top_cats');
            //$showTopCategories = true;
            if ($showTopCategories)
            {
                $category = $this->getRootCategory();
            }
            else
            {
                $category = $this->getCategory();
            }                     
            
            
            /** @var $categoty Mage_Catalog_Model_Categeory */
            $categories = $category->getChildrenCategories();
#d($categories->getSelect()->__tostring());
            $data = array();
            $level = 0;
            $parent = null; 
            
            if ($category->getLevel() > 1)
            { // current category is not root
                $parent = $category->getParentCategory();
                
                ++$level;
                if ($parent->getLevel()>1){
                    $data[] = array(
                        'label'       => $parent->getName(),
                        'value'       => $parent->getUrl(),
                        'level'       => $level,
                        'category_id' => $parent->getId(),
                        'uri'   => $queryStr,
                    );
                }
                //always include current category
                ++$level;
                $data[] = array(
                    'label'       => $category->getName(),
                    'value'       => '',
                    'level'       => $level,
                    'is_current'  => true,
                    'category_id' => $category->getId(),
                    'uri'   => $queryStr,
                );
            }
            
            if (!$showTopCategories)
            {
                $this->getLayer()->getProductCollection()
                    ->addCountToCategories($categories);
            }
            
            ++$level;
            foreach ($categories as $cat) {
                if ($cat->getIsActive() && ($showTopCategories || $cat->getProductCount())) {
                    $data[] = array(
                        'label'       => $cat->getName(),
                        'value'       => $cat->getId(), 
                        'count'       => $cat->getProductCount(),
                        'level'       => $level,
                        'category_id' => $cat->getId(),
                        'uri'         => $cat->getUrl(), 
                    );
                }
            }           
            
            
            if (Mage::getStoreConfig('design/adjnav/reset_filters'))
            {
                $queryStr = '';
            }
            
            for ($i=0, $n=sizeof($data); $i<$n; ++$i) {
                $url = $data[$i]['uri'];
                $pos = strpos($url, '?');
                if ($pos)
                    $url = substr($url, 0, $pos);
                $data[$i]['uri'] = $url . $queryStr;
            }
#d($this->getLayer()->getProductCollection()->getSelect()->__tostring());            
            $tags = $this->getLayer()->getStateTags();
            $this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);
        }     
        
        
        
       // print_r($data);
        return $data;
    }    
    
    protected function _initItems()
    {
        $data = $this->_getItemsData();
        $items=array();
        foreach ($data as $itemData) {
            $obj = new Varien_Object();
            $obj->setData($itemData);
            $obj->setUrl($itemData['value']);
            
            $items[] = $obj;
        }
        $this->_items = $items;
        return $this;
    }
} } 