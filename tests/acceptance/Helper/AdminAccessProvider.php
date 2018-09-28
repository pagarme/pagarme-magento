<?php

namespace PagarMe\Magento\Test\Helper;

trait AdminAccessProvider
{
    public function loginOnAdmin($adminUser)
    {
        $session = $this->getSession();
        $session->visit(getenv('MAGENTO_URL') . 'index.php/admin');
        $page = $session->getPage();

        if ($page->find('css', '.link-logout')) {
            return true;
        }

        $inputLogin = $page->find('named', array('id', 'username'));
        $inputLogin->setValue($adminUser->getUsername());

        $inputPassword = $page->find('named', array('id', 'login'));
        $inputPassword->setValue($this->getAdminPassword());

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
