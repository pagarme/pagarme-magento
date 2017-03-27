<?php

use Behat\MinkExtension\Context\RawMinkContext;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../../vendor/autoload.php';

class OrderViewContext extends RawMinkContext
{
    const PAYMENT_METHOD_CREDIT_CARD_LABEL = 'Cartão de crédito';
    const PAYMENT_METHOD_BOLETO_LABEL = 'Boleto';
    
    /**
     * @When navigate to the Order page
     */
    public function andNavigateToTheOrderPage()
    {
        $session = $this->getSession();
        $page = $session->getPage();

        $popup = $page->find('css', '.message-popup-head a');

        if ($popup instanceof \Behat\Mink\Element\NodeElement) {
            $popup->click();
        }

        $page->find('named', array('link', 'Sales'))
            ->mouseOver();

        $page->find('named', array('link', 'Orders'))
            ->click();
    }

    /**
     * @When click on the last created Order
     */
    public function andClickOnTheLastCreatedOrder()
    {
        $session = $this->getSession();
        $page = $session->getPage();

        $page->find('css', '#sales_order_grid_table tbody tr td a')->click();
    }

    /**
     * @Then I see that the interest rate information for :paymentMethod is present
     */
    public function thenISeeThatTheInterestRateValueIsPresent($paymentMethod)
    {
        $session = $this->getSession();
        $page = $session->getPage();

        $element = $page->find('css', '#pagarme_order_info_payment_details');

        \PHPUnit_Framework_TestCase::assertInstanceOf(
            'Behat\Mink\Element\NodeElement',
            $element
        );

        $htmlContent = $element->getHtml();

        if ($paymentMethod === self::PAYMENT_METHOD_CREDIT_CARD_LABEL) {
            \PHPUnit_Framework_TestCase::assertContains(
                'Installments',
                $htmlContent
            );
        }

        \PHPUnit_Framework_TestCase::assertContains(
            $paymentMethod,
            $htmlContent
        );

        \PHPUnit_Framework_TestCase::assertContains(
            'Interest Fee',
            $htmlContent
        );

        \PHPUnit_Framework_TestCase::assertContains(
            'Transaction Id',
            $htmlContent
        );
    }
}
