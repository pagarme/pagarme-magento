<?php
/**
 *
 * @category   Inovarti
 * @package    Inovarti_Pagarme
 * @author     Suporte <suporte@inovarti.com.br>
 *
 * UPDATED:
 *
 * @copyright   Copyright (C) 2015 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author     Eneias Ramos de Melo <eneias@gamuza.com.br>
 */
class Inovarti_Pagarme_Model_Observer
{
    const PAGARME_CC_PAYMENT_METHOD = 'pagarme_cc';
    const PAGARME_CHECKOUT_PAYMENT_METHOD = 'pagarme_checkout';

    public function addPagarmeJs(Varien_Event_Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        $blockType = $block->getType();
        $targetBlocks = array(
            'checkout/onepage_payment',
            'aw_onestepcheckout/onestep_form_paymentmethod',
            'onestepcheckout/onestep_form_paymentmethod',
        );
        if (in_array($blockType, $targetBlocks) && Mage::getStoreConfig('payment/pagarme_cc/active')) {
            $transport = $observer->getTransport();
            $html = $transport->getHtml();
            $preHtml = $block->getLayout()
                ->createBlock('core/template')
                ->setTemplate('pagarme/checkout/payment/js.phtml')
                ->toHtml();
            $transport->setHtml($preHtml . $html);
        }
    }

    public function invoicePay(Varien_Event_Observer $observer)
    {
        $invoice = $observer->getEvent()->getInvoice();
        $order = $invoice->getOrder();
        if ($invoice->getBaseFeeAmount())
        {
            $order->setFeeAmountInvoiced($order->getFeeAmountInvoiced() + $invoice->getFeeAmount());
            $order->setBaseFeeAmountInvoiced($order->getBaseFeeAmountInvoiced() + $invoice->getBaseFeeAmount());
        }
        $payment_method = $order->getPayment()->getMethod();
        $invoice_email = Mage::getStoreConfig("payment/{$payment_method}/invoice_email");
        $comment = Mage::helper('sales')->__('Approved the payment online.');
        switch ($invoice_email) {
        case '1': {
            $invoice->sendEmail(true, $comment);
            break;
        }
        case '2': {
            $invoice->sendUpdateEmail(true, $comment);
            break;
        }
        };
        return $this;
    }

    public function creditmemoRefund(Varien_Event_Observer $observer)
    {
        $creditmemo = $observer->getEvent()->getCreditmemo();
        if ($creditmemo->getFeeAmount())
        {
            $order = $creditmemo->getOrder();
            $order->setFeeAmountRefunded($order->getFeeAmountRefunded() + $creditmemo->getFeeAmount());
            $order->setBaseFeeAmountRefunded($order->getBaseFeeAmountRefunded() + $creditmemo->getBaseFeeAmount());
        }
        return $this;
    }

    public function updateOrderStatusInvoiced(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $paymentMethod = $order->getPayment()->getMethod();

        if ($paymentMethod === self::PAGARME_CC_PAYMENT_METHOD) {
            $validatePaymentMethod  = self::PAGARME_CC_PAYMENT_METHOD;
            $configOrderStatus      = Mage::getStoreConfig('payment/pagarme_cc/order_status');
            $configOrderStatusPaid  = Mage::getStoreConfig('payment/pagarme_cc/order_status_paid');
        }

        if ($paymentMethod === self::PAGARME_CHECKOUT_PAYMENT_METHOD) {
            $validatePaymentMethod  = self::PAGARME_CHECKOUT_PAYMENT_METHOD;
            $configOrderStatus      = Mage::getStoreConfig('payment/pagarme_checkout/order_status');
            $configOrderStatusPaid  = Mage::getStoreConfig('payment/pagarme_checkout/order_status_paid');
        }

        if (!isset($validatePaymentMethod)) {
          return $this;
        }

        if ($order->hasInvoices()
            && $order->getState() === Mage_Sales_Model_Order::STATE_PROCESSING
            && $order->getStatus() === $configOrderStatus) {

              $order->setStatus($configOrderStatusPaid, true);
              $history = $order->addStatusHistoryComment('status automatically changed to ('.$configOrderStatusPaid.') by setting the module Pagar.me', false);
              $history->setIsCustomerNotified(true);
              $order->save();

              Mage::log('status automatically changed to ('.$configOrderStatusPaid.') by setting the module Pagar.me', null, 'pagarme.log');
              return $this;
        }
    }
}
