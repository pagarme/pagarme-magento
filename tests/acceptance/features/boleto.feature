Feature: Boleto 
    As an administrator of a webstore
    I want to use the transparent checkout for boleto
    So that the clients on my store can buy without knowing that the payment is resolved by Pagar.me

    Scenario: Make a purchase by boleto 
        Given a registered user
        When I access the store page
        And add any product to basket
        And I go to checkout page
        And login with registered user
        And confirm billing and shipping address information
        And choose pay with transparent checkout using boleto 
        And I confirm my payment information
        And place order
        Then the purchase must be placed with success
        And a link to boleto must be provided
        And I get the created order id
        And the order status should be "pending_payment"

    Scenario: Cancel order with unpaid boleto
        Given a registered user
        When I access the store page
        And add any product to basket
        And I go to checkout page
        And login with registered user
        And confirm billing and shipping address information
        And choose pay with transparent checkout using boleto
        And I confirm my payment information
        And place order
        Then the purchase must be placed with success
        And I get the created order id
        And simulate the boleto is expired
        And cancel orders with expired boletos by cron job model
        And the order status should be "canceled"
