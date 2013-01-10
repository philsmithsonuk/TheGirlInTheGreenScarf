<?php
/**
 * Product:     Layered Navigation Pro - 16/08/12
 * Package:     AdjustWare_Nav_2.4.7_0.1.4_8_357526
 * Purchase ID: RtE0qeQE7RRjsdRvhv07l9cGxzFoZAJ502qOJCvubx
 * Generated:   2012-12-20 08:02:01
 * File path:   app/code/local/AdjustWare/Nav/Block/Catalog/Layer/Filter/Categorysearch.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ qofdcgEgsdrdtyOm('2ecfeb20031ae6ab44f80d233ccbd5af'); ?><?php
class AdjustWare_Nav_Block_Catalog_Layer_Filter_Categorysearch extends AdjustWare_Nav_Block_Catalog_Layer_Filter_Category
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('adjnav/filter_category_search.phtml');
        $this->_filterModelName = 'adjnav/catalog_layer_filter_categorysearch'; 
    }
    
} } 