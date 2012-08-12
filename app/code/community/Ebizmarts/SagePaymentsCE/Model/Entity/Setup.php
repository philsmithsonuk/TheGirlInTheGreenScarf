<?php

 class Ebizmarts_SagePaymentsCE_Model_Entity_Setup extends Mage_Eav_Model_Entity_Setup
{
    public function getDefaultEntities()
    {
        return array(
        	'sagepaymentsce_transactions' => array(
                'entity_model'      => 'sagepaymentsce/transactions',
                'table'=>'sales/order_entity',
                'attributes' => array(
                    'parent_id' => array(
                        'type'=>'static',
                        'backend'=>'sales_entity/order_attribute_backend_child'
                    ),
                    'address_result'    => array('type'=>'int'),
                    'postcode_result'   => array('type'=>'int'),
                    'cv2_result' => array('type'=>'int'),
                    'security_key' => array('type'=>'varchar'),
                    'vendor_tx_code' => array('type'=>'varchar'),
                    'released' => array('type'=>'int'),
                    'authorised' => array('type'=>'int'),
                    'canceled' => array('type'=>'int'),
                    'aborted' => array('type'=>'int'),
                    'repeated' => array('type'=>'int'),
                    'voided' => array('type'=>'int'),
                    'action_date' => array('type'=>'datetime'),
                    'action_by'=>array('type'=>'varchar'),
                    'sgpdp_amount_released'=>array('type'=>'decimal')
                )
            ),
        );
    }
}