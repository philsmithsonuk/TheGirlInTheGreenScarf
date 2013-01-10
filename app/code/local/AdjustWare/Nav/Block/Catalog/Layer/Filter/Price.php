<?php
/**
 * Product:     Layered Navigation Pro - 16/08/12
 * Package:     AdjustWare_Nav_2.4.7_0.1.4_8_357526
 * Purchase ID: RtE0qeQE7RRjsdRvhv07l9cGxzFoZAJ502qOJCvubx
 * Generated:   2012-12-20 08:02:01
 * File path:   app/code/local/AdjustWare/Nav/Block/Catalog/Layer/Filter/Price.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'AdjustWare_Nav')){ qoIkiajaskBkEjaD('d6bfa49e97b94a27367ee3419d9b2966'); ?><?php
class AdjustWare_Nav_Block_Catalog_Layer_Filter_Price extends Mage_Catalog_Block_Layer_Filter_Price
{
    private $_style;

    public function __construct()
    {
        parent::__construct();
        $this->_style = Mage::getStoreConfig('design/adjnav/price_style');
        $this->setTemplate('adjnav/filter_price_' . $this->_style . '.phtml');

        $this->_filterModelName = 'adjnav/catalog_layer_filter_price';
    }

    public function getVar(){
        return $this->_filter->getRequestVar();
    }

    public function getDelimeter() {
        if( version_compare( Mage::getVersion(),'1.7.0.0','>=' ) ) {
            return '-'; //magento 1.7+ "$from-$to"
        }
        return ',';//matento 1.6- "$index,$range"
    }

    public function getClearUrl()
    {
        $url = '';
        $query = Mage::helper('adjnav')->getParams();
//        if ('slider' != $this->_style && !empty($query[$this->getVar()])){
        if (!empty($query[$this->getVar()])){
            $query[$this->getVar()] = null;
            $url = Mage::getUrl('*/*/*', array(
                '_use_rewrite' => true,
                '_query'       => $query,
            ));
        }
        return $url;
    }

    public function isSelected($item)
    {
        return ($item->getValueString() == $this->_filter->getActiveState());
    }

    public function getItemUrl($_item)
    {
        $href = $this->htmlEscape($currentUrl = Mage::app()->getRequest()->getBaseUrl());

        //if (!$hideLinks)
        {
            $href .= $this->getRequestPath();

            $params = Mage::helper('adjnav')->getParams();
            $params[$this->getVar()] = $_item->getValueString();

            if ($params = http_build_query($params))
            {
	            $href .= '?' . $params;
            }
        }
        return $href;
    }

    /**
     * Will return GET part of the request
     *
     *	@return string
     */
    public function getRequestPath()
    {
    	$request = Mage::app()->getRequest();

    	$requestPath = '';

    	if ($request->isXmlHttpRequest())
    	{
    		$requestPath = Mage::getSingleton('core/session')->getRequestPath();
    	}
    	else
    	{
    		Mage::getSingleton('core/session')->setRequestPath($requestPath = $request->getRequestString());
    	}

    	return $this->htmlEscape($requestPath);
    }

    public function getSymbol()
    {
        $s = $this->getData('symbol');
        if (!$s){
            $code = Mage::app()->getStore()->getCurrentCurrencyCode();
            $s = trim(Mage::app()->getLocale()->currency($code)->getSymbol());

            $this->setData('symbol', $s);
        }
        return $s;
    }

    public function getCollectionSize() {
        return $this->getLayer()->getProductCollection()->getSize();
    }
} } 