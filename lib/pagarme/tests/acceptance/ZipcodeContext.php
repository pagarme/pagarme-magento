<?php

namespace PagarMe\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

class ZipcodeContext extends BasicContext
{
    private $zipcodeInfo;
    private $zipcode;
    /**
     * @Given a zipcode :zipcode
     */
    public function aZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
    }

    /**
     * @When I query for the CEP
     */
    public function iQueryForTheCep()
    {
        $this->zipcodeInfo = self::getPagarMe()
            ->zipcode()
            ->getInfo($this->zipcode);
    }

    /**
     * @Then at least city, state and zipcode must be returned
     */
    public function atLeastCityStateAndZipcodeMustBeReturned()
    {
        assertInternalType('string', $this->zipcodeInfo->city);
        assertInternalType('string', $this->zipcodeInfo->state);
        assertInternalType('string', $this->zipcodeInfo->zipcode);
        assertObjectHasAttribute('neighborhood', $this->zipcodeInfo);
        assertObjectHasAttribute('street', $this->zipcodeInfo);
    }
}
