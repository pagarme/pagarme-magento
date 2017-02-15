<?php

namespace PagarMe\SdkTest\Payable;

class PayableBuilderTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\Payable\PayableBuilder;

    /**
     * @test
     */
    public function mustCreatePayableCorrectly()
    {
        $payload = '{"object":"payable","id":441944,"status":"paid","amount":50000,"fee":190,"anticipation_fee":0,"installment":null,"transaction_id":994229,"split_rule_id":"sr_cix9cgluu018s9k6estsuiqh7","bulk_anticipation_id":null,"recipient_id":"re_cix9cglnu018r9k6ec57ocmse","payment_date":"2016-12-28T02:00:00.994Z","original_payment_date":null,"type":"credit","payment_method":"boleto","date_created":"2016-12-28T19:38:17.079Z"}';

        $payable = $this->buildPayable(json_decode($payload));

        $this->assertInstanceOf('\PagarMe\Sdk\Payable\Payable', $payable);
        $this->assertInstanceOf('\DateTime', $payable->getPaymentDate());
        $this->assertInstanceOf('\DateTime', $payable->getDateCreated());
    }
}
