<?php

namespace PagarMe\Magento\Test\CreditCard;

trait AdminInterestRateCheck
{
    /**
     * @When I login to the admin
     */
    public function iLoginToTheAdmin()
    {
        $this->session
            ->visit($this->magentoUrl . '/admin');

        $page = $this->session->getPage();
        $this->waitForElement('#username', 2000);
        $page->fillField(
            \Mage::helper('pagarme_modal')->__('Password'),
            'magentorocks1'
        );
        $page->fillField(
            \Mage::helper('pagarme_modal')->__('User Name'),
            'admin'
        );
        $page->pressButton('Login');
    }

    /**
     * @When I check the order interest amount in its admin detail page
     */
    public function iCheckTheOrderInterestAmountInItsAdminDetailPage()
    {
        $order = \Mage::getModel('sales/order')
            ->load($this->createdOrderId, 'increment_id');
        $this->session
            ->visit(
                $this->magentoUrl . 'admin/sales_order/view/order_id/' . $order->getId()
            );
    }

    /**
     * @Then the admin interest value should consider the values :installments and :interestRate
     */
    public function theAdminInterestValueShouldConsiderTheValuesAnd(
        $installments,
        $interestRate
    ) {
        $interestAmountLabel = \Mage::helper('pagarme_creditcard')
            ->__("Installments related Interest");
        $interestAmountElementXpath = "//td[contains(text(), '${interestAmountLabel}')]/following-sibling::td/span[@class='price']";
        $page = $this->session->getPage();
        $this->waitForElementXpath(
            $interestAmountElementXpath,
            5
        );
        $interestAmount = $page
            ->find(
                'xpath',
                $interestAmountElementXpath
            )->getText();

        \PHPUnit_Framework_TestCase::assertEquals(
            $interestAmount,
            'R$11.22'
        );

    }
}
