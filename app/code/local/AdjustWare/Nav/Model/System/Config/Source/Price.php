<?php
/**
 * Product:     Layered Navigation Pro - 16/08/12
 * Package:     AdjustWare_Nav_2.4.7_0.1.4_8_357526
 * Purchase ID: RtE0qeQE7RRjsdRvhv07l9cGxzFoZAJ502qOJCvubx
 * Generated:   2012-12-20 08:02:01
 * File path:   app/code/local/AdjustWare/Nav/Model/System/Config/Source/Price.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ qofdcgEgsdrdtyOm('811ab5b7a4ca8794a8ada9532576bdb6'); ?><?php
class AdjustWare_Nav_Model_System_Config_Source_Price extends Varien_Object
{
    public function toOptionArray()
    {
        $options = array();
        
        $options[] = array(
                'value'=> 'default',
                'label' => Mage::helper('adjnav')->__('Default')
        );
        $options[] = array(
                'value'=> 'slider',
                'label' => Mage::helper('adjnav')->__('Slider')
        );
        $options[] = array(
                'value'=> 'input',
                'label' => Mage::helper('adjnav')->__('Input')
        );
        
        return $options;
    }
} } 