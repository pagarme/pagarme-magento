<?php

namespace PagarMe\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;

class CardContext extends BasicContext
{
    private $createdCard;
    private $queryCard;

    private $number;
    private $holder;
    private $expiration;

    /**
     * @Given a card with :number, :holder and :expiration
     */
    public function aCardWithAnd($number, $holder, $expiration)
    {
        $this->number     = $number;
        $this->holder     = $holder;
        $this->expiration = $expiration;
    }

    /**
     * @When register the card
     */
    public function registerTheCard()
    {
        $this->createdCard = self::getPagarMe()
            ->card()
            ->create(
                $this->number,
                $this->holder,
                $this->expiration
            );
    }

    /**
     * @Then should have a card starting with :start and ending with :end
     */
    public function iShouldHaveACardStartingWithAndEndingWith($start, $end)
    {
        assertEquals($start, $this->createdCard->getFirstDigits());
        assertEquals($end, $this->createdCard->getLastDigits());
    }

    /**
     * @When query for the card
     */
    public function iQueryForCard()
    {
        $cardId = $this->createdCard->getId();

        $this->queryCard = self::getPagarMe()
            ->card()
            ->get($cardId);
    }

    /**
     * @Then should have the same card
     */
    public function iShouldHaveTheSameCard()
    {
        assertEquals($this->createdCard->getId(), $this->queryCard->getId());
    }
}
