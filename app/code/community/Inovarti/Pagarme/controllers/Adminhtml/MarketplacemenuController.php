<?php

class Inovarti_Pagarme_Adminhtml_MarketplacemenuController
    extends Inovarti_Pagarme_Model_AbstractPagarmeApiAdminController
{

    public function indexAction()
    {
        $this->_title($this->__('Pagarme'))->_title($this->__('Menu Marketplace'));
        $this->loadLayout();
        $this->_setActiveMenu('pagarme/marketplacemenu');
        $this->_addContent($this->getLayout()->createBlock('pagarme/adminhtml_marketplacemenu'));
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_title($this->__("Pagarme"));
        $this->_title($this->__("Marketplace Menu"));
        $this->_title($this->__("New produto to menu marketplace"));
        $id = $this->getRequest()->getParam("entity_id");
        $model = Mage::getModel("pagarme/marketplacemenu");
        $data = Mage::getSingleton("adminhtml/session")->getFormData(true);

        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register("marketplacemenu_data", $model);

        $this->loadLayout();
        $this->_setActiveMenu("pagarme/marketplacemenu");

        $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

        $this->_addBreadcrumb(
            Mage::helper("adminhtml")->__("Marketplace Menu Manager"),
            Mage::helper("adminhtml")->__("Marketplace Menu Manager")
        );

        $this->_addBreadcrumb(
            Mage::helper("adminhtml")->__("Markertplace Menu Description"),
            Mage::helper("adminhtml")->__("Marketplace Menu Description")
        );

        $this->_addContent(
            $this->getLayout()->createBlock("pagarme/adminhtml_marketplacemenu_edit"))
            ->_addLeft($this->getLayout()->createBlock("pagarme/adminhtml_marketplacemenu_edit_tabs"));

        $this->renderLayout();
    }

    public function editAction()
    {
        $this->_title($this->__("Pagarme"));
        $this->_title($this->__("Marketplace Menu"));
        $this->_title($this->__("Edit Marketplace Menu"));

        $id = $this->getRequest()->getParam("entity_id");
        $model = Mage::getModel("pagarme/marketplacemenu")->load($id);

        if ($model->getEntityId()) {
            Mage::register("marketplacemenu_data", $model);
            $this->loadLayout();
            $this->_setActiveMenu("pagarme/marketplacemenu");

            $this->_addBreadcrumb(
                Mage::helper("adminhtml")->__("Marketplace Menu Manager"),
                Mage::helper("adminhtml")->__("Marketplace Menu Manager")
            );

            $this->_addBreadcrumb(
                Mage::helper("adminhtml")->__("Marketplace Menu Description"),
                Mage::helper("adminhtml")->__("Marketplace Menu Description")
            );

            $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock("pagarme/adminhtml_marketplacemenu_edit"))
                ->_addLeft($this->getLayout()->createBlock("pagarme/adminhtml_marketplacemenu_edit_tabs")
                );

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
                $splitRules = Mage::getModel('pagarme/marketplacemenu')
                    ->setData($data)
                    ->save();

                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('pagarme')->__('Success create Marketplace Menu'));
                return $this->_redirect('*/*/');

            } catch (Exception $e) {

                Mage::getSingleton('adminhtml/session')
                    ->addError(
                        Mage::helper('pagarme')->__('Error create product to menu in marketplace : ' . $e->getMessage())
                    );
                $this->_redirect("*/*/");
            }
        }

        try {

            $splitRules = Mage::getModel('pagarme/marketplacemenu')->load($this->getRequest()->getParam('entity_id'));
            $splitRules->addData($data)->save();

            Mage::getSingleton('adminhtml/session')
                ->addSuccess(Mage::helper('pagarme')->__('Success create product to menu in marketplace'));

            return $this->_redirect('*/*/');

        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')
                ->addError(
                    Mage::helper('pagarme')->__('Error create product to menu in marketplace : ' . $e->getMessage())
                );
            $this->_redirect("*/*/");
        }
    }

    public function deleteAction()
    {
        if (!$this->getRequest()->getParam("entity_id")) {
            Mage::getSingleton("adminhtml/session")->addError('id não existe!');
            $this->_redirect("*/*/");
        }

        $id = $this->getRequest()->getParam("entity_id");
        $marketplaceMenu = Mage::getModel("pagarme/marketplacemenu")->load($id);

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