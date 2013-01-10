<?php
/**
 * Product:     Layered Navigation Pro - 07/06/12
 * Package:     AdjustWare_Nav_2.4.2_0.1.4_8_300402
 * Purchase ID: QmqwcKnSEMUDkX35fJBKkoOUk2rsivOit75vaVFw7E
 * Generated:   2012-06-12 14:40:26
 * File path:   app/code/local/AdjustWare/Nav/Block/Catalog/Layer/Filter/Categorysearch.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ owQOhmrmsOZOVrPk('2ecfeb20031ae6ab44f80d233ccbd5af'); ?><?php
class AdjustWare_Nav_Block_Catalog_Layer_Filter_Categorysearch extends AdjustWare_Nav_Block_Catalog_Layer_Filter_Category
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('adjnav/filter_category_search.phtml');
        $this->_filterModelName = 'adjnav/catalog_layer_filter_categorysearch'; 
    }
    
} } 