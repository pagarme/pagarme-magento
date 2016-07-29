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
        if (!Mage::getStoreConfig('payment/pagarme_settings/marketplace_is_active')
            || !Mage::getStoreConfig('payment/pagarme_settings/marketplace_recipient_id')
            || !$this->getMarketplaceRecipientId()) {
            return false;
        }

        $this->marketplaceRecipientId = $this->getMarketplaceRecipientId();
        $this->carrierAmount = $quote->getShippingAddress()->getShippingInclTax();

        $splitItems = $this->getSplitItems($quote->getId());

        if (!$this->checkSplitItems($splitItems)) {
            return false;
        }

        $baseSplitRules = $this->getBaseSplitRules($quote->getItemsCollection(), $quote);
        $splitRules     = $this->getSplitRules($baseSplitRules);

        $splitRule = array();
        $splitRuleMarketplace = array();

        foreach ($splitRules as $recipientId => $splitData) {

            $splitAmount = $this->getAmount($recipientId, $splitData['seller']);

            if ($splitAmount) {

                $amount = Mage::helper('pagarme')->formatAmount($splitAmount + $this->orderFeeAmount);
                $splitRule[] = array(
                    'recipient_id'          => $recipientId,
                    'charge_processing_fee' => $splitData['charge_processing_fee'],
                    'liable'                => $splitData['liable'],
                    'amount'                => $amount
                );
            }

            if ($splitRuleMarketplace[$this->marketplaceRecipientId]) {

                $currentAmount  = $splitRuleMarketplace;
                $amount         = $currentAmount['amount'] + $splitData['fee_marketplace'];

                $splitRuleMarketplace[$splitRuleMarketplace['amount']] = $amount;
                continue;
            }

            $marketplaceAmount = $this->getMarketplaceAmount($recipientId,$splitData);

            $splitRuleMarketplace[$this->marketplaceRecipientId] = array(
                'recipient_id'          => $this->marketplaceRecipientId,
                'charge_processing_fee' => $splitData['charge_processing_fee'],
                'liable'                => $splitData['liable'],
                'amount' => $marketplaceAmount
            );
        }

        $amount = Mage::helper('pagarme')->formatAmount($splitRuleMarketplace[$this->marketplaceRecipientId]['amount'] + $this->orderFeeAmount + $this->getMarketplaceSplitItemsAmount($quote->getId()));

        $splitRuleMarketplace[$this->marketplaceRecipientId]['amount'] = $amount;
        $splitRule[] = $splitRuleMarketplace[$this->marketplaceRecipientId];

        return $splitRule;
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

                $recipientRule = $this->getFirstSplitRule($item);

                if ($recipientRule->getShippingCharge()) {
                    array_push($recipientCarriers, $item->getRecipientId());
                }

                $recipientRules[$recipientId] = $recipientRule;
            }

            if (isset($splitRules[$recipientId])) {

                $splitRules[$recipientId][] = array(
                    'sku' => $item->getSku(),
                    'amount' => ($item->getPrice() * $item->getQty())
                );
                continue;
            }

            $splitRules[$recipientId][] = array(
                'sku' => $item->getSku(),
                'amount' => ($item->getPrice() * $item->getQty())
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