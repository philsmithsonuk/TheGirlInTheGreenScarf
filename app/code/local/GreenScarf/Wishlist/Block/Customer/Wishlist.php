<?php 
class GreenScarf_Wishlist_Block_Customer_Wishlist extends Mage_Wishlist_Block_Customer_Wishlist
{
     public function _prepareLayout()
    {
		if($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')){
			$breadcrumbs->addCrumb('home', 			 array('label'=>Mage::helper('Customer')->__('Home'),'title'=>Mage::helper('Customer')->__('Home'), 		'link'=>Mage::getBaseUrl())); 
			$breadcrumbs->addCrumb('myaccount', 	 array('label'=>Mage::helper('Customer')->__('My Account'), 'title'=>Mage::helper('Customer')->__('My Account'),'link'=>Mage::getUrl('customer/account/'))); 
			$breadcrumbs->addCrumb('mywishlist', 	 array('label'=>Mage::helper('Customer')->__('My Wishlist'), 'title'=>Mage::helper('Customer')->__('My Wishlist'))); 
		}
		return parent::_prepareLayout();
    } 
}
