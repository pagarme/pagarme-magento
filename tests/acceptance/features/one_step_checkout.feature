Feature: One Step Checkout Pagar.me
    As a customer
    I want use PagarMe Checkout
    And One Step Checkout
    To make purchase

    @OSC
    Scenario: Make a purchase by boleto without discount
        Given I am on checkout page using Inovarti One Step Checkout
        When I confirm payment
        Then the purchase must be created with success
        And a link to boleto must be provided
