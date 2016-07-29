<?php

class Inovarti_Pagarme_Block_Adminhtml_Order_View_Tab_SplitOrder
    extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * @var
     */
    private $order;

    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('pagarme/order/view/tab/split.phtml');
        $this->order = $this->getOrder();
    }

    /**
     * @return mixed
     */
    public function getPayables()
    {
        $transactionId = $this->getTransactionId();
        return Mage::getModel('pagarme/TransactionPayables')
            ->preparePayables($transactionId);
    }

    /**
     * @return mixed
     */
    public function getTotalAmount()
    {
        return Mage::helper('core')->currency($this->order->getGrandTotal(), true, false);
    }

    /**
     * @return mixed
     */
    public function getTransactionId()
    {
        return $this->getOrder()->getPayment()->getPagarmeTransactionId();
    }

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Split Order Details');
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Split Order Details');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return Mage::registry('current_order');
    }
}