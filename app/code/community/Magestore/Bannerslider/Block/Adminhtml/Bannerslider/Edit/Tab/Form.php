<?php

class Magestore_Bannerslider_Block_Adminhtml_Bannerslider_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
/*
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('bannerslider_form', array('legend'=>Mage::helper('bannerslider')->__('General information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('bannerslider')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
			
			if (!Mage::app()->isSingleStoreMode()) {
				$fieldset->addField('store_id', 'multiselect', array(
							'name'      => 'stores[]',
							'label'     => Mage::helper('cms')->__('Store View'),
							'title'     => Mage::helper('cms')->__('Store View'),
							'required'  => true,
							'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
					));
			}
			else {
					$fieldset->addField('store_id', 'hidden', array(
							'name'      => 'stores[]',
							'value'     => Mage::app()->getStore(true)->getId()
					));					
			}

      $fieldset->addField('filename', 'image', array(
          'label'     => Mage::helper('bannerslider')->__('Image File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
			
	  $fieldset->addField('is_home', 'select', array(
          'label'     => Mage::helper('bannerslider')->__('Show in'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'is_home',
		  'values'	=> Mage::helper('bannerslider')->getDisplayOption(),
      ));
	  
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('bannerslider')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('bannerslider')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('bannerslider')->__('Disabled'),
              ),
          ),
      ));
			
			$fieldset->addField('weblink', 'text', array(
          'label'     => Mage::helper('bannerslider')->__('Web Url'),
          'required'  => false,
          'name'      => 'weblink',
      ));
			
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('bannerslider')->__('Content'),
          'title'     => Mage::helper('bannerslider')->__('Content'),
          'style'     => 'width:280px; height:100px;',
          'wysiwyg'   => false,
          'required'  => false,
      ));
			
     
      if ( Mage::getSingleton('adminhtml/session')->getBannerSliderData() )
      {
          $data = Mage::getSingleton('adminhtml/session')->getBannerSliderData();
          Mage::getSingleton('adminhtml/session')->setBannerSliderData(null);
      } elseif ( Mage::registry('bannerslider_data') ) {
          $data = Mage::registry('bannerslider_data')->getData();
      }
	  $data['store_id'] = explode(',',$data['stores']);
	  $form->setValues($data);
	  
      return parent::_prepareForm();
  }
  */
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('bannerslider_form', array('legend'=>Mage::helper('bannerslider')->__('General information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('bannerslider')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
	  
	  $fieldset->addField('sub_title', 'text', array(
          'label'     => Mage::helper('bannerslider')->__('Sub title'), 
          'required'  => false,
          'name'      => 'sub_title',
      ));
	  
	 
			
	  if (!Mage::app()->isSingleStoreMode()) {
	  	$fieldset->addField('store_id', 'multiselect', array(
	  				'name'      => 'stores[]',
	  				'label'     => Mage::helper('cms')->__('Store View'),
	  				'title'     => Mage::helper('cms')->__('Store View'),
	  				'required'  => true,
	  				'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
	  		));
	  }
	  else {
	  		$fieldset->addField('store_id', 'hidden', array(
	  				'name'      => 'stores[]',
	  				'value'     => Mage::app()->getStore(true)->getId()
	  		));					
	  }

      $fieldset->addField('filename', 'image', array(
          'label'     => Mage::helper('bannerslider')->__('Image File'),
          'required'  => false,
          'name'      => 'filename',
		  'note'	    => Mage::helper('bannerslider')->__('Image width :275px height: 374px'),
	  ));
	  
	  $fieldset->addField('thumbnail', 'image', array(
          'label'     => Mage::helper('bannerslider')->__('Image Thumb'),
          'required'  => false,
          'name'      => 'thumbnail',
		  'note'	    => Mage::helper('bannerslider')->__('Image width :43px height: 42px'),
	  ));
			
	  $fieldset->addField('is_home', 'select', array(
          'label'     => Mage::helper('bannerslider')->__('Show in'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'is_home',
		  'values'	=> Mage::helper('bannerslider')->getDisplayOption(),
      ));
	  
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('bannerslider')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('bannerslider')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('bannerslider')->__('Disabled'),
              ),
          ),
      ));
			
	  $fieldset->addField('weblink', 'text', array(
          'label'     => Mage::helper('bannerslider')->__('Web Url'),
          'required'  => false,
          'name'      => 'weblink',
      ));
	  $fieldset->addField('button_text', 'text', array(
          'label'     => Mage::helper('bannerslider')->__('Button Text'),
          'required'  => true,
          'name'      => 'button_text',
      ));
		
			
      $fieldset->addField('banner_content', 'editor', array(
          'name'      => 'banner_content',
          'label'     => Mage::helper('bannerslider')->__('Content'),
          'title'     => Mage::helper('bannerslider')->__('Content'),
          'style'     => 'width:280px; height:100px;',
		  'note'	    => Mage::helper('bannerslider')->__('Maximum 500 Characters'),
		//  'state'	  => 'html',
          'wysiwyg'   => false,
          'required'  => true,
		  'class'	  => 'maximum-length-500 minimum-length-5 validate-length'
		//  'config'	  => $wysiwygConfig,
      ));
			
     
      if ( Mage::getSingleton('adminhtml/session')->getBannerSliderData() )
      {
          $data = Mage::getSingleton('adminhtml/session')->getBannerSliderData();
          Mage::getSingleton('adminhtml/session')->setBannerSliderData(null);
      } elseif ( Mage::registry('bannerslider_data') ) {
          $data = Mage::registry('bannerslider_data')->getData();
      }
	  $data['store_id'] = explode(',',$data['stores']);
	  $form->setValues($data);
	  
      return parent::_prepareForm();
  }
}