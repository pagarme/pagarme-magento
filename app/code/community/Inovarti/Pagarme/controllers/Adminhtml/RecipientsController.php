<?php

class Inovarti_Pagarme_Adminhtml_RecipientsController
    extends Inovarti_Pagarme_Model_AbstractPagarmeApiAdminController
{

    public function indexAction()
    {
        $this->_title($this->__('Pagarme'))->_title($this->__('Recipients'));
        $this->loadLayout();
        $this->_setActiveMenu('pagarme/recipients');
        $this->_addContent($this->getLayout()->createBlock('pagarme/adminhtml_recipients'));
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_title($this->__("Pagarme"));
        $this->_title($this->__("Recipients"));
        $this->_title($this->__("New Recipient"));
        $id   = $this->getRequest()->getParam("id");
        $model  = Mage::getModel("pagarme/recipients");
        $data = Mage::getSingleton("adminhtml/session")->getFormData(true);

        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register("recipients_data", $model);

        $this->loadLayout();
        $this->_setActiveMenu("pagarme/recipients");

        $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

        $this->_addBreadcrumb(
            Mage::helper("adminhtml")->__("Recipients Manager"),
            Mage::helper("adminhtml")->__("Recipients Manager")
        );

        $this->_addBreadcrumb(
            Mage::helper("adminhtml")->__("Recipients Description"),
            Mage::helper("adminhtml")->__("Recipients Description")
        );

        $this->_addContent($this->getLayout()->createBlock("pagarme/adminhtml_banks_edit"))
            ->_addLeft($this->getLayout()->createBlock("pagarme/adminhtml_recipients_edit_tabs"));

        $this->renderLayout();
    }

    public function editAction()
    {
        $this->_title($this->__("Pagarme"));
        $this->_title($this->__("Recipients"));
        $this->_title($this->__("Edit Recipient Account"));

        $id = $this->getRequest()->getParam("id");
        $model = Mage::getModel("pagarme/recipients")->load($id);

        if ($model->getId()) {
            Mage::register("recipients_data", $model);
            $this->loadLayout();
            $this->_setActiveMenu("pagarme/recipients");
            $this->_addBreadcrumb(
                Mage::helper("adminhtml")->__("Recipient Account Manager"),
                Mage::helper("adminhtml")->__("Recipient Account Manager")
            );
            $this->_addBreadcrumb(
                Mage::helper("adminhtml")->__("Recipient Account Description"),
                Mage::helper("adminhtml")->__("Recipient Account Description")
            );

            $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock("pagarme/adminhtml_recipients_edit"))
                ->_addLeft($this->getLayout()->createBlock("pagarme/adminhtml_recipients_edit_tabs"));
            $this->renderLayout();
        } else {
            Mage::getSingleton("adminhtml/session")->addError(Mage::helper("pagarme")->__("Item does not exist."));
            $this->_redirect("*/*/");
        }
    }

    public function saveAction()
    {
        $data = $this->getRequest()->getPost();
        unset($data['form_key']);

        $data['transfer_enabled'] = (bool) $data['transfer_enabled'];
        $data['transfer_interval'] = $data['transfer_interval'] == '' ? null : $data['transfer_interval'];
        $data['transfer_day'] = $data['transfer_day'] == '' ? null : $data['transfer_day'];

        $recipientId = $this->getRequest()->getParam('id');

        $message = '';
        try {
            if(!$recipientId) {
                $recipient = new PagarMe_Recipient($data);
                $recipient->create();

                $message = 'Success create Recipient account';
            } else {
                $recipient = PagarMe_Recipient::findById($recipientId);
                $recipient->setTransferEnabled($data['transfer_enabled']);
                $recipient->setTransferInterval($data['transfer_interval']);
                $recipient->setTransferDay($data['transfer_day']);
                $recipient->setBankAccountId($data['bank_account_id']);
                $recipient->save();

                $message = 'Success on editing Recipient account';
            }

            Mage::getSingleton('adminhtml/session')
                ->addSuccess(Mage::helper('pagarme')->__($message));
            $this->_redirect('*/*/');
        } catch(Exception $e) {
            Mage::getSingleton('adminhtml/session')
                ->addError(Mage::helper('pagarme')->__('Error create recipient account : '. $e->getMessage()));
            $this->_redirect("*/*/");
        }
    }
}
