<?php 
class GreenScarf_Customer_Block_Account_Dashboard extends Mage_Customer_Block_Account_Dashboard
{  
  	public function _prepareLayout()
    {
		if($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')){
			$breadcrumbs->addCrumb('home', 			 array('label'=>Mage::helper('Customer')->__('Home'), 		'title'=>Mage::helper('Customer')->__('Home'), 		'link'=>Mage::getBaseUrl())); 
			$breadcrumbs->addCrumb('dashboard', 	 array('label'=>Mage::helper('Customer')->__('My Account'), 		'title'=>Mage::helper('Customer')->__('My Account'))); 
		}
		return parent::_prepareLayout();
    } 
}
