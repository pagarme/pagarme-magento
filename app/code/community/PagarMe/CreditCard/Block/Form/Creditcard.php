<?php

use PagarMe_Core_Model_CurrentOrder as CurrentOrder;

class PagarMe_Creditcard_Block_Form_CreditCard extends Mage_Payment_Block_Form_Cc
{
    use PagarMe_Core_Trait_ConfigurationsAccessor;

    const TEMPLATE = 'pagarme/form/credit_card.phtml';

    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate(self::TEMPLATE);
    }

    /**
     * @return array
     */
    public function getInstallments()
    {
        $quote = Mage::helper('checkout')->getQuote();
        $pagarMeSdk = Mage::getModel('pagarme_core/sdk_adapter');
        $currentOrder = new CurrentOrder(
            $quote,
            $pagarMeSdk
        );

        $maxInstallments = $this->getMaxInstallmentsByMinimumAmount(
            $currentOrder->productsTotalValueInBRL()
        );

        return $currentOrder->calculateInstallments(
            $maxInstallments,
            $this->getFreeInstallmentStoreConfig(),
            $this->getInterestRateStoreConfig()
        );
    }

    /**
     * @param float $orderTotal
     *
     * @return int
     */
    public function getMaxInstallmentsByMinimumAmount($orderTotal)
    {
        $minInstallmentAmount = $this->getMinInstallmentValueStoreConfig();

        $maxInstallmentsConfig = $this->getMaxInstallmentStoreConfig();

        if ($minInstallmentAmount <= 0) {
            return $this->getMaxInstallmentStoreConfig();
        }

        $installmentsNumber = floor($orderTotal / $minInstallmentAmount);

        $maxInstallments = $installmentsNumber ? $installmentsNumber : 1;

        if ($maxInstallments > $this->getMaxInstallmentStoreConfig()) {
            return $this->getMaxInstallmentStoreConfig();
        }

        return $maxInstallments;
    }
}
