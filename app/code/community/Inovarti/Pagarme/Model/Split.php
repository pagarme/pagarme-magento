<?php

class Inovarti_Pagarme_Model_Split extends Inovarti_Pagarme_Model_AbstractSplit
{
    /**
     * @var
     */
    private $carrierAmount;

    /**
     * @var
     */
    private $carrierSplitAmount;

    /**
     * @var
     */
    private $recipientCarriers;

    /**
     * @var
     */
    private $orderFeeAmount;

    /**
     * @var
     */
    private $marketplaceRecipientId;

    /**
     * @param $quote
     * @return $this|array|bool
     */
    public function prepareSplit($quote)
    {
        if (!Mage::getStoreConfig('payment/pagarme_settings/marketplace_is_active'))
            return false;

        $splitRules = array();
        $marketplaceRecipientId = $this->getMarketplaceRecipientId();
        $amount = $quote->getGrandTotal();
        $carrierAmount = $quote->getShippingAddress()->getShippingInclTax();
        $itemsAmount = $amount - $carrierAmount;

        $websiteSplitRules = Mage::getModel('pagarme/splitRulesGroup')
            ->loadByWebsite(Mage::app()->getWebsite())
            ->getSplitRules();

        foreach($websiteSplitRules as $websiteSplitRule)
        {
            $websiteSplitRuleAmount = $itemsAmount * $websiteSplitRule->getAmount() / 100;

            if($websiteSplitRule->getRecipientId() == $marketplaceRecipientId)
                $websiteSplitRuleAmount += $carrierAmount;

            $splitRules[] = array(
                'recipient_id' => $websiteSplitRule->getRecipientId(),
                'charge_processing_fee' => $websiteSplitRule->getChargeProcessingFee(),
                'liable' => $websiteSplitRule->getLiable(),
                'amount' => $websiteSplitRuleAmount * 100
            );
        }

        return $splitRules;
    }

    /**
     * @param $baseSplitRules
     * @param $baseSplitRule
     * @param $recipientId
     * @return mixed
     */
    protected function splitItemsBetweenSellers($baseSplitRules, $baseSplitRule, $recipientId)
    {
        foreach ($baseSplitRule as $splitRule) {
            $recipientRule = $baseSplitRules['recipent_rules'][$recipientId];
            $recipientValue = $this->calculatePercetage($recipientRule->getAmount(), $splitRule['amount']);

            if (isset($splitRules[$recipientId])) {
                $lastedSplitRule    =  $splitRules[$recipientId];
                $currentAmount      = $splitRule['amount']-$recipientValue;

                $splitRules[$recipientId] = array(
                    'seller'                => $lastedSplitRule['seller'] + $recipientValue,
                    'fee_marketplace'       => $lastedSplitRule['fee_marketplace'] + $currentAmount,
                    'charge_processing_fee' => $recipientRule->getChargeProcessingFee(),
                    'liable'                => $recipientRule->getLiable()
                );
                continue;
            }

            $splitRules[$recipientId] = array(
                'seller' => $recipientValue,
                'fee_marketplace' => $splitRule['amount']-$recipientValue
            );
        }

        return $splitRules[$recipientId];
    }

    /**
     * @param $checkSplitItems
     * @param $quote
     * @return array
     */
    protected function getBaseSplitRules($checkSplitItems, $quote)
    {
        $recipientCarriers = array();
        $recipientRules = array();
        $splitRules = array();

        foreach ($checkSplitItems as $item) {
            $recipientId = $this->prepareRecipientId($item);

            if (!$recipientRules[$recipientId]) {
                $recipientRule = $this->getSplitRuleByRecipientId($item->getRecipientId());

                if ($recipientRule->getShippingCharge()) {
                    array_push($recipientCarriers, $item->getRecipientId());
                }

                $recipientRules[$recipientId] = $recipientRule;
            }

            $splitRules[$recipientId][] = array(
                'sku'                     => $item->getSku(),
                'amount'                  => ($item->getPrice() * $item->getQty()),
                'charge_processing_fee'   => ($recipientRule->getLiable() == true),
                'liable'                  => ($recipientRule->getChargeProcessingFee() == true)
            );
        }

        $this->setRecipientCarriers($recipientCarriers);
        $this->setNumberRecipientsFeeAmount($recipientCarriers);

        $this->setOrderFeeAmount($recipientCarriers, $quote);

        return array(
            'base_split_rules' => $splitRules,
            'recipent_rules' => $recipientRules
        );
    }
}
