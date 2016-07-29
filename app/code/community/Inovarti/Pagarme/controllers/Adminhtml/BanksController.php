<?php

class Inovarti_Pagarme_Adminhtml_BanksController
    extends Inovarti_Pagarme_Model_AbstractPagarmeApiAdminController
{

    public function indexAction()
    {
        $this->_title($this->__('Pagarme'))->_title($this->__('Banks Plans'));
        $this->loadLayout();
        $this->_setActiveMenu('pagarme/banks');
        $this->_addContent($this->getLayout()->createBlock('pagarme/adminhtml_banks'));
        $this->renderLayout();
    }
    
    public function newAction()
    {
        $this->_title($this->__("Pagarme"));
        $this->_title($this->__("Banks"));
        $this->_title($this->__("New Item"));
        $id   = $this->getRequest()->getParam("id");
        $model  = Mage::getModel("pagarme/banks");
        $data = Mage::getSingleton("adminhtml/session")->getFormData(true);

        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register("banks_data", $model);

        $this->loadLayout();
        $this->_setActiveMenu("pagarme/plans");

        $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Banks Manager"), Mage::helper("adminhtml")->__("Banks Manager"));
        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Banks Description"), Mage::helper("adminhtml")->__("Banks Description"));
        $this->_addContent($this->getLayout()->createBlock("pagarme/adminhtml_banks_edit"))->_addLeft($this->getLayout()->createBlock("pagarme/adminhtml_banks_edit_tabs"));

        $this->renderLayout();
    }
//
//    public function editAction()
//    {
//        $this->_title($this->__("Pagarme"));
//        $this->_title($this->__("Banks"));
//        $this->_title($this->__("Edit Bank Account"));
//
//        $id = $this->getRequest()->getParam("entity_id");
//        $model = Mage::getModel("pagarme/banks")->load($id);
//
//
//        if ($model->getEntityId()) {
//            Mage::register("banks_data", $model);
//            $this->loadLayout();
//            $this->_setActiveMenu("pagarme/banks");
//            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Bank Account Manager"), Mage::helper("adminhtml")->__("Bank Account Manager"));
//            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Bank Account Description"), Mage::helper("adminhtml")->__("Bank Account Description"));
//            $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
//            $this->_addContent($this->getLayout()->createBlock("pagarme/adminhtml_banks_edit"))->_addLeft($this->getLayout()->createBlock("pagarme/adminhtml_banks_edit_tabs"));
//            $this->renderLayout();
//        } else {
//            Mage::getSingleton("adminhtml/session")->addError(Mage::helper("pagarme")->__("Item does not exist."));
//            $this->_redirect("*/*/");
//        }
//    }
    
    public function saveAction()
    {
        $data = $this->getRequest()->getPost();

        if (!$data) {
            $this->_redirect("*/*/");
        }

        $bankAccountParams = array(
            "bank_code" => $data['bank_code'],
            "agencia" => $data['agency'],
            "agencia_dv" => $data['agency_dv'],
            "conta" => $data['account_number'],
            "conta_dv" => $data['account_dv'],
            "document_number" => $data['document_number'],
            "legal_name" => $data['legal_name']
        );

        $account = new Pagarme_Bank_Account($bankAccountParams);

        try {

            $account->create();

            Mage::getSingleton('adminhtml/session')
                ->addSuccess(Mage::helper('pagarme')->__('Success create back account'));
            $this->_redirect('*/*/');

        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')
                ->addError(Mage::helper('pagarme')->__('Error create back account : '. $e->getMessage()));
            $this->_redirect("*/*/");
            return;
        }
    }
}