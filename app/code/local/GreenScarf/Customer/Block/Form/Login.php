<?php 
class GreenScarf_Customer_Block_Form_Login extends Mage_Customer_Block_Form_Login
{
     public function _prepareLayout()
    {
		if($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')){
			$breadcrumbs->addCrumb('home', 			 array('label'=>Mage::helper('Customer')->__('Home'),'title'=>Mage::helper('Customer')->__('Home'), 		'link'=>Mage::getBaseUrl())); 
			$breadcrumbs->addCrumb('myaccount', 	 array('label'=>Mage::helper('Customer')->__('My Account'), 'title'=>Mage::helper('Customer')->__('My Account'),'link'=>Mage::getUrl('customer/account/'))); 
		 }
		return parent::_prepareLayout();
    } 
}
