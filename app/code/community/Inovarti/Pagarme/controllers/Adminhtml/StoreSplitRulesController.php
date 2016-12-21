<?php

class Inovarti_Pagarme_Adminhtml_StoreSplitRulesController extends Inovarti_Pagarme_Model_AbstractPagarmeApiAdminController
{
    public function indexAction()
    {
        $this->_title($this->__('Pagarme'))->_title($this->__('Store Split Rules'));
        $this->loadLayout();
        $this->_setActiveMenu('pagarme/storeSplitRules');
        $this->_addContent($this->getLayout()->createBlock('pagarme/adminhtml_storeSplitRules'));
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_title($this->__('Pagarme'));
        $model = Mage::getModel('pagarme/storeSplitRules');
        $data  = Mage::getSingleton('adminhtml/session')->getFormData(true);

        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('storeSplitRules_data', $model);

        $this->loadLayout();
        $this->_setActiveMenu('pagarme/storeSplitRules');

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->_addBreadcrumb(
            Mage::helper('adminhtml')->__('Split Rules Manager')
        );

        $this->_addContent($this->getLayout()->createBlock('pagarme/adminhtml_storeSplitRules_edit'))
            ->_addLeft($this->getLayout()->createBlock('pagarme/adminhtml_storeSplitRules_edit_tabs'));

        $this->renderLayout();
    }

    public function saveAction()
    {
        $data = $this->getRequest()->getPost();

        $splitRulesData = $data['split_rules'];
        $storeId = $data['store_id'];
        $amount = 0;
        $hasResponsibleForChargeProcessingFee = false;
        $hasResponsibleForChargeback = false;

        $conn = Mage::getSingleton('core/resource')
            ->getConnection('core_write');

        $conn->beginTransaction();

        $splitRulesGroup = Mage::getModel('pagarme/splitRulesGroup');
        $splitRulesGroup->setGroupName('xablaaaaaaaaaaaau!');

        $store = Mage::getModel('core/store')->load($data['store_id']);

        $splitRulesGroup->setStore($store);

        foreach ($splitRulesData as $splitRuleData) {
            $splitRule = Mage::getModel('pagarme/splitrules');
            $splitRule->setData($splitRuleData);
            $splitRulesGroup->addSplitRule($splitRule);

            if (!$hasResponsibleForChargeProcessingFee) {
                $hasResponsibleForChargeProcessingFee = $splitRuleData['charge_processing_fee'] == true;
            }

            if (!$hasResponsibleForChargeback) {
                $hasResponsibleForChargeback = $splitRuleData['liable'] == true;
            }

            $amount += (double) $splitRuleData['amount'];
        }

        $splitRulesGroup->save();

        $conn->commit();
    }
}
