<?php

namespace PagarMe\Sdk\Postback;

class PayloadBuilderTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\Postback\PayloadBuilder;

    /**
     * @test
     */
    public function mustCreatePayloadCorrectly()
    {
        $response = 'id=1003499&fingerprint=2de7149a5337510d3ff7eb22a36483efd1db52a6&event=transaction_status_changed&old_status=processing&desired_status=paid&current_status=paid&object=transaction&transaction%5Bobject%5D=transaction&transaction%5Bstatus%5D=paid&transaction%5Brefuse_reason%5D=&transaction%5Bstatus_reason%5D=acquirer&transaction%5Bacquirer_response_code%5D=0000&transaction%5Bacquirer_name%5D=pagarme&transaction%5Bacquirer_id%5D=57ab081b0450e4bd51d52af8&transaction%5Bauthorization_code%5D=491541&transaction%5Bsoft_descriptor%5D=&transaction%5Btid%5D=1003499&transaction%5Bnsu%5D=1003499&transaction%5Bdate_created%5D=2016-12-30T17%3A18%3A05.779Z&transaction%5Bdate_updated%5D=2016-12-30T17%3A18%3A06.210Z&transaction%5Bamount%5D=7959&transaction%5Bauthorized_amount%5D=7959&transaction%5Bpaid_amount%5D=7959&transaction%5Brefunded_amount%5D=0&transaction%5Binstallments%5D=10&transaction%5Bid%5D=1003499&transaction%5Bcost%5D=50&transaction%5Bcard_holder_name%5D=Joao%20Silva&transaction%5Bcard_last_digits%5D=7271&transaction%5Bcard_first_digits%5D=516619&transaction%5Bcard_brand%5D=mastercard&transaction%5Bcard_pin_mode%5D=&transaction%5Bpostback_url%5D=http%3A%2F%2Feduardo.com&transaction%5Bpayment_method%5D=credit_card&transaction%5Bcapture_method%5D=ecommerce&transaction%5Bantifraud_score%5D=&transaction%5Bboleto_url%5D=&transaction%5Bboleto_barcode%5D=&transaction%5Bboleto_expiration_date%5D=&transaction%5Breferer%5D=api_key&transaction%5Bip%5D=187.11.121.49&transaction%5Bsubscription_id%5D=&transaction%5Bphone%5D%5Bobject%5D=phone&transaction%5Bphone%5D%5Bddi%5D=55&transaction%5Bphone%5D%5Bddd%5D=11&transaction%5Bphone%5D%5Bnumber%5D=999887766&transaction%5Bphone%5D%5Bid%5D=65601&transaction%5Baddress%5D%5Bobject%5D=address&transaction%5Baddress%5D%5Bstreet%5D=rua%20qualquer%2C&transaction%5Baddress%5D%5Bcomplementary%5D=apto%2C&transaction%5Baddress%5D%5Bstreet_number%5D=13%2C&transaction%5Baddress%5D%5Bneighborhood%5D=pinheiros%2C&transaction%5Baddress%5D%5Bcity%5D=S%C3%A3o%20Paulo&transaction%5Baddress%5D%5Bstate%5D=SP&transaction%5Baddress%5D%5Bzipcode%5D=05444040&transaction%5Baddress%5D%5Bcountry%5D=Brasil&transaction%5Baddress%5D%5Bid%5D=67895&transaction%5Bcustomer%5D%5Bobject%5D=customer&transaction%5Bcustomer%5D%5Bdocument_number%5D=78863832064&transaction%5Bcustomer%5D%5Bdocument_type%5D=cpf&transaction%5Bcustomer%5D%5Bname%5D=Joao%20Silva&transaction%5Bcustomer%5D%5Bemail%5D=user586696cda239d%40email.com&transaction%5Bcustomer%5D%5Bborn_at%5D=1970-01-01T04%3A11%3A11.991Z&transaction%5Bcustomer%5D%5Bgender%5D=M&transaction%5Bcustomer%5D%5Bdate_created%5D=2016-12-28T19%3A28%3A05.344Z&transaction%5Bcustomer%5D%5Bid%5D=122229&transaction%5Bcard%5D%5Bobject%5D=card&transaction%5Bcard%5D%5Bid%5D=card_cix93fm9g009hy36dszle5gpt&transaction%5Bcard%5D%5Bdate_created%5D=2016-12-28T15%3A25%3A33.941Z&transaction%5Bcard%5D%5Bdate_updated%5D=2016-12-30T15%3A58%3A35.544Z&transaction%5Bcard%5D%5Bbrand%5D=mastercard&transaction%5Bcard%5D%5Bholder_name%5D=Jose%20Silva&transaction%5Bcard%5D%5Bfirst_digits%5D=516619&transaction%5Bcard%5D%5Blast_digits%5D=7271&transaction%5Bcard%5D%5Bcountry%5D=HT&transaction%5Bcard%5D%5Bfingerprint%5D=g%2BFfwUFCT6dd&transaction%5Bcard%5D%5Bvalid%5D=true&transaction%5Bcard%5D%5Bexpiration_date%5D=1223&transaction%5Bsplit_rules%5D=&transaction%5Bmetadata%5D%5Bsource%5D=tests';

        $payload = $this->buildPayload($response);

        $this->assertInstanceOf('PagarMe\Sdk\Postback\Payload', $payload);
        $this->assertInstanceOf(
            'PagarMe\Sdk\Transaction\AbstractTransaction',
            $payload->getTransaction()
        );
    }
}
