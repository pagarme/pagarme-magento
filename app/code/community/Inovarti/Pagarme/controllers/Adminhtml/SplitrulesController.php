<?php

class Inovarti_Pagarme_Adminhtml_SplitrulesController
    extends Inovarti_Pagarme_Model_AbstractPagarmeApiAdminController
{

    public function indexAction()
    {
        $this->_title($this->__('Pagarme'))->_title($this->__('Split Rule`s'));
        $this->loadLayout();
        $this->_setActiveMenu('pagarme/splitrules');
        $this->_addContent($this->getLayout()->createBlock('pagarme/adminhtml_splitrules'));
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_title($this->__("Pagarme"));
        $this->_title($this->__("Split Rules"));
        $this->_title($this->__("New Split Rule"));
        $id   = $this->getRequest()->getParam("id");
        $model  = Mage::getModel("pagarme/splitrules");
        $data = Mage::getSingleton("adminhtml/session")->getFormData(true);

        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register("splitrules_data", $model);

        $this->loadLayout();
        $this->_setActiveMenu("pagarme/splitrules");

        $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

        $this->_addBreadcrumb(
            Mage::helper("adminhtml")->__("Split Rules Manager"),
            Mage::helper("adminhtml")->__("Split Rules Manager")
        );

        $this->_addBreadcrumb(
            Mage::helper("adminhtml")->__("Split Rules Description"),
            Mage::helper("adminhtml")->__("Split Rules Description")
        );

        $this->_addContent($this->getLayout()->createBlock("pagarme/adminhtml_splitrules_edit"))
            ->_addLeft($this->getLayout()->createBlock("pagarme/adminhtml_splitrules_edit_tabs"));

        $this->renderLayout();
    }

    public function editAction()
    {
        $this->_title($this->__("Pagarme"));
        $this->_title($this->__("Split Rules"));
        $this->_title($this->__("Edit Split Rules"));

        $id = $this->getRequest()->getParam("entity_id");
        $model = Mage::getModel("pagarme/splitrules")->load($id);

        if ($model->getId()) {
            Mage::register("splitrules_data", $model);
            $this->loadLayout();
            $this->_setActiveMenu("pagarme/splitrules");
            $this->_addBreadcrumb(
                Mage::helper("adminhtml")->__("Split Rules Manager"),
                Mage::helper("adminhtml")->__("Split Rules Manager")
            );
            $this->_addBreadcrumb(
                Mage::helper("adminhtml")->__("Split Rules Description"),
                Mage::helper("adminhtml")->__("Split Rules Description")
            );
            $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock("pagarme/adminhtml_splitrules_edit"))
                ->_addLeft($this->getLayout()->createBlock("pagarme/adminhtml_splitrules_edit_tabs"));
            $this->renderLayout();
        } else {
            Mage::getSingleton("adminhtml/session")->addError(Mage::helper("pagarme")->__("Item does not exist."));
            $this->_redirect("*/*/");
        }
    }

    public function saveAction()
    {
        $data = $this->getRequest()->getPost();

        if (!$this->getRequest()->getParam('entity_id')) {

            try {

                $splitRules = Mage::getModel('pagarme/splitrules')
                    ->setData($data)
                    ->save();

                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('pagarme')->__('Success create Split Rule'));
                return $this->_redirect('*/*/');

            } catch (Exception $e) {

                Mage::getSingleton('adminhtml/session')
                    ->addError(Mage::helper('pagarme')->__('Error create recipient account : '. $e->getMessage()));
               return $this->_redirect("*/*/");
            }
        }

        try {

            $splitRules = Mage::getModel('pagarme/splitrules')->load($this->getRequest()->getParam('entity_id'));
            $splitRules->addData($data)->save();

            Mage::getSingleton('adminhtml/session')
                ->addSuccess(Mage::helper('pagarme')->__('Success create Split Rule'));
            return $this->_redirect('*/*/');

        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')
                ->addError(Mage::helper('pagarme')->__('Error create recipient account : '. $e->getMessage()));
           return $this->_redirect("*/*/");
        }
    }

    public function deleteAction()
    {
        if (!$this->getRequest()->getParam("entity_id")) {
            Mage::getSingleton("adminhtml/session")->addError('id não existe!');
            $this->_redirect("*/*/");
        }

        $id = $this->getRequest()->getParam("entity_id");
        $marketplaceMenu = Mage::getModel("pagarme/splitrules")->load($id);

        try {
            $marketplaceMenu->delete();
            Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
            $this->_redirect("*/*/");
        } catch (Exception $e) {
            Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
            $this->_redirect("*/*/edit", array("entity_id" => $this->getRequest()->getParam("entity_id")));
        }

        $this->_redirect("*/*/");
    }
}