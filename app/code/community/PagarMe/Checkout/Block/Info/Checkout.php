<?php

class PagarMe_Checkout_Block_Info_Checkout extends Mage_Payment_Block_Info
{
    /**
     * @codeCoverageIgnore
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();

        $this->setTemplate('pagarme/checkout/order_info/payment_details.phtml');
    }

    /**
     * @codeCoverageIgnore
     *
     * @return PagarMe_Core_Model_Transaction
     */
    public function getTransaction()
    {
        return \Mage::getModel('pagarme_core/service_order')
            ->getTransactionByOrderId(
                $this->getInfo()
                    ->getOrder()
                    ->getId()
            );
    }
}
