<?php

class Inovarti_Pagarme_Adminhtml_WebsiteSplitRulesController extends Inovarti_Pagarme_Model_AbstractPagarmeApiAdminController
{
    public function indexAction()
    {
        $this->_title($this->__('Pagarme'))->_title($this->__('website Split Rules'));
        $this->loadLayout();
        $this->_setActiveMenu('pagarme/websiteSplitRules');
        $this->_addContent($this->getLayout()->createBlock('pagarme/adminhtml_websiteSplitRules'));
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_title($this->__('Pagarme'));
        $model = Mage::getModel('pagarme/websiteSplitRules');
        $data  = Mage::getSingleton('adminhtml/session')->getFormData(true);

        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('websiteSplitRules_data', $model);

        $this->loadLayout();
        $this->_setActiveMenu('pagarme/websiteSplitRules');

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->_addBreadcrumb(
            Mage::helper('adminhtml')->__('Split Rules Manager')
        );

        $this->_addContent($this->getLayout()->createBlock('pagarme/adminhtml_websiteSplitRules_edit'))
            ->_addLeft($this->getLayout()->createBlock('pagarme/adminhtml_websiteSplitRules_edit_tabs'));

        $this->renderLayout();
    }

    public function editAction()
    {
        $this->_title($this->__('Pagarme'));

        $splitRulesGroupId = $this->getRequest()->getParam('entity_id');
        $splitRulesGroup = Mage::getModel('pagarme/splitRulesGroup')
            ->load($splitRulesGroupId);


        if($splitRulesGroup->getId() != '') {
            $splitRules = Mage::getModel('pagarme/splitrules')
                ->getCollection()
                ->addFieldToFilter('group_id', $splitRulesGroup->getId());

            $splitRulesGroupData = $splitRulesGroup->getData();
            $splitRulesGroupData['split_rules'] = $splitRules->getData();

            Mage::register('websiteSplitRules_data', $splitRulesGroupData);

            $this->loadLayout();
            $this->_setActiveMenu('pagarme/websiteSplitRules');

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Split Rules Manager')
            );

            $this->_addContent($this->getLayout()->createBlock('pagarme/adminhtml_websiteSplitRules_edit'))
            ->_addLeft($this->getLayout()->createBlock('pagarme/adminhtml_websiteSplitRules_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('pagarme')->__('Split not found'));
            return $this->_redirect('*/*/');
        }
    }

    public function saveAction()
    {
        $pagarMeHelper = Mage::helper('pagarme');
        $session = Mage::getSingleton('adminhtml/session');

        $data = $this->getRequest()->getPost();
        $splitRulesGroup = Mage::getModel('pagarme/splitRulesGroup');
        $splitRulesGroupId = $this->getRequest()->getParam('entity_id');

        $website = Mage::getModel('core/website')->load($data['website_id']);
        $splitRulesGroup->setWebsite($website);

        if($splitRulesGroupId > 0) {
            $splitRulesGroup->load($splitRulesGroupId);
            $splitRulesGroup->addData($data);

            foreach($data['split_rules'] as $splitRuleData) {
                $splitRule = Mage::getModel('pagarme/splitrules')
                    ->load($splitRuleData['entity_id']);
                $splitRule->addData($splitRuleData);

                $splitRulesGroup->addSplitRule($splitRule);
            }

        } else {
            $splitRulesGroup->setGroupName('xablau');

            foreach($data['split_rules'] as $splitRuleData) {
                $splitRule = Mage::getModel('pagarme/splitrules')
                    ->setData($splitRuleData);

                $splitRulesGroup->addSplitRule($splitRule);
            }
        }

        $errors = $splitRulesGroup->validate();

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $session->addError($pagarMeHelper->__($error));
            }

            return $this->_redirect('*/*/');
        }

        try {
            $splitRulesGroup->save();
            $session->addSuccess($pagarMeHelper->__('Salvo com sucesso!'));
            return $this->_redirect('*/*/');
        } catch (Exception $e) {
            $session->addError($pagarMeHelper->__('Error saving split rules: ' . $e->getMessage()));
            return $this->_redirect('*/*/');
        }
    }

    public function deleteAction()
    {
        $splitRulesGroupId = $this->getRequest()->getParam('entity_id');

        $splitRulesGroup = Mage::getModel('pagarme/splitRulesGroup')
            ->load($splitRulesGroupId);

        $splitRulesGroup->delete();
    }
}
