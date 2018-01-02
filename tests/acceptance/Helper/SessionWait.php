<?php
namespace PagarMe\Magento\Test\Helper;

trait SessionWait
{
    public function waitForElement($element, $timeout)
    {
        $this->session->wait(
            $timeout,
            "document.querySelector('${element}').style.display != 'none'"
        );
    }
}
