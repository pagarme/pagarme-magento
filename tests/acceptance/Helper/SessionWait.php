<?php
namespace PagarMe\Magento\Test\Helper;

trait SessionWait
{
    public function waitForElement($element, $timeout)
    {
        try {
            $this->session->wait(
                $timeout,
                "document.querySelector('${element}').style.display != 'none'"
            );
        } catch (\Exception $exception) {
            var_dump($element . ' not found');
        }
    }


    public function waitForElementType(
        $element,
        $timeout,
        $page=null,
        $type = 'css'
    )
    {
        $waitTimeDelayInSeconds = 1;
        $waitedTimeInSeconds = 0;
        if (is_null($page)) {
            $page = $this->session->getPage();
        }
        do {
            try {
                if ($page->find($type, $element)) {
                    return true;
                }
            } catch (Exception $e) {
                if($element == '#p_method_pagarme_creditcard')
                    throw new \Exception('Element not found');
            }
            sleep($waitTimeDelayInSeconds);
            $waitedTimeInSeconds += $waitTimeDelayInSeconds;
            $waitedEnough = $waitedTimeInSeconds >= $timeout;
        } while(!$waitedEnough);
        throw new \Exception("Timeout for: ".$element);
    }

    public function waitForElementXpath($element, $timeout, $page=null)
    {
        $this->waitForElementType(
            $element,
            $timeout,
            $page,
            'xpath'
        );
    }

}
