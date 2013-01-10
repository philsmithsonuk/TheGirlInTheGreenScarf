<?php
/**
 * Product:     Layered Navigation Pro - 07/06/12
 * Package:     AdjustWare_Nav_2.4.2_0.1.4_8_300402
 * Purchase ID: QmqwcKnSEMUDkX35fJBKkoOUk2rsivOit75vaVFw7E
 * Generated:   2012-06-12 14:40:26
 * File path:   app/code/local/AdjustWare/Nav/controllers/AjaxController.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ wqTPpkZksPjPWZka('b2a95e2ec290706d1cb55cff52dc0cdf'); ?><?php
class AdjustWare_Nav_AjaxController extends Mage_Core_Controller_Front_Action
{
    public function categoryAction()
    {
        // init category

         $categoryId =(int)$this->getRequest()->getQuery('cat');
         
         if(!$categoryId && Mage::helper('adjnav')->isCategoryCleared(true) ) {
             //if category was cleared trought X or 'Clear All' link - we are trying to get default category from link
             $categoryId = (int) $this->getRequest()->getParam('id', false);
         }
         
         // 1. Get category ID from request.
         // 2. From session.
         // ONLY in that order. Please don`t change.
         if (!$categoryId)
         {
             $categoryId = (int) $this->getRequest()->getParam('id', false);
         }

         if (!$categoryId)
         {
             $categoryId = Mage::getSingleton('catalog/session')->getAdjnavLastCategoryId();
         }
         //
         
        if (!$categoryId) {
            $this->_forward('noRoute'); 
            return;
        }

        $category = Mage::getModel('catalog/category')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($categoryId);
        Mage::register('current_category', $category);
        try
		{
			$this->loadLayout();
		}
		catch (Varien_Exception $e)
		{
			if ((NULL !== strpos($e->getMessage(), 'addColumnCountLayoutDepend')) && version_compare(Mage::getVersion(), '1.3.2', '<'))
			{
				// We shouldn`t do anything if method Mage_Catalog_Block_Product_Abstract::addColumnCountLayoutDepend
				// is called. It doesn`t exist in magento version lower than 1.3.2.
			}
			else
			{
				throw $e;				
			}
		}
        
        $update = $this->getLayout()->getUpdate();        
                
        if (Aitoc_Aitsys_Abstract_Service::get()->isMagentoVersion('>=1.4.2'))
        {
            $design = Mage::getSingleton('catalog/design');
            $settings = $design->getDesignSettings($category);
            // apply custom design
            if ($settings->getCustomDesign()) {
                $design->applyCustomDesign($settings->getCustomDesign());
            }
            // apply custom layout update once layout is loaded
            if ($layoutUpdates = $settings->getLayoutUpdates()) {
                if (is_array($layoutUpdates)) {
                    foreach($layoutUpdates as $layoutUpdate) {
                        $update->addUpdate($layoutUpdate);
                    }
                }
            }
            // apply custom layout (page) template once the blocks are generated
            if ($settings->getPageLayout()) {
                $this->getLayout()->helper('page/layout')->applyHandle($settings->getPageLayout());
            }
        } elseif (Aitoc_Aitsys_Abstract_Service::get()->isMagentoVersion('>=1.4')) {
            // apply custom design
            Mage::getModel('catalog/design')->applyDesign($category, Mage_Catalog_Model_Design::APPLY_FOR_CATEGORY);
            // apply custom layout update once layout is loaded
            $update->addUpdate($category->getCustomLayoutUpdate());
            // apply custom layout (page) template once the blocks are generated
            if ($category->getPageLayout()) {
                $this->getLayout()->helper('page/layout')
                    ->applyHandle($category->getPageLayout());
            } 
        }    
        
       
        if(Mage::helper('adjnav')->isModuleEnabled('Aitoc_Aitmanufacturers'))
        {
            $canUseLNP = false;
            if(version_compare(Mage::getVersion(), '1.4.0.0', '>='))
                $canUseLNP = Mage::helper('aitmanufacturers')->canUseLayeredNavigation(Mage::registry('shopby_attribute'), true);
            else
                $canUseLNP = Mage::helper('aitmanufacturers')->canUseLayeredNavigation();
                
            if($canUseLNP)
            {
                $session = Mage::getSingleton('core/session');
                $manufactureId = $session->getAitocManufacturersCurrentManufacturerId();
                $manufacture = Mage::getModel('aitmanufacturers/aitmanufacturers')->loadByManufacturer($manufactureId);
                $layout = (string) $manufacture->getRootTemplate();
                $block = $this->getLayout()->getBlock('product_list');
                $columnsCount = $block->getColumnCountLayoutDepend($layout);
                $this->getLayout()->getBlock('product_list')->setColumnCount($columnsCount);
            }
        }
        
        $response = array();
        $response['category_name'] = $category->getName();
        $response['products'] = $this->getLayout()->getBlock('products')->toHtml(); 
        $response['layer']    = $this->getLayout()->getBlock('layer')->toHtml(); 
        
        if ((string)Mage::getConfig()->getModuleConfig('Aitoc_Aitshopassist')->active == 'true') 
        {
            $assistant = $this->getLayout()->createBlock('aitshopassist/Assistant')->setBlockId('aitshopassist_assistant')->toHtml();
            $response['category_name'] = $assistant.$response['category_name'];
        }
        
        $response = Zend_Json::encode($response);
        $this->getResponse()->setBody($response);
    }
    
    public function searchAction()
    {
        $this->loadLayout();
        
        $response = array();
        $response['products'] = $this->getLayout()->getBlock('products')->setIsSearchMode()->toHtml();  
        $response['layer']    = $this->getLayout()->getBlock('layer')->toHtml();
        
        $response = Zend_Json::encode($response);
        $this->getResponse()->setBody($response);
        
    }
    
} } 