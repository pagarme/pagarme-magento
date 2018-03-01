<?php

namespace PagarMe\Magento\Test\CreditCard;

trait AdminPaymentDetailsCheck
{
    /**
     * @When I check the order payment details
     */
    public function iCheckTheOrderPaymentDetails()
    {
        $order = \Mage::getModel('sales/order')
            ->load($this->createdOrderId, 'increment_id');
        $this->session
            ->visit(
                $this->magentoUrl . 'admin/sales_order/view/order_id/' . $order->getId()
            );
    }

    /**
     * @Then the admin details should contain the payment method :paymentMethod, installments value :installments, customer name and card brand
     */
    public function theAdminDetailsShouldContainThePaymentMethodInstallmentsValueCustomerNameAndCardBrand(
        $paymentMethod,
        $installments
    ) {
        $this->waitForElement(
            '#pagarme_order_info_payment_details',
            3000
        );
        $page = $this->session->getPage();
        $helper = \Mage::helper('pagarme_creditcard');

        $this->assertLabelEquals(
            $page,
            '#pagarme_order_info_payment_customer_name .label',
            $helper->__('Customer name')
        );

        $this->assertLabelEquals(
            $page,
            '#pagarme_order_info_payment_card_brand .label',
            $helper->__('Card brand')
        );

        $this->assertLabelEquals(
            $page,
            '#pagarme_order_info_payment_payment_method .label',
            $helper->__('Payment Method')
        );
        $this->assertFieldValue(
            $page,
            '#pagarme_order_info_payment_payment_method .value',
            $helper->__($paymentMethod)
        );

        $this->assertLabelEquals(
            $page,
            '#pagarme_order_info_payment_installments .label',
            $helper->__('Installments')
        );
        $this->assertFieldValue(
            $page,
            '#pagarme_order_info_payment_installments .value',
            $installments
        );
    }

    private function assertLabelEquals($page, $labelCss, $expectedLabel)
    {
        $label = $page->find(
            'css',
            $labelCss
        )->getText();

        \PHPUnit_Framework_TestCase::assertEquals(
            $expectedLabel,
            $label
        );
    }

    private function assertFieldValue($page, $fieldNameCss, $expectedValue)
    {
        $value = $page->find(
            'css',
            $fieldNameCss
        )->getText();

        \PHPUnit_Framework_TestCase::assertEquals(
            $expectedValue,
            $value
        );
    }
}
