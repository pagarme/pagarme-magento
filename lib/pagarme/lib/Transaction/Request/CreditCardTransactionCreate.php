<?php

namespace PagarMe\Sdk\Transaction\Request;

use PagarMe\Sdk\Transaction\CreditCardTransaction;

class CreditCardTransactionCreate extends TransactionCreate
{
    /**
     * @param CreditCardTransaction $transaction
     */
    public function __construct(CreditCardTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        $basicData = parent::getPayload();
        $cardData = [
            'installments' => $this->transaction->getInstallments(),
            'capture'      => $this->transaction->isCapturable()
        ];

        if (!is_null($this->transaction->getCardCvv())) {
            $cardData['card_cvv'] = $this->transaction->getCardCvv();
        }

        return array_merge($basicData, $cardData, $this->getCardInfo());
    }

    /**
     * @return array
     */
    private function getCardInfo()
    {
        if (!is_null($this->transaction->getCardId())) {
            return ['card_id' => $this->transaction->getCardId()];
        }

        if (!is_null($this->transaction->getCardHash())) {
            return ['card_hash' => $this->transaction->getCardHash()];
        }
    }
}
