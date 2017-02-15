<?php

namespace Pagarme\SdkTests\Transaction;

use PagarMe\Sdk\Transaction\TransactionHandler;
use PagarMe\Sdk\Transaction\AbstractTransaction;
use PagarMe\Sdk\Transaction\BoletoTransaction;
use PagarMe\Sdk\Transaction\CreditCardTransaction;

class TransactionBuilderTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\Transaction\TransactionBuilder;

    /**
     * @test
     */
    public function mustCreateCreditCardTransaction()
    {
        $transaction = $this->buildTransaction(
            json_decode(
                $this->creditCardTransactionCreateResponse()
            )
        );

        $this->assertInstanceOf(
            'PagarMe\Sdk\Transaction\CreditCardTransaction',
            $transaction
        );

        $this->assertInstanceOf('\DateTime', $transaction->getDateCreated());
        $this->assertInstanceOf('\DateTime', $transaction->getDateUpdated());
        $this->assertInstanceOf(
            'PagarMe\Sdk\SplitRule\SplitRuleCollection',
            $transaction->getSplitRules()
        );
        $this->assertEquals(
            CreditCardTransaction::PAYMENT_METHOD,
            $transaction->getPaymentMethod()
        );
    }

    /**
     * @test
     */
    public function mustCreateBoletoTransaction()
    {

        $transaction = $this->buildTransaction(
            json_decode(
                $this->boletoTransactionCreateResponse()
            )
        );

        $this->assertInstanceOf(
            'PagarMe\Sdk\Transaction\BoletoTransaction',
            $transaction
        );

        $this->assertInstanceOf('\DateTime', $transaction->getDateCreated());
        $this->assertInstanceOf('\DateTime', $transaction->getDateUpdated());
        $this->assertInstanceOf(
            'PagarMe\Sdk\SplitRule\SplitRuleCollection',
            $transaction->getSplitRules()
        );
        $this->assertEquals(
            BoletoTransaction::PAYMENT_METHOD,
            $transaction->getPaymentMethod()
        );
    }

    /**
     * @test
     * @expectedException PagarMe\Sdk\Transaction\UnsupportedTransaction
     */
    public function mustThrowUnsupportedTransaction()
    {
        $this->buildTransaction(json_decode('{"object":"transaction","payment_method":"other", "date_created":"2016-09-29T15:36:08.020Z","date_updated":"2016-09-29T17:48:34.600Z"}'));
    }

    public function creditCardTransactionCreateResponse()
    {
        return '{"object":"transaction","status":"processing","refuse_reason":null,"status_reason":"acquirer","acquirer_response_code":null,"authorization_code":null,"soft_descriptor":"testeDeAPI","tid":null,"nsu":null,"date_created":"2015-02-25T21:54:56.000Z","date_updated":"2015-02-25T21:54:56.000Z","amount":310000,"installments":5,"id":184220,"cost":0,"postback_url":"http://requestb.in/pkt7pgpk","payment_method":"credit_card","antifraud_score":null,"boleto_url":null,"boleto_barcode":null,"boleto_expiration_date":null,"referer":"api_key","ip":"189.8.94.42","subscription_id":null,"phone":null,"address":null,"customer":null,"card":{"object":"card","id":"card_ci6l9fx8f0042rt16rtb477gj","date_created":"2015-02-25T21:54:56.000Z","date_updated":"2015-02-25T21:54:56.000Z","brand":"mastercard","holder_name":"Api Customer","first_digits":"548045","last_digits":"3123","fingerprint":"HSiLJan2nqwn","valid":null},"split_rules":[{"object":"split_rule","id":"sr_cixi05w5w04erhx6dllaght6m","recipient_id":"re_cixi05vxt04ephx6dxm1y0esy","charge_processing_fee":true,"charge_remainder":false,"liable":true,"percentage":49,"amount":null,"date_created":"2017-01-03T21:03:56.948Z","date_updated":"2017-01-03T21:03:56.948Z"},{"object":"split_rule","id":"sr_cixi05w5v04eqhx6d4ala4v4b","recipient_id":"re_cixi05vt4053fmm6etx9e7h9f","charge_processing_fee":true,"charge_remainder":true,"liable":true,"percentage":51,"amount":null,"date_created":"2017-01-03T21:03:56.947Z","date_updated":"2017-01-03T21:03:56.947Z"}],"metadata":{"idProduto":"13933139"}}';
    }

    public function boletoTransactionCreateResponse()
    {
        return '{"object":"transaction","status":"waiting_payment","refuse_reason":null,"status_reason":"acquirer","acquirer_response_code":null,"acquirer_name":"pagarme","authorization_code":null,"soft_descriptor":null,"tid":733692,"nsu":733692,"date_created":"2016-09-26T19:14:35.119Z","date_updated":"2016-09-26T19:14:35.281Z","amount":3100,"authorized_amount":3100,"paid_amount":0,"refunded_amount":0,"installments":1,"id":733692,"cost":0,"card_holder_name":null,"card_last_digits":null,"card_first_digits":null,"card_brand":null,"postback_url":null,"payment_method":"boleto","capture_method":"ecommerce","antifraud_score":null,"boleto_url":"https://pagar.me","boleto_barcode":"1234 5678","boleto_expiration_date":"2016-10-03T03:00:00.117Z","referer":"api_key","ip":"187.11.121.49","subscription_id":null,"phone":null,"address":null,"customer":null,"card":null,"split_rules":[{"object":"split_rule","id":"sr_cixi05w5w04erhx6dllaght6m","recipient_id":"re_cixi05vxt04ephx6dxm1y0esy","charge_processing_fee":true,"charge_remainder":false,"liable":true,"percentage":49,"amount":null,"date_created":"2017-01-03T21:03:56.948Z","date_updated":"2017-01-03T21:03:56.948Z"},{"object":"split_rule","id":"sr_cixi05w5v04eqhx6d4ala4v4b","recipient_id":"re_cixi05vt4053fmm6etx9e7h9f","charge_processing_fee":true,"charge_remainder":true,"liable":true,"percentage":51,"amount":null,"date_created":"2017-01-03T21:03:56.947Z","date_updated":"2017-01-03T21:03:56.947Z"}],"metadata":{},"antifraud_metadata":{}}';
    }
}
