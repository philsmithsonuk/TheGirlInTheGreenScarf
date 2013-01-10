<?php 
class GreenScarf_Customer_Block_Address_Book extends Mage_Customer_Block_Address_Book
{
   	public function _prepareLayout()
    {
		if($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')){
			$breadcrumbs->addCrumb('home', 			 array('label'=>Mage::helper('Customer')->__('Home'),'title'=>Mage::helper('Customer')->__('Home'), 		'link'=>Mage::getBaseUrl())); 
			$breadcrumbs->addCrumb('myaccount', 	 array('label'=>Mage::helper('Customer')->__('My Account'), 'title'=>Mage::helper('Customer')->__('My Account'),'link'=>Mage::getUrl('customer/account/'))); 
			$breadcrumbs->addCrumb('addessbook', 	 array('label'=>Mage::helper('Customer')->__('Address Book'), 'title'=>Mage::helper('Customer')->__('Address Book'))); 
		}
		return parent::_prepareLayout();
    } 
}
