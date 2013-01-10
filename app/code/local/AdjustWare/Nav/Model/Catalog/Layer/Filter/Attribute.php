<?php
/**
 * Product:     Layered Navigation Pro - 16/08/12
 * Package:     AdjustWare_Nav_2.4.7_0.1.4_8_357526
 * Purchase ID: RtE0qeQE7RRjsdRvhv07l9cGxzFoZAJ502qOJCvubx
 * Generated:   2012-12-20 08:02:01
 * File path:   app/code/local/AdjustWare/Nav/Model/Catalog/Layer/Filter/Attribute.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ qoIkiajaskBkEjaD('de30395b9b1d4b6f27155e733f9e638c'); ?><?php
class AdjustWare_Nav_Model_Catalog_Layer_Filter_Attribute extends Mage_Catalog_Model_Layer_Filter_Attribute
{
    private static $_filterAttributes = array();
    private static $_filterProducts   = array();

    public function __construct()
    {
        parent::__construct();
    }

    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        $filter = Mage::helper('adjnav')->getParam($this->_requestVar);
        $filter = explode('-', $filter);
        
        $ids = array();    
        foreach ($filter as $id){
            $id = intVal($id);
            if ($id)
                $ids[] = $id;    
        } 
        if ($ids){
            $this->applyMultipleValuesFilter($ids);     
        }
        
        $this->setActiveState($ids);

        // Increment attribute usage statistics data
        if (count($ids))
        {
            Mage::getModel('adjnav/eav_entity_attribute_option_stat')->addStat($ids);
        }

        return $this;
    }

    // copied from catalogindex
    protected function applyMultipleValuesFilter($ids)
    {
        $collection = $this->getLayer()->getProductCollection();
        $attribute  = $this->getAttributeModel();
        $table = Mage::getSingleton('core/resource')->getTableName('catalogindex/eav'); //check for prefix
        $helper = Mage::helper('adjnav');
        
        $alias = 'attr_index_'.$attribute->getId();
        $collection->getSelect()
            ->join(array($alias => $table), $alias.'.entity_id=e.entity_id', array())
         	->where($alias.'.store_id = ?', Mage::app()->getStore()->getId())
            ->where($alias.'.attribute_id = ?', $attribute->getId())
    		->where($alias.'.value IN (?)', $ids);

        if (is_array($ids) && ($size = count($ids)))
        {
            $adapter  = $collection->getConnection();
            $subQuery = new Varien_Db_Select($adapter);

            /**
             * @author ksenevich@aitoc.com
             */
            if (Mage::helper('adjnav/version')->hasConfigurableFix())
            {
                $subQuery
                    ->from(array('e' => Mage::getModel('catalog/product')->getResource()->getTable('catalog/product')), 'entity_id')
                    ->join(array('a' => Mage::getModel('catalog/product')->getResource()->getTable('catalog/product_index_eav')), 'a.entity_id = e.entity_id', array());

                $SBBStatus = $helper->getShopByBrandsStatus();
                $forbidConfigurables = $SBBStatus->getIsInstalled()
                        && $SBBStatus->getIsEnabled()
                        && Mage::helper('aitmanufacturers')->canUseLayeredNavigation(Mage::registry('shopby_attribute'), true);
                
                if (!$forbidConfigurables)
                {
                    $subQuery->where('e.type_id != ?', Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE);
                }
            }
            else 
            {
                $subQuery->from(array('a' => Mage::getResourceModel('catalogindex/attribute')->getMainTable()), 'entity_id');                
            }

            $subQuery
                ->where('a.store_id = ?', Mage::app()->getStore()->getId())
                ->where('a.attribute_id = ?', $attribute->getId())
                ->where('a.value IN (?)', $ids)
                ->group(array('a.entity_id', 'a.attribute_id', 'a.store_id'));

            $res = $adapter->fetchCol($subQuery);

            /**
             * @author ksenevich@aitoc.com
             */
            self::_addFilterValues($attribute->getId(), $res, $ids);
        }
        
        if (count($ids)>1)
        {
            $collection->getSelect()->distinct(true); 
        }

        return $this;
    }   
    
    /**
     * Get data array for building attribute filter items
     *
     * @return array
     */
    protected function _getItemsData()
    {        
        $attribute = $this->getAttributeModel();
        $this->_requestVar = $attribute->getAttributeCode();

        $key = $this->getLayer()->getStateKey();
        $key .= Mage::helper('adjnav')->getCacheKey($this->_requestVar);

        $data = $this->getLayer()->getAggregator()->getCacheData($key);
        
        

        if ($data === null) {
            $data = array();

            $options = $attribute->getFrontend()->getSelectOptions();                        

            $optionsCount = Mage::getSingleton('catalogindex/attribute')->getCount(
                $attribute,
                $this->_getBaseCollectionSql()
            );

            foreach ($options as $option) {
                if (is_array($option['value'])) {
                    continue;
                }
                if (Mage::helper('core/string')->strlen($option['value'])) {
                    // Check filter type
                    if ($attribute->getIsFilterable() == self::OPTIONS_ONLY_WITH_RESULTS) {
                        if (!empty($optionsCount[$option['value']])) {
                            $data[] = array(
                                'label' => $option['label'],
                                'value' => $option['value'],
                                'count' => $optionsCount[$option['value']],
                            );
                        }
                    }
                    else {
                        $data[] = array(
                            'label' => $option['label'],
                            'value' => $option['value'],
                            'count' => isset($optionsCount[$option['value']]) ? $optionsCount[$option['value']] : 0,
                        );
                    }
                }
            }

            $currentIds = Mage::helper('adjnav')->getParam($attribute->getAttributeCode());
            $tags = array(
                Mage_Eav_Model_Entity_Attribute::CACHE_TAG . ':' . $currentIds,
            );

            $tags = $this->getLayer()->getStateTags($tags);
            $this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);
        }
        return $data;
    }
    
    protected function _getBaseCollectionSql()
    {        
        $alias = 'attr_index_' . $this->getAttributeModel()->getId();         
        // Varien_Db_Select
        $baseSelect = clone parent::_getBaseCollectionSql();
        
        // 1) remove from conditions
        $oldWhere = $baseSelect->getPart(Varien_Db_Select::WHERE);
        $newWhere = array();

        foreach ($oldWhere as $cond){
           if (!strpos($cond, $alias))
               $newWhere[] = $cond;
        }
  
        if ($newWhere && substr($newWhere[0], 0, 3) == 'AND')
           $newWhere[0] = substr($newWhere[0],3);        
        
        $baseSelect->setPart(Varien_Db_Select::WHERE, $newWhere);
        
        // 2) remove from joins
        $oldFrom = $baseSelect->getPart(Varien_Db_Select::FROM);
        $newFrom = $oldFrom;

        if (isset($newFrom[$alias]))
        {
            unset($newFrom[$alias]);
        }
        //it assumes we have at least one table 
        $baseSelect->setPart(Varien_Db_Select::FROM, $newFrom);        

        return $baseSelect;
    }
    
    protected function _getResource()
    {
        if (is_null($this->_resource)) {
            $this->_resource = Mage::getResourceModel('catalog/layer_filter_attribute');
        }
        return $this->_resource;
    }

    protected static function _addFilterValues($attributeId, array $productIds, array $attributeValues)
    {
        self::$_filterProducts[$attributeId]   = $productIds;
        self::$_filterAttributes[$attributeId] = $attributeValues;
    }

    public static function getFilterAttributes()
    {
        return self::$_filterAttributes;
    }

    public static function getFilterProducts()
    {
        return self::$_filterProducts;
    }

    public static function cleanFilterAttributes()
    {
        self::$_filterAttributes = array();
        self::$_filterProducts   = array();
    }
} } 