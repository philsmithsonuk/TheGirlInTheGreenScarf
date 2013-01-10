<?php
/**
 * Product:     Layered Navigation Pro - 16/08/12
 * Package:     AdjustWare_Nav_2.4.7_0.1.4_8_357526
 * Purchase ID: RtE0qeQE7RRjsdRvhv07l9cGxzFoZAJ502qOJCvubx
 * Generated:   2012-12-20 08:02:01
 * File path:   app/code/local/AdjustWare/Nav/Model/System/Config/Source/Category.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ hpAtIBPBstktlmVM('f23a5841ce49da911507c4a02548583c'); ?><?php
class AdjustWare_Nav_Model_System_Config_Source_Category extends Varien_Object
{
    public function toOptionArray()
    {
        $options = array();
        
        $options[] = array(
                'value'=> 'breadcrumbs',
                'label' => Mage::helper('adjnav')->__('Breadcrumbs')
        );
        $options[] = array(
                'value'=> 'none',
                'label' => Mage::helper('adjnav')->__('None')
        );
        
        return $options;
    }
} } 