Feature: Checkout Pagar.me
    Like a seller that use Magento
    I want to allow that purchases must be paid using Checkout Pagar.me
    To make sales

    Scenario: Make a purchase by boleto
        Given a registered user
        When I access the store page
        And add any product to basket
        And I go to checkout page
        And login with registered user
        And confirm billing and shipping address information
        And choose pay with pagar me checkout using "Boleto bancário"
        And I confirm my personal data
        And finish payment process
        And place order
        Then the purchase must be paid with success
        And a link to boleto must be provided

    @only
    Scenario Outline: Make a purchase by boleto with fixed discount
        Given a registered user
        And a <discount_mode> discount of <discount_value>
        When I access the store page
        And add any product to basket
        And I go to checkout page
        And login with registered user
        And confirm billing and shipping address information
        And choose pay with pagar me checkout using "Boleto bancário"
        And I confirm my personal data
        And finish payment process
        Then the discount must be described in checkout
        And the discount must be applied
        Examples:
        | discount_mode | discount_value |
        | fixed_value   | 6              |
        | percentage    | 2              |

    Scenario: Make a purchase by credit card
        Given a registered user
        And a valid credit card
        When I access the store page
        And add any product to basket
        And I go to checkout page
        And login with registered user
        And confirm billing and shipping address information
        And choose pay with pagar me checkout using "Cartão de crédito"
        And I confirm my personal data
        And I confirm my payment information with "1" installments
        And finish payment process
        And place order
        Then the purchase must be paid with success

    Scenario: Make a purchase by credit card with installments and interests
        Given a registered user
        And a valid credit card
        When I access the store page
        And add any product to basket
        And I go to checkout page
        And login with registered user
        And confirm billing and shipping address information
        And choose pay with pagar me checkout using "Cartão de crédito"
        And I confirm my personal data
        And I confirm my payment information with "4" installments
        And finish payment process
        Then the interest must applied
        And the interest must be described in checkout

    Scenario: Hide pagarme checkout button after success
        Given a registered user
        And a valid credit card
        When I access the store page
        And add any product to basket
        And I go to checkout page
        And login with registered user
        And confirm billing and shipping address information
        And choose pay with pagar me checkout using "Cartão de crédito"
        And I confirm my personal data
        And I confirm my payment information with "1" installments
        And finish payment process
        Then The button that opens pagarme checkout must be hidden

