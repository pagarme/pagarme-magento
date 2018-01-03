Feature: Credit Card
    As an administrator of a webstore
    I want to use the transparent checkout for credit card purchases with installments
    So that the clients on my store can buy their goods without knowing that the payment is resolved by Pagar.me

    Scenario: Make a purchase by credit card
        Given a registered user
        When I access the store page
        And add any product to basket
        And I go to checkout page
        And login with registered user
        And confirm billing and shipping address information
        And choose pay with transparent checkout using credit card
        And I confirm my payment information
        And place order
        Then the purchase must be paid with success

    Scenario Outline: Change the max installments configuration
        Given a registered user
        When I set max installments to "<max_installments>"
        And I access the store page
        And add any product to basket
        And I go to checkout page
        And login with registered user
        And confirm billing and shipping address information
        And choose pay with transparent checkout using credit card
        And I confirm my payment information
        And I should see only installment options up to "<max_installments>"
        And place order
        Then the purchase must be paid with success
        Examples:
        | max_installments  |
        | 12                |
        | 3                 |
        | 1                 |
