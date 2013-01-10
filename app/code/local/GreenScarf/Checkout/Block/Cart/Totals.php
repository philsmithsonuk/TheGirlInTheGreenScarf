<?php

class GreenScarf_Checkout_Block_Cart_Totals extends Mage_Checkout_Block_Cart_Totals
{
	protected $_quote    = null;
	
    public function renderTotal($total, $area = null, $colspan = 1)
    {	
        $code = $total->getCode();
        if ($total->getAs()) {
            $code = $total->getAs();
        }		
        return $this->_getTotalRenderer($code)
            ->setTotal($total)
            ->setColspan($colspan)
            ->setRenderingArea(is_null($area) ? -1 : $area)
            ->toHtml();
    }
	
	/** 
     * 
	 * Added to separate in order to have better control of the cart totals renderer.
     * 
	 * @author joseph.silva
	 *	
     **/
	 
	public function renderSubTotal($label="",$displayLabel=false,$colspan = 1){
        return $this->__render("subtotal",$label,$displayLabel,$colspan);
	}
	
	public function renderShippingTotal($label="",$displayLabel=false,$colspan = 1){
		Mage::getSingleton('checkout/session')->setCanDisplayTaxTemplate(false);
		return $this->__render("shipping",$label,$displayLabel,$colspan,true);
	}
	
	public function renderDiscountTotal($label="",$displayLabel=false,$colspan = 3){
        return $this->__render("discount",$label,$displayLabel,$colspan);
    }   
    public function renderGrandTotal($label="",$displayLabel=false,$colspan = 3){
        return $this->__render("grand_total",$label,$displayLabel,$colspan);
    }
	
	public function __render($code,$label="",$displayLabel=false,$colspan = 1,$isForShipping=false){
		$html = '';
        foreach($this->getTotals() as $total) {
            if($total->getCode() == $code){
				if(!$displayLabel) $total->setTitle(null);
				else {
					if(!empty($label))
						$total->setTitle($label);
				}
				
				if($isForShipping) $total->getAddress()->setTitle($label);
				$html .= $this->renderTotal($total, null, $colspan);
			}
        }
        return $html;
	} 
	public function getQuoteModel()
	{
		return Mage::getSingleton('checkout/session')->getQuote();
	}	
}
