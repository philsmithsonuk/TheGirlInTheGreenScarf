<?php
/*
 * Created on May 21, 2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class Ebizmarts_SagePaymentsCE_Block_Payment_Info_Cc  extends Mage_Payment_Block_Info_Cc
 {
	public function getRefundsCollection()
	{
		try {
	    	$refunds_collection = Mage::getResourceModel('sagepaymentsce/sagepaymentsce_refund_collection');
	    	$refunds_collection->setOrderFilter($this->getInfo()->getOrder()->getId())
//	    				->addAttributeToSelect('*')
	    				->addOrder('refunded_on')
	    				->load();
		}
		catch(exception $e)
		{
			Mage::log("error");
			Mage::log($e->getMessage());
		}
    	return $refunds_collection;
	}
	public function getSageInfo()
	{
		try {
		$sage_info_collection = Mage::getResourceModel('sagepaymentsce/sagepaymentsce_transaction_collection');
		$sage_info_collection->setOrderFilter($this->getInfo()->getOrder()->getId())
//                ->addAttributeToSelect('*')
                ->load();
		}
		catch(exception $e)
		{
			Mage::log("error");
			Mage::log($e->getMessage());
		}
		foreach($sage_info_collection as $sageinfo) {
			return $sageinfo;
			break;
		}
	}
	public function getCvvDescription($indicator)
	{
		$descr = '';
		switch($indicator) {
			case 'M':
				$descr = 'Match';
				break;
			case 'N' :
				$descr = 'CVV no Match';
				break;
			case 'P':
				$descr = 'Not processed';
				break;
			case 'S':
				$descr = 'MERCANT has indicate that CVV2 is not present';
				break;
			case 'U':
				$descr = 'Issuer is not certified';
				break;
		}
		return $descr;
	}
	public function getAvsDescription($indicator)
	{
		$descr = '';
		switch($indicator) {
			case 'X':
				$descr = 'Exact; match on address and 9Digit Zip Code';
				break;
			case 'Y' :
				$descr = 'Yes; match on address and 5 Digit Zip Code';
				break;
			case 'A':
				$descr = 'Address matches, Zip does no';
				break;
			case 'W':
				$descr = '9 Digit Zip matches, address does not';
				break;
			case 'Z':
				$descr = '5 Digit Zip matches, address does not';
				break;
			case 'N':
				$descr = 'No; neither zip nor address match';
				break;
			case 'U':
				$descr = 'Unavailable';
				break;
			case 'R':
				$descr = 'Retry';
				break;
			case 'E':
				$descr = 'Error';
				break;
			case 'S':
			case '':
				$descr = 'Service not supported';
				break;
			case 'D':
				$descr = 'Match Street Address and Postal Code match for International Transaction';
				break;
			case 'M':
				$descr = 'Match Street Address and Postal Code match for International Transaction';
				break;
			case 'B':
				$descr = 'Partial Match Street Address Match for International Transaction. Postal Code not verified due to incompatible formats';
				break;
			case 'P':
				$descr = 'Partial Match Postal Codes match for International Transaction but street address not verified due to incompatible formats';
				break;
		}
		return $descr;
	}
 }
