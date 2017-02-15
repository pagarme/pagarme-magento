Feature: Checkout Pagar.me
    Like a seller that use Magento
    I want to allow that purchases must be paid by credit card
    To make sales

    Scenario: Make a purchase by credit card
        Given a registered user
        And a valid credit card
        When I access the store page
        And add any product to basket
        And I go to checkout page
        And login with registered user
        And confirm billing and shipping address information
        And I use a valid credit card to pay
        Then the purchase must be paid with success

