<?php

class Inovarti_Pagarme_Model_System_Config_Validator_ChargeProcessingFeeValidator extends Mage_Core_Model_Config_Data
{
    public function _beforeSave() {
        $marketPlaceIsResponsibleForChargeProcessingFee = $this->getValue();

        if(!$marketPlaceIsResponsibleForChargeProcessingFee) {
            $splitRuleCollection = Mage::getModel('pagarme/splitrules')
                ->getCollection()
                ->addFieldToFilter('charge_processing_fee', array('eq', '0'));
            $splitRuleCollection->getSelect()
                ->join(array('marketplace_menu' => Mage::getConfig()->getTablePrefix() . 'pagarme_marketplace_menu'),
                    'main_table.recipient_id = marketplace_menu.recipient_id');

            $qtdRulesThatAreNotResponsibleForChargeProcessingFee = $splitRuleCollection->count();

            if($qtdRulesThatAreNotResponsibleForChargeProcessingFee > 0) {
                Mage::throwException('More than one recipients are not responsible for charge processing fee');
            }
        }
    }
}
