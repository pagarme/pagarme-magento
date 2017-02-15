<?php

namespace PagarMe\SdkTest\SplitRule;

use PagarMe\Sdk\SplitRule\SplitRuleBuilder;

class SplitRuleBuilderTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\SplitRule\SplitRuleBuilder;

    /**
     * @test
     */
    public function mustSplitRuleCollectionCreatedCorrectly()
    {
        $spliruleCollection = $this->buildSplitRules(json_decode('[{"object":"split_rule","id":"sr_cixi05w5w04erhx6dllaght6m","recipient_id":"re_cixi05vxt04ephx6dxm1y0esy","charge_processing_fee":true,"charge_remainder":false,"liable":true,"percentage":49,"amount":null,"date_created":"2017-01-03T21:03:56.948Z","date_updated":"2017-01-03T21:03:56.948Z"},{"object":"split_rule","id":"sr_cixi05w5v04eqhx6d4ala4v4b","recipient_id":"re_cixi05vt4053fmm6etx9e7h9f","charge_processing_fee":true,"charge_remainder":true,"liable":true,"percentage":51,"amount":null,"date_created":"2017-01-03T21:03:56.947Z","date_updated":"2017-01-03T21:03:56.947Z"}]'));

        $this->assertInstanceOf(
            'PagarMe\Sdk\SplitRule\SplitRuleCollection',
            $spliruleCollection
        );

        $this->assertInstanceOf(
            'PagarMe\Sdk\SplitRule\SplitRule',
            $spliruleCollection[0]
        );

        $this->assertInstanceOf('PagarMe\Sdk\SplitRule\SplitRule', $spliruleCollection[0]);
        $this->assertInstanceOf('PagarMe\Sdk\Recipient\Recipient', $spliruleCollection[0]->getRecipient());
        $this->assertInstanceOf(
            '\DateTime',
            $spliruleCollection[0]->getDateCreated()
        );
        $this->assertInstanceOf(
            '\DateTime',
            $spliruleCollection[0]->getDateUpdated()
        );
    }
}
