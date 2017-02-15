<?php

namespace PagarMe\Sdk\Transaction\Request;

use PagarMe\Sdk\RequestInterface;
use PagarMe\Sdk\Transaction\Transaction;
use PagarMe\Sdk\SplitRule\SplitRuleCollection;

class TransactionCreate implements RequestInterface
{
    /**
     * @var \PagarMe\Sdk\Transaction\Transaction
     */
    protected $transaction;

    /**
     * @param \PagarMe\Sdk\Transaction\Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        $customer = $this->transaction->getCustomer();

        $address = $customer->getAddress();
        if (is_array($address)) {
            $address = new \PagarMe\Sdk\Customer\Address($address);
        }
        $phone = $customer->getPhone();
        if (is_array($phone)) {
            $phone = new \PagarMe\Sdk\Customer\Phone($phone);
        }

        $transactionData = [
            'amount'         => $this->transaction->getAmount(),
            'payment_method' => $this->transaction->getPaymentMethod(),
            'postback_url'   => $this->transaction->getPostbackUrl(),
            'customer' => [
                'name'            => $customer->getName(),
                'document_number' => $customer->getDocumentNumber(),
                'email'           => $customer->getEmail(),
                'sex'             => $customer->getGender(),
                'born_at'         => $customer->getBornAt(),
                'address' => [
                    'street'        => $address->getStreet(),
                    'street_number' => $address->getStreetNumber(),
                    'complementary' => $address->getComplementary(),
                    'neighborhood'  => $address->getNeighborhood(),
                    'zipcode'       => $address->getZipcode()
                ],
                'phone' => [
                    'ddd'    => (string) $phone->getDdd(),
                    'number' => (string) $phone->getNumber()
                ]
            ],
            'metadata' => $this->transaction->getMetadata()
        ];

        if ($this->transaction->getSplitRules() instanceof SplitRuleCollection) {
            $transactionData['split_rules'] = $this->getSplitRulesInfo(
                $this->transaction->getSplitRules()
            );
        }

        return $transactionData;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return 'transactions';
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_POST;
    }

    /**
     * @param \PagarMe\Sdk\SplitRule\SplitRuleCollection $splitRules
     * @return array
     */
    private function getSplitRulesInfo(SplitRuleCollection $splitRules)
    {
        $rules = [];

        foreach ($splitRules as $key => $splitRule) {
            $rule = [
                'recipient_id'          => $splitRule->getRecipient()->getId(),
                'charge_processing_fee' => $splitRule->getChargeProcessingFee(),
                'liable'                => $splitRule->getLiable()
            ];

            $rules[$key] = array_merge($rule, $this->getRuleValue($splitRule));
        }

        return $rules;
    }

    /**
     * @param \PagarMe\Sdk\SplitRule\SplitRule $splitRule
     * @return array
     */
    private function getRuleValue($splitRule)
    {
        if (!is_null($splitRule->getAmount())) {
            return ['amount' => $splitRule->getAmount()];
        }

        return ['percentage' => $splitRule->getPercentage()];
    }
}
