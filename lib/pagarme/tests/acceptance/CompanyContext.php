<?php

namespace PagarMe\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

class CompanyContext extends BasicContext
{
    private $info;
    private $companyClient;

    /**
     * @Given a configured client
     */
    public function aConfiguredClient()
    {
        assertInstanceOf(
            'PagarMe\Sdk\PagarMe',
            self::getPagarMe()
        );
    }

    /**
     * @When I query for company information
     */
    public function iQueryForCompanyInformation()
    {
        $this->info = self::getPagarMe()
            ->company()
            ->info();
    }

    /**
     * @Then company information must be obtained
     */
    public function companyInformationMustBeObtained()
    {
        assertEquals('company', $this->info->object);
    }
}
