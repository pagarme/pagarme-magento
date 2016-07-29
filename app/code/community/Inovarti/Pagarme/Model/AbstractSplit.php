<?php

abstract class Inovarti_Pagarme_Model_AbstractSplit extends Mage_Payment_Model_Method_Abstract
{
    const API_ADDRESS = 'https://api.pagar.me/1/';
    const COMPANY_ENDPOINT = 'company';

    /**
     * @return mixed
     */
    protected function getMarketplaceRecipientId()
    {
        $api = Mage::getModel('pagarme/api');

        $data = new Varien_Object();
        $data->setApiKey(Mage::helper('pagarme')->getApiKey());

        $company = $api->request(self::API_ADDRESS.self::COMPANY_ENDPOINT, $data);
        $defaultRecipientIds = $company->getDefaultRecipientId();

        $mode = Mage::helper('pagarme')->getMode();

        return $defaultRecipientIds[$mode];
    }

    /**
     * @param $recipientCarriers
     * @param $quote
     */
    protected function setOrderFeeAmount($recipientCarriers, $quote)
    {
        $numberRecipientsFeeAmount = (count($recipientCarriers) > 1)? count($recipientCarriers) : 2;
        $this->orderFeeAmount = $quote->getFeeAmount() / $numberRecipientsFeeAmount;
    }

    /**
     * @param $recipientCarriers
     * @return $this
     */
    protected function setNumberRecipientsFeeAmount($recipientCarriers)
    {
        $this->carrierSplitAmount = $this->carrierAmount / count($recipientCarriers);
        return $this;
    }

    /**
     * @param $recipientCarriers
     */
    protected function setRecipientCarriers($recipientCarriers)
    {
        $this->recipientCarriers = $recipientCarriers;
        return $this;
    }

    /**
     * @param $item
     * @return mixed
     */
    protected function getFirstSplitRule($item)
    {
        return Mage::getModel('pagarme/splitRules')
            ->getCollection()
            ->addFieldToFilter('recipient_id', $item->getRecipientId())
            ->getFirstItem();
    }

    /**
     * @param $item
     * @return mixed
     */
    protected function prepareRecipientId($item)
    {
        return ($item->getRecipientId())? $item->getRecipientId() : $this->marketplaceRecipientId;
    }

    /**
     * @param $quoteId
     * @return mixed
     */
    protected function getSplitItems($quoteId)
    {
        return Mage::getModel('sales/quote_item')
            ->getCollection()
            ->addFieldToFilter('quote_id', $quoteId)
            ->addFieldToFilter('recipient_id', array('notnull' => true));
    }

    /**
     * @param $quoteId
     * @return int
     */
    protected function getMarketplaceSplitItemsAmount($quoteId)
    {
        $amount = 0;
        $splitItems = $this->getSplitItemsNull($quoteId);

        if (!$splitItems->getData()) {
            return $amount;
        }

        foreach ($this->getSplitItemsNull($quoteId)->getData() as $item) {
            $amount = $amount + ($item['price'] * $item['qty']);
        }

        return $amount;
    }

    /**
     * @param $quoteId
     * @return mixed
     */
    protected function getSplitItemsNull($quoteId)
    {
        return Mage::getModel('sales/quote_item')
            ->getCollection()
            ->addFieldToFilter('quote_id', $quoteId)
            ->addFieldToFilter('recipient_id', array('null' => true));
    }

    /**
     * @param $checkSplitItems
     * @return bool
     */
    protected function checkSplitItems($checkSplitItems)
    {
        if ($checkSplitItems->getData()) {
            return true;
        }
        return false;
    }

    /**
     * @param $recipientId
     * @param $amount
     * @return mixed
     */
    protected function getAmount($recipientId, $amount)
    {
        if (in_array($recipientId,$this->recipientCarriers)) {
            return $amount + $this->carrierSplitAmount;
        }

        return $amount;
    }

    /**
     * @param $percetage
     * @param $total
     * @return float
     */
    protected function calculatePercetage($percetage, $total)
    {
        return ($percetage / 100) * $total;
    }

    /**
     * @param $recipientId
     * @param $splitData
     * @return mixed
     */
    protected function getMarketplaceAmount($recipientId, $splitData)
    {
        if (count($this->recipientCarriers) === 1 && !in_array($recipientId,$this->recipientCarriers)) {
            return $splitData['fee_marketplace'] + $this->carrierSplitAmount;
        }
        
        return $splitData['fee_marketplace'];
    }

    /**
     * @param $baseSplitRules
     * @return array
     */
    protected function getSplitRules($baseSplitRules)
    {
        $splitRules = array();
        foreach ($baseSplitRules['base_split_rules'] as $recipientId => $baseSplitRule) {
            $splitRules[$recipientId] = $this->splitItemsBetweenSellers($baseSplitRules, $baseSplitRule, $recipientId);
        }

        return $splitRules;
    }
}