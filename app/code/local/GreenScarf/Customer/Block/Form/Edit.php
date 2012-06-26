<?php 
class GreenScarf_Customer_Block_Form_Edit extends Mage_Customer_Block_Form_Edit
{
	public function _prepareLayout()
    {
		if($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')){
			$breadcrumbs->addCrumb('home', 			 array('label'=>Mage::helper('Customer')->__('Home'),'title'=>Mage::helper('Customer')->__('Home'), 		'link'=>Mage::getBaseUrl())); 
			$breadcrumbs->addCrumb('myaccount', 	 array('label'=>Mage::helper('Customer')->__('My Account'), 'title'=>Mage::helper('Customer')->__('My Account'),'link'=>Mage::getUrl('customer/account/'))); 
			$breadcrumbs->addCrumb('personaldetails', 	 array('label'=>Mage::helper('Customer')->__('Personal Details'), 'title'=>Mage::helper('Customer')->__('Personal Details'))); 
		}
		return parent::_prepareLayout();
    } 
}
