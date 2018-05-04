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
        } catch(\Exception $exception) {
            throw new \Exception("$element not found");
        }
    }

    public function waitForElementXpath($element, $timeout, $page=null)
    {
        $waitTimeDelayInSeconds = 1;
        $waitedTimeInSeconds = 0;

        if (is_null($page)) {
            $page = $this->session->getPage();
        }
        do {
            try {
                if ($page->find('xpath', $element)) {
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

        throw new \Exception("Timeout for: ${$element}");
    }

}
