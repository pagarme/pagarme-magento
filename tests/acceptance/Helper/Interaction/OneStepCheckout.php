<?php

namespace PagarMe\Magento\Test\Helper\Interaction;

trait OneStepCheckout
{
    private function getItemFromOrderReview($index)
    {
        return preg_replace(
            "/[^0-9,.]/",
            "",
            $this->getSession()->getPage()->find(
                'xpath',
                '//*[@class="onestepcheckout-cart-table"]//tfoot//tr['.$index.']//td//span'
            )
            ->getText()
        );
    }

    public function getSubTotal()
    {
        return $this->getItemFromOrderReview(1);
    }

    public function getShipping()
    {
        return $this->getItemFromOrderReview(2);
    }

    public function getInterestFee()
    {
        return $this->getItemFromOrderReview(3);
    }

    public function getTotal()
    {
        return $this->getItemFromOrderReview('last()');
    }
}
