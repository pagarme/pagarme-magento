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
     * @var
     */
    private $splitRules = array();

    /**
     * @param $quote
     * @return $this|array|bool
     */
    public function prepareSplit($quote)
    {
        if (!Mage::getStoreConfig('payment/pagarme_settings/marketplace_is_active')) {
            return false;
        }

        $productSplitRules = array();
        $marketplaceRecipientId = $this->getMarketplaceRecipientId();
        $amount = $quote->getGrandTotal();
        $shippingFee = $quote->getShippingAddress()->getShippingInclTax();
        $cartAmount = $amount - $shippingFee;

        $websiteSplitRules = $this->prepareWebsiteSplitrules(Mage::app()->getWebsite(), $cartAmount, $shippingFee, $productSplitRules);
        $splitRules = $this->addShippingFeeToSplitRules($websiteSplitRules, $shippingFee);


        return array_values($splitRules);
    }

    /**
     *
     *
     */
    private function prepareProductSplitrules($quote, $cartAmount, $splitRules = array())
    {
        $splitRules = array_merge(array(), $splitRules);
        $baseSplitRules = $this->getBaseSplitRules($quote->getItemsCollection(), $quote);
        $splitRulesData      = $this->getSplitRules($baseSplitRules);

        foreach ($splitRulesData as $recipientId => $splitRuleData) {
            $splitAmount = Mage::helper('pagarme')->formatAmount($this->getAmount($recipientId, $splitRuleData['seller']));

            if ($splitAmount <= 0) {
                continue;
            }

            if (!isset($splitRules[$recipientId])) {
                $splitRules[$recipientId] = array(
                    'recipient_id'           => $recipientId,
                    'charge_processing_fee'  => $splitRuleData['charge_processing_fee'],
                    'liable'                 => $splitRuleData['liable'],
                    'amount'                 => $splitAmount
                );
            } else {
                $splitRules[$recipientId]['charge_processing_fee'] = $this->splitRules[$recipientId]['charge_processing_fee'] || $splitRuleData['charge_processing_fee'];
                $splitRules[$recipientId]['liable'] = $this->splitRules[$recipientId]['liable'] || $splitRuleData['liable'];
                $splitRules[$recipientId]['amount'] += $amount;
            }
        }

        return $splitRules;
    }

    /**
     *
     * @param Mage_Core_Model_Webiste $website
     * @param $amount
     */
    private function prepareWebsiteSplitrules(Mage_Core_Model_Website $website, $amount, $shippingFee, $splitRules = array())
    {
        $splitRules = array_merge(array(), $splitRules);
        $websiteSplitRules = Mage::getModel('pagarme/splitRulesGroup')
            ->loadByWebsite($website)
            ->getSplitRules();

        foreach ($websiteSplitRules as $websiteSplitRule) {
            $splitRuleAmount = Mage::helper('pagarme')->formatAmount($websiteSplitRule->getAmount() / 100 * $amount);

            $recipientId = $websiteSplitRule->getRecipientId();

            if (!isset($splitRules[$recipientId])) {
                $splitRules[$recipientId] = array(
                    'recipient_id'           => $recipientId,
                    'charge_processing_fee'  => $websiteSplitRule->getChargeProcessingFee(),
                    'liable'                 => $websiteSplitRule->getLiable(),
                    'amount'                 => $splitRuleAmount
                );
            } else {
                $splitRules[$recipientId]['charge_processing_fee'] = $splitRules[$recipientId]['charge_processing_fee'] || ($websiteSplitRule->getChargeProcessingFee() == true);
                $splitRules[$recipientId]['liable'] = $splitRules[$recipientId]['liable'] || $websiteSplitRule->getLiable();
                $splitRules[$recipientId]['amount'] += $splitRuleAmount;
            }
        }

        return $splitRules;
    }

    private function addShippingFeeToSplitRules($splitRules = array(), $shippingFee)
    {
        $formatedShippingFee = Mage::helper('pagarme')->formatAmount($shippingFee);

        if(isset($splitRules[$this->getMarketplaceRecipientId()])) {
            $splitRules[$this->getMarketplaceRecipientId()]['amount'] += $formatedShippingFee;
        } else {
            $splitRules[$this->getMarketplaceRecipientId()] = array(
                'recipient_id'           => $this->getMarketplaceRecipientId(),
                'charge_processing_fee'  => (Mage::app()->getWebsite()->getConfig('payment/pagarme_settings/charge_processing_fee') == true),
                'liable'                 => (Mage::app()->getWebsite()->getConfig('payment/pagarme_settings/liable') == true),
                'amount'                 => $formatedShippingFee
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
