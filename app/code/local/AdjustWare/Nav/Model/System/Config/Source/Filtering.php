<?php
/**
 * Product:     Layered Navigation Pro - 16/08/12
 * Package:     AdjustWare_Nav_2.4.7_0.1.4_8_357526
 * Purchase ID: RtE0qeQE7RRjsdRvhv07l9cGxzFoZAJ502qOJCvubx
 * Generated:   2012-12-20 08:02:01
 * File path:   app/code/local/AdjustWare/Nav/Model/System/Config/Source/Filtering.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ owCahDBDsaMarBDe('989ed529993e3716f63f28f0e8b1923b'); ?><?php
class AdjustWare_Nav_Model_System_Config_Source_Filtering extends Varien_Object
{
    public function toOptionArray()
    {
        $options = array();   
        
        
        $options[] = array(
                'value'=> 'OR',
                'label' => Mage::helper('adjnav')->__('OR'),
        );

	$options[] = array(
                'value'=> 'AND',
                'label' => Mage::helper('adjnav')->__('AND'),
        );

        return $options;
    }
} } 