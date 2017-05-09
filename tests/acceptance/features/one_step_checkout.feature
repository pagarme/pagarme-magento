Feature: One Step Checkout Pagar.me
    As a customer
    I want use PagarMe Checkout
    And One Step Checkout
    To make purchase

    Scenario: Make a purchase by boleto without discount
        Given I am on checkout page using Inovarti One Step Checkout
        When I confirm payment
        Then the purchase must be created with success
        And a link to boleto must be provided

    Scenario Outline: Make a purchase by boleto with fixed discount
        Given fixed "<boleto_discount>" discount for boleto payment is provided
        And I am on checkout page using Inovarti One Step Checkout
        When I confirm payment
        Then the absolute discount of "<boleto_discount>" must be informed on checkout
        Examples:
        | boleto_discount |
        | 10.5            |
        | 1.23            |

    Scenario Outline: Make a purchase by boleto with percentual discount
        Given percentual "<boleto_discount>" discount for boleto payment is provided
        And I am on checkout page using Inovarti One Step Checkout
        When I confirm payment
        Then the percentual discount of "<boleto_discount>" must be informed on checkout
        Examples:
        | boleto_discount |
        | 13.37           |
        | 42              |
