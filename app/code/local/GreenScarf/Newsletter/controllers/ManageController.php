<?php 
require_once('Mage/Newsletter/controllers/ManageController.php');
class GreenScarf_Newsletter_ManageController extends Mage_Newsletter_ManageController
{ 
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');

        if ($block = $this->getLayout()->getBlock('customer_newsletter')) {
            $block->setRefererUrl($this->_getRefererUrl());
        }
		if($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')){
			$breadcrumbs->addCrumb('home', 			 array('label'=>Mage::helper('Customer')->__('Home'),'title'=>Mage::helper('Customer')->__('Home'), 		'link'=>Mage::getBaseUrl())); 
			$breadcrumbs->addCrumb('myaccount', 	 array('label'=>Mage::helper('Customer')->__('My Account'), 'title'=>Mage::helper('Customer')->__('My Account'),'link'=>Mage::getUrl('customer/account/'))); 
			$breadcrumbs->addCrumb('newsletter', 	 array('label'=>Mage::helper('Customer')->__('Newsletter Subscription'), 'title'=>Mage::helper('Customer')->__('Newsletter Subscription'))); 
		}
        $this->getLayout()->getBlock('head')->setTitle($this->__('Newsletter Subscription'));
        $this->renderLayout();
    }
 
}
