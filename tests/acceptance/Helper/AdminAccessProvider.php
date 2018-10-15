<?php

namespace PagarMe\Magento\Test\Helper;

trait AdminAccessProvider
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

    public function goToSystemSettings()
    {
        $session = $this->getSession();
        $page = $session->getPage();

        $popup = $page->find('css', '.message-popup-head a');

        if ($popup instanceof \Behat\Mink\Element\NodeElement) {
            $popup->click();
        }

        $page->find('named', array('link', 'System'))
            ->mouseOver();

        $page->find('named', array('link', 'Configuration'))
            ->click();
    }
}
